<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
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
    ];

    protected $casts = [
        'is_bounce'      => 'boolean',
        'is_new_visitor' => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLastDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days)->startOfDay());
    }

    // ── Aggregates ──────────────────────────────────────

    public static function stats(string $period = 'today'): array
    {
        $query = match ($period) {
            'week'  => static::lastDays(7),
            'month' => static::lastDays(30),
            default => static::today(),
        };

        // Each row = one page view; visitors = distinct IPs
        $pageviews = (clone $query)->count();
        $visitors  = (clone $query)->distinct('ip_address')->count('ip_address');

        // Average session duration: sum page durations per IP per day (one "session" = one IP per day),
        // then average those session totals. Cap each page duration at 1800s (30 min) to exclude
        // stale records where the user left and came back hours later within the same cache window.
        $avgSec = DB::table(
                (clone $query)
                    ->selectRaw('SUM(LEAST(COALESCE(session_duration, 0), 1800)) as total')
                    ->groupBy('ip_address', DB::raw('DATE(created_at)'))
                    ->toBase(),
                'sessions'
            )->avg('total') ?? 0;

        // Bounce: visitors who only loaded 1 page in this period
        $bounced = (clone $query)
            ->selectRaw('ip_address, COUNT(*) as cnt')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) = 1')
            ->get()
            ->count();

        // New visitors: first-ever visit (is_new_visitor set once per IP lifetime)
        $new = (clone $query)->where('is_new_visitor', true)->count();

        $m = intdiv((int) $avgSec, 60);
        $s = (int) $avgSec % 60;

        return [
            'visitors'        => $visitors,
            'pageviews'       => $pageviews,
            'avg_session'     => "{$m}m {$s}s",
            'avg_session_sec' => (int) $avgSec,
            'bounce_rate'     => $visitors > 0 ? round($bounced / $visitors * 100) . '%' : '0%',
            'bounce_pct'      => $visitors > 0 ? (int) round($bounced / $visitors * 100) : 0,
            'new_visitors'    => $new,
        ];
    }

    public static function topCountries(string $period = 'today'): array
    {
        $query = match ($period) {
            'week'  => static::lastDays(7),
            'month' => static::lastDays(30),
            default => static::today(),
        };

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

    public static function deviceBreakdown(string $period = 'today'): array
    {
        $query = match ($period) {
            'week'  => static::lastDays(7),
            'month' => static::lastDays(30),
            default => static::today(),
        };

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

    public static function browserBreakdown(string $period = 'today'): array
    {
        $query = match ($period) {
            'week'  => static::lastDays(7),
            'month' => static::lastDays(30),
            default => static::today(),
        };

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
        return static::latest()
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
     * All-time geographic data for the world map.
     * Returns country bubbles and city dots with visitor counts + coordinates.
     */
    public static function mapData(): array
    {
        $countries = static::query()
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

        $cities = static::query()
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

    public static function topPages(string $period = 'today'): array
    {
        $query = match ($period) {
            'week'  => static::lastDays(7),
            'month' => static::lastDays(30),
            default => static::today(),
        };

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
