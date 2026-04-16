<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    // ┌─────────────────────────────────────────────────────────────────┐
    // │  TIMING CONTROLS — edit these two values to change behaviour    │
    // │                                                                 │
    // │  VISITOR_WINDOW    — seconds before the same IP is counted as   │
    // │                      a fresh visitor again  (default: 12 h)     │
    // │                                                                 │
    // │  PAGEVIEW_COOLDOWN — seconds before the same IP + page combo    │
    // │                      is counted as a new page view (default: 1m)│
    // └─────────────────────────────────────────────────────────────────┘
    public const VISITOR_WINDOW    = 12 * 3600; // 43 200 s  →  12 hours
    public const PAGEVIEW_COOLDOWN = 60;         //     60 s  →  1 minute
    public const SESSION_TIMEOUT   = 30 * 60;   //  1 800 s  →  30 minutes (industry standard)

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return;
        }

        if ($request->is('admin', 'admin/*', 'livewire-*', 'livewire-*/*', '_debugbar/*', 'up')) {
            return;
        }

        $agent = new Agent();
        $agent->setUserAgent($request->userAgent() ?? '');

        if ($agent->isRobot()) {
            return;
        }

        $ip = $request->ip();
        if (! $ip) {
            return;
        }

        $path = '/' . ltrim($request->path(), '/');

        // ── Page-view dedup: same IP + same page within PAGEVIEW_COOLDOWN ──
        $pvKey = 'pv_' . md5($ip . $path);
        if (cache()->has($pvKey)) {
            return;
        }

        // ── Stamp duration on the previous page view for this IP ──────────
        // When the visitor loads a new page we know how long they spent on
        // the previous one: elapsed = now - time we recorded that page view.
        $lastPvKey = 'last_pv_' . md5($ip);
        $lastPv    = cache()->get($lastPvKey);
        if ($lastPv) {
            $elapsed = now()->timestamp - $lastPv['time'];
            // Only stamp if within the session window — if more than 30 min passed,
            // the user left and came back, so the previous page duration is unknown (stays 0).
            if ($elapsed <= self::SESSION_TIMEOUT) {
                VisitorLog::where('id', $lastPv['id'])->update(['session_duration' => $elapsed]);
            }
        }

        // ── Visitor dedup: first request from this IP in VISITOR_WINDOW? ──
        $visitorKey      = 'visitor_seen_' . md5($ip);
        $isFirstInWindow = ! cache()->has($visitorKey);

        // Truly new = first-ever appearance of this IP in the database
        $isNew = $isFirstInWindow && ! VisitorLog::where('ip_address', $ip)->exists();

        // ── Browser / OS / device ────────────────────────────────────────
        $browser = $agent->browser() ?: null;
        $os      = $agent->platform() ?: null;
        $device  = $agent->isTablet() ? 'Tablet' : ($agent->isMobile() ? 'Mobile' : 'Desktop');

        // ── Geo lookup — cached 24 h per IP ──────────────────────────────
        // array_pad handles old 3-element cached values during the transition
        [$country, $countryCode, $city, $lat, $lon] = array_pad(
            cache()->remember(
                'geoip_' . md5($ip),
                86400,
                fn () => $this->geoLookup($ip)
            ),
            5,
            null
        );

        // ── Create page-view record ───────────────────────────────────────
        $log = VisitorLog::create([
            'ip_address'       => $ip,
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
        ]);

        // Mark this page viewed — blocks re-count until PAGEVIEW_COOLDOWN expires
        cache()->put($pvKey, true, self::PAGEVIEW_COOLDOWN);

        // Remember this page view so the next request can stamp its duration.
        // TTL = SESSION_TIMEOUT: beyond 30 min we won't stamp anyway, no need to keep it longer.
        cache()->put($lastPvKey, ['id' => $log->id, 'time' => now()->timestamp], self::SESSION_TIMEOUT);

        // Mark visitor as seen — blocks visitor re-count until VISITOR_WINDOW expires
        if ($isFirstInWindow) {
            cache()->put($visitorKey, true, self::VISITOR_WINDOW);
        }
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
