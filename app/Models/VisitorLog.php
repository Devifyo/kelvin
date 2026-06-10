<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'visitor_id',
        'session_id',
        'country',
        'country_code',
        'city',
        'lat',
        'lon',
        'browser',
        'os',
        'device',
        'page',
        'referrer',
        'session_duration',
        'is_bounce',
        'is_new_visitor',
        'is_bot',
        'bot_reason',
    ];

    protected $casts = [
        'is_bounce'      => 'boolean',
        'is_new_visitor' => 'boolean',
        'is_bot'         => 'boolean',
    ];

    /**
     * Per-page duration cap (seconds). A single page longer than the session
     * timeout means the visitor left and came back, so we don't count the gap.
     */
    private const SESSION_CAP = 1800;

    // ── Scopes ──────────────────────────────────────────

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLastDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days)->startOfDay());
    }

    /** Real human traffic only — excludes classified bots/crawlers. */
    public function scopeHumans(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    /** Filtered automated traffic only. */
    public function scopeBots(Builder $query): Builder
    {
        return $query->where('is_bot', true);
    }

    // ── Period helpers ──────────────────────────────────

    /**
     * Resolve a period (preset or custom range) to [start, end] timestamps.
     * 'custom' uses the supplied from/to dates (inclusive whole days); the
     * presets are relative to now.
     *
     * @return array{0: CarbonInterface, 1: CarbonInterface}
     */
    private static function bounds(string $period, ?string $from = null, ?string $to = null): array
    {
        if ($period === 'custom' && $from && $to) {
            return [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()];
        }

        return match ($period) {
            'week'  => [now()->subDays(7)->startOfDay(), now()],
            'month' => [now()->subDays(30)->startOfDay(), now()],
            default => [today(), now()],
        };
    }

    private static function periodScope(string $period, ?string $from = null, ?string $to = null): Builder
    {
        [$start, $end] = self::bounds($period, $from, $to);

        return static::whereBetween('created_at', [$start, $end]);
    }

    private static function periodStart(string $period, ?string $from = null, ?string $to = null): CarbonInterface
    {
        return self::bounds($period, $from, $to)[0];
    }

    /** Base query for a period restricted to human traffic. */
    private static function humanPeriod(string $period, ?string $from = null, ?string $to = null): Builder
    {
        return static::periodScope($period, $from, $to)->humans();
    }

    /**
     * SQL that caps a page's duration at SESSION_CAP — portable across MySQL
     * and SQLite (avoids LEAST(), which SQLite lacks).
     */
    private static function cappedDurationSql(): string
    {
        $cap = self::SESSION_CAP;

        return "SUM(CASE WHEN COALESCE(session_duration, 0) > {$cap} THEN {$cap} ELSE COALESCE(session_duration, 0) END)";
    }

    /**
     * GROUP BY expressions that collapse rows into sessions: one group per
     * session_id, falling back to IP + day for legacy rows that predate
     * session tracking. Portable (CASE + DATE work on MySQL and SQLite).
     *
     * @return array<int, mixed>
     */
    private static function sessionGrouping(): array
    {
        return [
            'session_id',
            DB::raw('CASE WHEN session_id IS NULL THEN ip_address END'),
            DB::raw('CASE WHEN session_id IS NULL THEN DATE(created_at) END'),
        ];
    }

    // ── Aggregates ──────────────────────────────────────

    public static function stats(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        $human = static::humanPeriod($period, $from, $to);

        $pageviews = (clone $human)->count();
        $visitors  = (int) (clone $human)
            ->selectRaw('COUNT(DISTINCT COALESCE(visitor_id, ip_address)) as c')
            ->value('c');

        // Per-session duration totals (carrying location for the peak session).
        $sessionTotals = (clone $human)
            ->selectRaw('MAX(country) as country, MAX(city) as city, ' . self::cappedDurationSql() . ' as total')
            ->groupBy(...self::sessionGrouping())
            ->toBase();

        $sessionCount = DB::table($sessionTotals, 's')->count();
        $avgSec       = (int) round(DB::table($sessionTotals, 's')->avg('total') ?? 0);

        // Peak (longest) session — the most engaged visitor, with their location.
        $peak    = DB::table($sessionTotals, 's')->orderByDesc('total')->first();
        $maxSec  = (int) ($peak->total ?? 0);
        $maxFrom = $peak ? trim(($peak->city ? $peak->city . ', ' : '') . ($peak->country ?? '')) : '';

        // Pages per session — engagement depth.
        $pagesPerSession = $sessionCount > 0 ? round($pageviews / $sessionCount, 1) : 0.0;

        // Returning visitors — cookie ids seen for the first time before this
        // period (i.e. they came back). Cookieless/legacy traffic is excluded.
        $returning = (clone $human)
            ->whereNotNull('visitor_id')
            ->whereIn('visitor_id', function ($sub) use ($period, $from, $to) {
                $sub->from('visitor_logs')
                    ->select('visitor_id')
                    ->whereNotNull('visitor_id')
                    ->where('created_at', '<', self::periodStart($period, $from, $to));
            })
            ->distinct()
            ->count('visitor_id');

        $new = (clone $human)->where('is_new_visitor', true)
            ->selectRaw('COUNT(DISTINCT COALESCE(visitor_id, ip_address)) as c')
            ->value('c');

        // Bounce — single-page-view sessions (kept for re-enabling the card).
        $bounced = DB::table(
            (clone $human)
                ->selectRaw('COUNT(*) as views')
                ->groupBy(...self::sessionGrouping())
                ->toBase(),
            's'
        )->where('views', 1)->count();

        // Human vs filtered bot volume for the same period.
        $botPageviews = (int) static::periodScope($period, $from, $to)->bots()->count();
        $totalTraffic = $pageviews + $botPageviews;
        $botPct       = $totalTraffic > 0 ? (int) round($botPageviews / $totalTraffic * 100) : 0;

        return [
            'visitors'          => $visitors,
            'pageviews'         => $pageviews,
            'sessions'          => $sessionCount,
            'avg_session'       => self::formatDuration($avgSec),
            'avg_session_sec'   => $avgSec,
            'max_session'       => self::formatDuration($maxSec),
            'max_session_sec'   => $maxSec,
            'max_session_from'  => $maxFrom,
            'pages_per_session' => number_format($pagesPerSession, 1),
            'returning_visitors' => (int) $returning,
            'new_visitors'      => (int) $new,
            'bounce_rate'       => $sessionCount > 0 ? round($bounced / $sessionCount * 100) . '%' : '0%',
            'bounce_pct'        => $sessionCount > 0 ? (int) round($bounced / $sessionCount * 100) : 0,
            // Traffic-quality split
            'human_pageviews'   => $pageviews,
            'bot_pageviews'     => $botPageviews,
            'bot_pct'           => $botPct,
        ];
    }

    private static function formatDuration(int $seconds): string
    {
        return intdiv($seconds, 60) . 'm ' . ($seconds % 60) . 's';
    }

    public static function topCountries(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        $query = static::humanPeriod($period, $from, $to);
        $total = max((clone $query)->count(), 1);

        return (clone $query)
            ->selectRaw('country, country_code, COUNT(*) as visitors')
            ->whereNotNull('country_code')
            ->groupBy('country', 'country_code')
            ->orderByDesc('visitors')
            ->limit(6)
            ->get()
            ->map(fn ($r) => [
                'country'  => $r->country,
                'code'     => $r->country_code,
                'visitors' => $r->visitors,
                'pct'      => round($r->visitors / $total * 100),
            ])
            ->all();
    }

    public static function deviceBreakdown(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        $query = static::humanPeriod($period, $from, $to);
        $total = max((clone $query)->count(), 1);

        $colors = ['Desktop' => '#b5722a', 'Mobile' => '#d4924e', 'Tablet' => '#edb97a'];

        return (clone $query)
            ->selectRaw('device, COUNT(*) as cnt')
            ->whereNotNull('device')
            ->groupBy('device')
            ->orderByDesc('cnt')
            ->get()
            ->map(fn ($r) => [
                'label' => ucfirst($r->device),
                'pct'   => round($r->cnt / $total * 100),
                'color' => $colors[ucfirst($r->device)] ?? '#ccc',
            ])
            ->all();
    }

    public static function browserBreakdown(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        $query = static::humanPeriod($period, $from, $to);
        $total = max((clone $query)->count(), 1);

        $icons = ['Chrome' => 'C', 'Safari' => 'S', 'Firefox' => 'F', 'Edge' => 'E'];

        return (clone $query)
            ->selectRaw('browser, COUNT(*) as cnt')
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('cnt')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->browser,
                'pct'   => round($r->cnt / $total * 100),
                'icon'  => $icons[$r->browser] ?? '?',
            ])
            ->all();
    }

    public static function recentVisitors(int $limit = 10): array
    {
        return static::humans()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($r) {
                $sec = (int) $r->session_duration;
                $duration = $sec > 0
                    ? intdiv($sec, 60) . 'm ' . ($sec % 60) . 's'
                    : '–';

                return [
                    'ip'       => preg_replace('/\.\d+$/', '.xxx', $r->ip_address ?? ''),
                    'country'  => $r->country,
                    'city'     => $r->city,
                    'browser'  => $r->browser,
                    'os'       => $r->os,
                    'page'     => $r->page,
                    'duration' => $duration,
                    'time'     => $r->created_at->diffForHumans(),
                ];
            })
            ->all();
    }

    /**
     * All-time geographic data for the world map (human traffic only).
     */
    public static function mapData(): array
    {
        $countries = static::humans()
            ->selectRaw('country, country_code, ROUND(AVG(lat), 4) as lat, ROUND(AVG(lon), 4) as lon, COUNT(DISTINCT ip_address) as visitors')
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('visitors')
            ->get()
            ->map(fn ($r) => [
                'name'     => $r->country,
                'code'     => $r->country_code,
                'lat'      => (float) $r->lat,
                'lon'      => (float) $r->lon,
                'visitors' => (int) $r->visitors,
            ])
            ->values()
            ->all();

        $cities = static::humans()
            ->selectRaw('city, country, ROUND(AVG(lat), 4) as lat, ROUND(AVG(lon), 4) as lon, COUNT(DISTINCT ip_address) as visitors')
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->whereNotNull('city')
            ->groupBy('city', 'country')
            ->orderByDesc('visitors')
            ->limit(150)
            ->get()
            ->map(fn ($r) => [
                'city'     => $r->city,
                'country'  => $r->country,
                'lat'      => (float) $r->lat,
                'lon'      => (float) $r->lon,
                'visitors' => (int) $r->visitors,
            ])
            ->values()
            ->all();

        return ['countries' => $countries, 'cities' => $cities];
    }

    public static function topPages(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        $query = static::humanPeriod($period, $from, $to);
        $total = max((clone $query)->count(), 1);

        return (clone $query)
            ->selectRaw('page, COUNT(*) as views')
            ->whereNotNull('page')
            ->groupBy('page')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(fn ($r) => [
                'path'  => $r->page,
                'label' => ucfirst(trim($r->page, '/') ?: 'Home'),
                'views' => $r->views,
                'pct'   => round($r->views / $total * 100),
            ])
            ->all();
    }
}
