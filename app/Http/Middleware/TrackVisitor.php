<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use App\Services\BotDetector;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    // ┌─────────────────────────────────────────────────────────────────┐
    // │  TIMING CONTROLS — edit these values to change behaviour         │
    // │                                                                 │
    // │  VISITOR_WINDOW    — seconds before the same visitor is counted │
    // │                      as fresh again            (default: 12 h)  │
    // │  PAGEVIEW_COOLDOWN — seconds before the same visitor + page is  │
    // │                      counted as a new page view (default: 1 m)  │
    // │  SESSION_TIMEOUT   — inactivity gap that ends a session and the │
    // │                      duration window           (default: 30 m)  │
    // │  VISITOR_COOKIE_TTL— lifetime of the visitor id cookie, minutes │
    // └─────────────────────────────────────────────────────────────────┘
    public const VISITOR_WINDOW     = 12 * 3600; // 43 200 s  →  12 hours
    public const PAGEVIEW_COOLDOWN  = 60;        //     60 s  →  1 minute
    public const SESSION_TIMEOUT    = 30 * 60;   //  1 800 s  →  30 minutes (industry standard)
    public const VISITOR_COOKIE     = 'vid';
    public const VISITOR_COOKIE_TTL = 60 * 24 * 365; // 1 year, in minutes

    /**
     * Assign a stable visitor id cookie on the way in so we can recognise the
     * same browser across requests (and across IP changes). The cookie must be
     * queued during the request lifecycle — terminate() runs after the response
     * has already been sent, so it can't set cookies itself.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $vid = $request->cookie(self::VISITOR_COOKIE);

        if (! $vid || ! Str::isUuid($vid)) {
            $vid = (string) Str::uuid();
            cookie()->queue(cookie(
                self::VISITOR_COOKIE,
                $vid,
                self::VISITOR_COOKIE_TTL,
                httpOnly: true,
            ));
        }

        // Stash for terminate() — the response phase has the resolved id.
        $request->attributes->set(self::VISITOR_COOKIE, $vid);

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return;
        }

        if ($request->is('admin', 'admin/*', 'login', 'logout', 'livewire-*', 'livewire-*/*', '_debugbar/*', 'up')) {
            return;
        }

        $ip = $request->ip();
        if (! $ip) {
            return;
        }

        $path = '/' . ltrim($request->path(), '/');

        // ── Page-view dedup: same IP + same page within PAGEVIEW_COOLDOWN ──
        // Applies to humans and bots alike to keep write volume sane.
        $pvKey = 'pv_' . md5($ip . $path);
        if (cache()->has($pvKey)) {
            return;
        }
        cache()->put($pvKey, true, self::PAGEVIEW_COOLDOWN);

        $userAgent      = $request->userAgent() ?? '';
        $classification = BotDetector::classify($userAgent);

        if ($classification['is_bot']) {
            $this->recordBot($ip, $path, $request, $classification['reason']);
            return;
        }

        $this->recordHuman($ip, $path, $request, $userAgent);
    }

    /**
     * Persist a bot/crawler hit — flagged and lightweight (no geo lookup, no
     * session/duration bookkeeping). Kept so the dashboard can report exactly
     * how much traffic was filtered, and why.
     */
    private function recordBot(string $ip, string $path, Request $request, ?string $reason): void
    {
        VisitorLog::create([
            'ip_address'     => $ip,
            'page'           => $path,
            'referrer'       => $request->header('referer'),
            'device'         => 'Bot',
            'session_duration' => 0,
            'is_bounce'      => false,
            'is_new_visitor' => false,
            'is_bot'         => true,
            'bot_reason'     => $reason,
        ]);
    }

    /**
     * Persist a human page view with full geo, device, session and duration
     * tracking.
     */
    private function recordHuman(string $ip, string $path, Request $request, string $userAgent): void
    {
        // Stable visitor identity — cookie when available (set in handle()),
        // otherwise fall back to IP so cookieless clients still get tracked.
        $visitorId = $request->attributes->get(self::VISITOR_COOKIE)
            ?: $request->cookie(self::VISITOR_COOKIE);
        $track = $visitorId ?: $ip;

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        // ── Stamp duration on the previous page view for this visitor ──────
        $lastPvKey = 'last_pv_' . md5($track);
        $lastPv    = cache()->get($lastPvKey);
        if ($lastPv) {
            $elapsed = now()->timestamp - $lastPv['time'];
            // Only stamp within the session window; a larger gap means they left.
            if ($elapsed <= self::SESSION_TIMEOUT) {
                VisitorLog::where('id', $lastPv['id'])->update(['session_duration' => $elapsed]);
            }
        }

        // ── Session id: reuse while active, mint a fresh one after a gap ───
        $sessionId = $this->resolveSession($track);

        // ── Visitor dedup: first request from this visitor in VISITOR_WINDOW ─
        $visitorKey      = 'visitor_seen_' . md5($track);
        $isFirstInWindow = ! cache()->has($visitorKey);

        // Truly new = first-ever appearance of this visitor/IP in the database.
        $isNew = $isFirstInWindow && ! $this->hasBeenSeenBefore($visitorId, $ip);

        $browser = $agent->browser() ?: null;
        $os      = $agent->platform() ?: null;
        $device  = $agent->isTablet() ? 'Tablet' : ($agent->isMobile() ? 'Mobile' : 'Desktop');

        // ── Geo lookup — cached 24 h per IP ───────────────────────────────
        [$country, $countryCode, $city, $lat, $lon] = array_pad(
            cache()->remember('geoip_' . md5($ip), 86400, fn () => $this->geoLookup($ip)),
            5,
            null
        );

        $log = VisitorLog::create([
            'ip_address'       => $ip,
            'visitor_id'       => $visitorId ?: null,
            'session_id'       => $sessionId,
            'country'          => $country,
            'country_code'     => $countryCode,
            'city'             => $city,
            'lat'              => $lat,
            'lon'              => $lon,
            'browser'          => $browser,
            'os'               => $os,
            'device'           => $device,
            'page'             => $path,
            'referrer'         => $request->header('referer'),
            'session_duration' => 0,   // filled in when the next request arrives
            'is_bounce'        => false,
            'is_new_visitor'   => $isNew,
            'is_bot'           => false,
        ]);

        // Remember this page view so the next request can stamp its duration.
        cache()->put($lastPvKey, ['id' => $log->id, 'time' => now()->timestamp], self::SESSION_TIMEOUT);

        if ($isFirstInWindow) {
            cache()->put($visitorKey, true, self::VISITOR_WINDOW);
        }
    }

    /**
     * Return the current session id for a visitor, minting a new one when the
     * previous activity is older than SESSION_TIMEOUT.
     */
    private function resolveSession(string $track): string
    {
        $key  = 'sess_' . md5($track);
        $now  = now()->timestamp;
        $sess = cache()->get($key);

        $sessionId = ($sess && ($now - $sess['last']) <= self::SESSION_TIMEOUT)
            ? $sess['id']
            : (string) Str::uuid();

        cache()->put($key, ['id' => $sessionId, 'last' => $now], self::SESSION_TIMEOUT);

        return $sessionId;
    }

    /**
     * Has this visitor (by cookie id, else by IP) ever been recorded before?
     */
    private function hasBeenSeenBefore(?string $visitorId, string $ip): bool
    {
        if ($visitorId) {
            return VisitorLog::where('visitor_id', $visitorId)->exists();
        }

        return VisitorLog::where('ip_address', $ip)->exists();
    }

    private function geoLookup(string $ip): array
    {
        if (
            $ip === '127.0.0.1' ||
            $ip === '::1' ||
            str_starts_with($ip, '192.168.') ||
            str_starts_with($ip, '10.') ||
            preg_match('/^172\.(1[6-9]|2\d|3[01])\./', $ip)  // RFC 1918: 172.16–172.31
        ) {
            return ['Local', null, null, null, null];
        }

        try {
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $raw = @file_get_contents(
                "http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city,lat,lon",
                false,
                $ctx
            );

            if ($raw) {
                $data = json_decode($raw, true);
                if (is_array($data) && ($data['status'] ?? '') === 'success') {
                    return [
                        $data['country']     ?? null,
                        $data['countryCode'] ?? null,
                        $data['city']        ?? null,
                        isset($data['lat'])  ? (float) $data['lat'] : null,
                        isset($data['lon'])  ? (float) $data['lon'] : null,
                    ];
                }
            }
        } catch (\Throwable) {
            // Geo is best-effort
        }

        return [null, null, null, null, null];
    }
}
