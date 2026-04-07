<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'country',
        'country_code',
        'city',
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

        $total    = (clone $query)->count();
        $pageviews = (clone $query)->sum('session_duration') > 0 ? (clone $query)->count() : $total;
        $avgSec   = (clone $query)->avg('session_duration') ?? 0;
        $bounces  = (clone $query)->where('is_bounce', true)->count();
        $new      = (clone $query)->where('is_new_visitor', true)->count();

        $m = intdiv((int) $avgSec, 60);
        $s = (int) $avgSec % 60;

        return [
            'visitors'    => $total,
            'pageviews'   => $pageviews,
            'avg_session' => "{$m}m {$s}s",
            'bounce_rate' => $total > 0 ? round($bounces / $total * 100) . '%' : '0%',
            'new_visitors' => $new,
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
