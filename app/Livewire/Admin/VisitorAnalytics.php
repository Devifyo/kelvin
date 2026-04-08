<?php

namespace App\Livewire\Admin;

use App\Models\VisitorLog;
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

    public function getRecentVisitorsProperty(): array
    {
        return VisitorLog::recentVisitors(10);
    }

    public function render()
    {
        return view('livewire.admin.visitor-analytics')->layout('layouts.admin', ['title' => 'Visitor Analytics']);
    }
}
