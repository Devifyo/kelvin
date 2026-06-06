<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Self-contained, dependency-free CAPTCHA.
 *
 * Defence in depth (no third-party service, no GD extension required):
 *   1. A distorted alphanumeric code rendered as inline SVG — the answer is
 *      never present in the DOM as plain text.
 *   2. A stateless HMAC token: the browser only ever receives a SHA-256 hash of
 *      the answer signed with the app key. The plaintext answer never leaves the
 *      server, and any tampering with the token is detected via hash_equals().
 *   3. A time-trap (issued-at): submissions faster than a human can type, or
 *      older than the TTL, are rejected.
 *   4. Single-use replay protection: a solved token's nonce is burned in the
 *      cache so the same token cannot be replayed.
 *
 * Honeypot and rate-limiting live alongside this in the Blade component / routes.
 */
class Captcha
{
    /** Seconds a challenge stays valid. */
    private const TTL = 600;

    /** A genuine human cannot read + type the code faster than this. */
    private const MIN_SOLVE_SECONDS = 2;

    /** Number of characters in the challenge. */
    private const LENGTH = 5;

    /** Unambiguous character set (no 0/O, 1/I/L, etc.). */
    private const CHARS = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    /**
     * Issue a fresh challenge.
     *
     * @return array{svg: string, token: string}
     */
    public static function issue(): array
    {
        $code = self::randomCode();

        return [
            'svg'   => self::render($code),
            'token' => self::sign($code),
        ];
    }

    /**
     * Verify a user-supplied answer against its signed token.
     *
     * Returns true only when every layer passes: signature is authentic, the
     * token is unexpired, it was not solved suspiciously fast, it has not been
     * used before, and the answer matches.
     */
    /**
     * Number of characters in a challenge (exposed for the UI/input length).
     */
    public static function length(): int
    {
        return self::LENGTH;
    }

    /**
     * Verify a user-supplied answer against its signed token.
     *
     * Returns true only when every layer passes: signature is authentic, the
     * token is unexpired, it was not solved suspiciously fast, it has not been
     * used before, and the answer matches.
     */
    public static function verify(?string $answer, ?string $token): bool
    {
        if ($answer === null || $answer === '' || $token === null || ! str_contains($token, '.')) {
            return false;
        }

        [$payloadPart, $signature] = explode('.', $token, 2);

        // 1. Authenticity — was this token minted by us, untampered?
        $expected = hash_hmac('sha256', $payloadPart, self::key());
        if (! hash_equals($expected, $signature)) {
            return false;
        }

        $payload = json_decode(self::b64UrlDecode($payloadPart), true);
        if (! is_array($payload)) {
            return false;
        }

        $now    = time();
        $issued = (int) ($payload['iat'] ?? 0);
        $expiry = (int) ($payload['exp'] ?? 0);
        $nonce  = (string) ($payload['n'] ?? '');
        $hash   = (string) ($payload['a'] ?? '');

        // 2. Freshness — not expired.
        if ($expiry < $now) {
            return false;
        }

        // 3. Time-trap — not solved impossibly fast (a bot auto-submitting).
        if (($now - $issued) < self::MIN_SOLVE_SECONDS) {
            return false;
        }

        // 4. Replay protection — a solved token can only be used once.
        $usedKey = 'captcha_used:' . $nonce;
        if ($nonce === '' || Cache::has($usedKey)) {
            return false;
        }

        // 5. The answer itself (case-insensitive, whitespace-tolerant).
        $matches = hash_equals($hash, self::hashAnswer($answer));

        if ($matches) {
            // Burn the nonce so this token cannot be reused.
            Cache::put($usedKey, true, self::TTL);
        }

        return $matches;
    }

    /**
     * Build a random challenge string.
     */
    private static function randomCode(): string
    {
        $chars = self::CHARS;
        $max   = strlen($chars) - 1;
        $code  = '';

        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= $chars[random_int(0, $max)];
        }

