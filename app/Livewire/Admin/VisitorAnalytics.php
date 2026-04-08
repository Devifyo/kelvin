<?php

namespace App\Livewire\Admin;

use App\Models\VisitorLog;
use Livewire\Component;

class VisitorAnalytics extends Component
{
    public string $period = 'today';

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
        $rows = VisitorLog::latest()
            ->offset(($this->visitorPage - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE + 1)   // +1 to peek whether more exist
            ->get();

        $this->hasMoreVisitors = $rows->count() > self::PER_PAGE;

        $new = $rows->take(self::PER_PAGE)->map(function ($r) {
            $sec = (int) $r->session_duration;

            return [
                'ip'       => preg_replace('/\.\d+$/', '.xxx', $r->ip_address ?? ''),
                'country'  => $r->country,
                'city'     => $r->city,
                'browser'  => $r->browser,
                'os'       => $r->os,
                'page'     => $r->page,
                'duration' => $sec > 0 ? intdiv($sec, 60) . 'm ' . ($sec % 60) . 's' : '–',
                'time'     => $r->created_at->diffForHumans(),
                'date'     => $r->created_at->format('M j, Y'),
            ];
        })->all();

        $this->visitors = array_merge($this->visitors, $new);
    }

    // ── Period-filtered computed properties ──────────────────────────────

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getStatsProperty(): array
    {
        return VisitorLog::stats($this->period);
    }

    public function getCountriesProperty(): array
    {
        return VisitorLog::topCountries($this->period);
    }

    public function getDevicesProperty(): array
    {
        return VisitorLog::deviceBreakdown($this->period);
    }

    public function getBrowsersProperty(): array
    {
        return VisitorLog::browserBreakdown($this->period);
    }

    public function getTopPagesProperty(): array
    {
        return VisitorLog::topPages($this->period);
    }

    public function getMapDataProperty(): array
    {
        return VisitorLog::mapData();
    }

    public function getTotalVisitorCountProperty(): int
    {
        return VisitorLog::distinct('ip_address')->count('ip_address');
    }

    public function render()
    {
        return view('livewire.admin.visitor-analytics')->layout('layouts.admin', ['title' => 'Visitor Analytics']);
    }
}
