<?php

namespace App\Livewire\Admin;

use App\Models\VisitorLog;
use Livewire\Component;

class VisitorAnalytics extends Component
{
    public string $period = 'today';

    // ── Custom date range (active when $period === 'custom') ─────────────
    public ?string $startDate = null;
    public ?string $endDate   = null;

    // ── All-visitors infinite scroll ─────────────────────────────────────
    public array $visitors      = [];
    public int   $visitorPage   = 1;
    public bool  $hasMoreVisitors = true;

    private const PER_PAGE = 25;

    public function mount(): void
    {
        $this->fetchVisitors();
    }

    public function loadMoreVisitors(): void
    {
        if (! $this->hasMoreVisitors) {
            return;
        }

        $this->visitorPage++;
        $this->fetchVisitors();
    }

    private function fetchVisitors(): void
    {
        // Show every visitor — humans AND bots — each marked with why it was
        // classified that way.
        $rows = VisitorLog::latest()
            ->offset(($this->visitorPage - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE + 1)   // +1 to peek whether more exist
            ->get();

        $this->hasMoreVisitors = $rows->count() > self::PER_PAGE;

        $new = $rows->take(self::PER_PAGE)->map(function ($r) {
            $sec = (int) $r->session_duration;

            return [
                'ip'             => preg_replace('/\.\d+$/', '.xxx', $r->ip_address ?? ''),
                'country'        => $r->country,
                'city'           => $r->city,
                'browser'        => $r->browser,
                'os'             => $r->os,
                'page'           => $r->page,
                'duration'       => $sec > 0 ? intdiv($sec, 60) . 'm ' . ($sec % 60) . 's' : '–',
                'time'           => $r->created_at->diffForHumans(),
                'date'           => $r->created_at->format('M j, Y'),
                'is_bot'         => (bool) $r->is_bot,
                'classification' => $this->classify($r),
            ];
        })->all();

        $this->visitors = array_merge($this->visitors, $new);
    }

    /**
     * Human-readable explanation of why a visitor was classified as bot/human,
     * shown in the collapsible detail row.
     *
     * @return array{summary: string, signals: array<int, string>}
     */
    private function classify(VisitorLog $r): array
    {
        if ($r->is_bot) {
            return match ($r->bot_reason) {
                'empty-user-agent' => [
                    'summary' => 'Bot — no browser identity',
                    'signals' => [
                        'The request sent no User-Agent header at all.',
                        'Real browsers always identify themselves; empty agents are scripts/scanners (e.g. Shodan).',
                        'Excluded from all human metrics on this page.',
                    ],
                ],
                'http-client' => [
                    'summary' => 'Bot — scripted HTTP client',
                    'signals' => [
                        'The User-Agent is a programmatic HTTP client, not an interactive browser.',
                        'Examples: curl, wget, python-requests, Go-http-client, headless Chrome, Postman.',
                        'Excluded from all human metrics on this page.',
                    ],
                ],
                default => [
                    'summary' => 'Bot — known crawler signature',
                    'signals' => [
                        'The User-Agent matches a known crawler/bot signature.',
                        'Examples: Googlebot, bingbot, AhrefsBot, GPTBot, facebookexternalhit.',
                        'Excluded from all human metrics on this page.',
                    ],
                ],
            };
        }

        $signals = [];
        $signals[] = $r->browser
            ? "Sent a real browser User-Agent ({$r->browser}" . ($r->os ? " on {$r->os}" : '') . ').'
            : 'Sent a normal browser User-Agent.';
        $signals[] = 'No crawler/bot signature and not a scripted HTTP client.';
        if ($r->device) {
            $signals[] = "Device detected: {$r->device}.";
        }
        if ((int) $r->session_duration > 0) {
            $sec = (int) $r->session_duration;
            $signals[] = 'Recorded active time on page: ' . intdiv($sec, 60) . 'm ' . ($sec % 60) . 's (engagement signal).';
        }

        return [
            'summary' => 'Human — passed all bot checks',
            'signals' => $signals,
        ];
    }

    // ── Period-filtered computed properties ──────────────────────────────

    public function setPeriod(string $period): void
    {
        $this->period    = $period;
        $this->startDate = null;
        $this->endDate   = null;
    }

    /** Apply a custom from/to date range. */
    public function applyRange(): void
    {
        $this->validate([
            'startDate' => ['required', 'date', 'before_or_equal:today'],
            'endDate'   => ['required', 'date', 'before_or_equal:today', 'after_or_equal:startDate'],
        ], [
            'endDate.after_or_equal' => 'The end date must be on or after the start date.',
        ]);

        $this->period = 'custom';
    }

    public function getStatsProperty(): array
    {
        return VisitorLog::stats($this->period, $this->startDate, $this->endDate);
    }

    public function getCountriesProperty(): array
    {
        return VisitorLog::topCountries($this->period, $this->startDate, $this->endDate);
    }

    public function getDevicesProperty(): array
    {
        return VisitorLog::deviceBreakdown($this->period, $this->startDate, $this->endDate);
    }

    public function getBrowsersProperty(): array
    {
        return VisitorLog::browserBreakdown($this->period, $this->startDate, $this->endDate);
    }

    public function getTopPagesProperty(): array
    {
        return VisitorLog::topPages($this->period, $this->startDate, $this->endDate);
    }

    public function getMapDataProperty(): array
    {
        return VisitorLog::mapData();
    }

    public function getTotalVisitorCountProperty(): int
    {
        return VisitorLog::humans()->distinct('ip_address')->count('ip_address');
    }

    public function render()
    {
        return view('livewire.admin.visitor-analytics')->layout('layouts.admin', ['title' => 'Visitor Analytics']);
    }
}