        return $code;
    }

    /**
     * Produce a signed, self-describing token for a challenge code.
     */
    private static function sign(string $code): string
    {
        $payload = json_encode([
            'a'   => self::hashAnswer($code),
            'iat' => time(),
            'exp' => time() + self::TTL,
            'n'   => Str::random(24),
        ]);

        $encoded   = self::b64UrlEncode($payload);
        $signature = hash_hmac('sha256', $encoded, self::key());

        return $encoded . '.' . $signature;
    }

    /**
     * Normalise then hash an answer so comparison is case/space insensitive.
     */
    private static function hashAnswer(string $answer): string
    {
        return hash('sha256', strtoupper(trim($answer)));
    }

    /**
     * Render the code as a refined, distorted inline SVG image.
     *
     * The artwork layers a soft gradient ground, guilloché "security threads"
     * (the overlapping sine curves found on banknotes — elegant and hostile to
     * OCR), gradient-filled serif characters each individually rotated and
     * jittered, a thread woven across the glyphs, and fine speckle noise.
     */
    private static function render(string $code): string
    {
        $width  = 248;
        $height = 84;
        $ink    = ['#1a2332', '#243345', '#2f4259'];   // slate family
        $accent = ['#b5722a', '#8a5520', '#a0641f'];   // copper family

        $defs = '<defs>'
            . '<radialGradient id="cap-bg" cx="50%" cy="38%" r="85%">'
            . '<stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#f2ece3"/>'
            . '</radialGradient>'
            . '<linearGradient id="cap-ink" x1="0" y1="0" x2="0" y2="1">'
            . '<stop offset="0" stop-color="#243345"/><stop offset="1" stop-color="#1a2332"/>'
            . '</linearGradient>'
            . '<linearGradient id="cap-copper" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" stop-color="#b5722a"/><stop offset="1" stop-color="#8a5520"/>'
            . '</linearGradient>'
            . '</defs>';

        $parts = [
            $defs,
            '<rect width="' . $width . '" height="' . $height . '" fill="url(#cap-bg)"/>',
        ];

        // Guilloché security threads — smooth full-width sine curves.
        for ($g = 0; $g < 4; $g++) {
            $amp   = random_int(5, 13);
            $freq  = random_int(2, 4);
            $phase = random_int(0, 62) / 10;
            $mid   = random_int(18, 66);
            $d     = 'M0 ' . $mid;
            for ($x = 0; $x <= $width; $x += 6) {
                $y = $mid + $amp * sin(($x / $width) * $freq * 6.2832 + $phase);
                $d .= ' L' . $x . ' ' . round($y, 1);
            }
            $stroke = $g % 2 ? $accent[0] : $ink[2];
            $parts[] = '<path d="' . $d . '" stroke="' . $stroke . '" stroke-width="0.7" fill="none" opacity="0.16"/>';
        }

        // The characters — gradient-filled, individually jittered and rotated.
        $length = strlen($code);
        $step   = ($width - 44) / $length;

        for ($i = 0; $i < $length; $i++) {
            $char = htmlspecialchars($code[$i], ENT_QUOTES);
            $x    = 30 + ($i * $step);
            $y    = random_int(52, 60);
            $rot  = random_int(-22, 22);
            $size = random_int(34, 42);
            $fill = $i % 2 ? 'url(#cap-copper)' : 'url(#cap-ink)';

            $parts[] = sprintf(
                '<text x="%d" y="%d" font-family="Georgia, \'Times New Roman\', serif" font-size="%d" font-weight="700" '
                . 'fill="%s" transform="rotate(%d %d %d)" style="letter-spacing:0">%s</text>',
                $x, $y, $size, $fill, $rot, $x, $y, $char
            );
        }

        // A copper thread woven across the glyphs (foreground, anti-OCR).
        $mid = (int) ($height / 2);
        $d   = 'M0 ' . $mid;
        for ($x = 0; $x <= $width; $x += 8) {
            $y = $mid + random_int(-16, 16);
            $d .= ' Q' . ($x - 4) . ' ' . $y . ' ' . $x . ' ' . ($mid + random_int(-10, 10));
        }
        $parts[] = '<path d="' . $d . '" stroke="' . $accent[1] . '" stroke-width="1.1" fill="none" opacity="0.30"/>';

        // Fine speckle noise.
        for ($i = 0; $i < 55; $i++) {
            $palette = ($i % 3 === 0) ? $accent : $ink;
            $parts[] = sprintf(
                '<circle cx="%d" cy="%d" r="%s" fill="%s" opacity="0.28"/>',
                random_int(0, $width), random_int(0, $height),
                random_int(4, 14) / 10, $palette[random_int(0, 2)]
            );
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '" '
            . 'width="' . $width . '" height="' . $height . '" preserveAspectRatio="xMidYMid meet" role="img" '
            . 'aria-label="Verification image showing distorted characters. Use the refresh button for a new image if unreadable.">'
            . implode('', $parts)
            . '</svg>';
    }

    private static function key(): string
    {
        return (string) config('app.key');
    }

    private static function b64UrlEncode(string $binary): string
    {
        return rtrim(strtr(base64_encode($binary), '+/', '-_'), '=');
    }

    private static function b64UrlDecode(string $value): string
    {
        return (string) base64_decode(strtr($value, '-_', '+/'), true);
    }
}
