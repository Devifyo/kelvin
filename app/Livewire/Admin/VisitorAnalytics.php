<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class VisitorAnalytics extends Component
{
    public string $period = 'today';

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getStatsProperty(): array
    {
        return match ($this->period) {
            'week'  => ['visitors' => 1842, 'pageviews' => 5614, 'avg_session' => '3m 41s', 'bounce_rate' => '39%', 'new_visitors' => 1104],
            'month' => ['visitors' => 7230, 'pageviews' => 21940, 'avg_session' => '4m 02s', 'bounce_rate' => '36%', 'new_visitors' => 4521],
            default => ['visitors' => 247, 'pageviews' => 814, 'avg_session' => '3m 24s', 'bounce_rate' => '42%', 'new_visitors' => 138],
        };
    }

    public function getCountriesProperty(): array
    {
        return match ($this->period) {
            'week' => [
                ['country' => 'United States', 'code' => 'US', 'visitors' => 698, 'pct' => 38],
                ['country' => 'United Kingdom', 'code' => 'GB', 'visitors' => 350, 'pct' => 19],
                ['country' => 'Canada',         'code' => 'CA', 'visitors' => 221, 'pct' => 12],
                ['country' => 'Australia',      'code' => 'AU', 'visitors' => 147, 'pct' => 8],
                ['country' => 'Germany',        'code' => 'DE', 'visitors' => 129, 'pct' => 7],
                ['country' => 'Other',          'code' => '--', 'visitors' => 297, 'pct' => 16],
            ],
            'month' => [
                ['country' => 'United States', 'code' => 'US', 'visitors' => 2747, 'pct' => 38],
                ['country' => 'United Kingdom', 'code' => 'GB', 'visitors' => 1374, 'pct' => 19],
                ['country' => 'Canada',         'code' => 'CA', 'visitors' => 868, 'pct' => 12],
                ['country' => 'Australia',      'code' => 'AU', 'visitors' => 578, 'pct' => 8],
                ['country' => 'Germany',        'code' => 'DE', 'visitors' => 506, 'pct' => 7],
                ['country' => 'Other',          'code' => '--', 'visitors' => 1157, 'pct' => 16],
            ],
            default => [
                ['country' => 'United States', 'code' => 'US', 'visitors' => 94,  'pct' => 38],
                ['country' => 'United Kingdom', 'code' => 'GB', 'visitors' => 47,  'pct' => 19],
                ['country' => 'Canada',         'code' => 'CA', 'visitors' => 30,  'pct' => 12],
                ['country' => 'Australia',      'code' => 'AU', 'visitors' => 20,  'pct' => 8],
                ['country' => 'Germany',        'code' => 'DE', 'visitors' => 17,  'pct' => 7],
                ['country' => 'Other',          'code' => '--', 'visitors' => 39,  'pct' => 16],
            ],
        };
    }

    public function getDevicesProperty(): array
    {
        return [
            ['label' => 'Desktop', 'pct' => 58, 'color' => '#b5722a'],
            ['label' => 'Mobile',  'pct' => 35, 'color' => '#d4924e'],
            ['label' => 'Tablet',  'pct' => 7,  'color' => '#edb97a'],
        ];
    }

    public function getBrowsersProperty(): array
    {
        return [
            ['label' => 'Chrome',  'pct' => 52, 'icon' => 'C'],
            ['label' => 'Safari',  'pct' => 24, 'icon' => 'S'],
            ['label' => 'Firefox', 'pct' => 14, 'icon' => 'F'],
            ['label' => 'Edge',    'pct' => 8,  'icon' => 'E'],
            ['label' => 'Other',   'pct' => 2,  'icon' => '?'],
        ];
    }

    public function getTopPagesProperty(): array
    {
        return [
            ['path' => '/', 'label' => 'Home', 'views' => 312, 'pct' => 38],
            ['path' => '/consulting', 'label' => 'Consulting Services', 'views' => 189, 'pct' => 23],
            ['path' => '/blog', 'label' => 'Blog', 'views' => 147, 'pct' => 18],
            ['path' => '/training', 'label' => 'Training Classes', 'views' => 98, 'pct' => 12],
            ['path' => '/contact', 'label' => 'Contact', 'views' => 68, 'pct' => 9],
        ];
    }

    public function getRecentVisitorsProperty(): array
    {
        return [
            ['ip' => '192.168.1.xxx',  'country' => 'United States', 'city' => 'New York',      'browser' => 'Chrome',  'os' => 'Windows 11', 'page' => '/',            'time' => '2 min ago'],
            ['ip' => '10.0.45.xxx',    'country' => 'United Kingdom', 'city' => 'London',        'browser' => 'Safari',  'os' => 'macOS',       'page' => '/consulting',  'time' => '5 min ago'],
            ['ip' => '172.16.8.xxx',   'country' => 'Canada',         'city' => 'Toronto',       'browser' => 'Chrome',  'os' => 'Android',     'page' => '/blog',        'time' => '9 min ago'],
            ['ip' => '203.0.113.xxx',  'country' => 'Australia',      'city' => 'Sydney',        'browser' => 'Firefox', 'os' => 'Ubuntu',      'page' => '/training',    'time' => '14 min ago'],
            ['ip' => '198.51.100.xxx', 'country' => 'Germany',        'city' => 'Berlin',        'browser' => 'Edge',    'os' => 'Windows 10',  'page' => '/contact',     'time' => '21 min ago'],
            ['ip' => '192.0.2.xxx',    'country' => 'United States',  'city' => 'Chicago',       'browser' => 'Chrome',  'os' => 'iOS',         'page' => '/',            'time' => '28 min ago'],
            ['ip' => '100.64.22.xxx',  'country' => 'United States',  'city' => 'Los Angeles',   'browser' => 'Safari',  'os' => 'macOS',       'page' => '/papers',      'time' => '35 min ago'],
            ['ip' => '169.254.5.xxx',  'country' => 'United Kingdom', 'city' => 'Manchester',    'browser' => 'Chrome',  'os' => 'Windows 11',  'page' => '/blog',        'time' => '42 min ago'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.visitor-analytics')->layout('layouts.admin', ['title' => 'Training Classes']);
    }
}
