<div class="va-page">

    {{-- Period Tabs --}}
    <div class="va-period-bar">
        <button wire:click="setPeriod('today')" class="va-period-btn {{ $period === 'today' ? 'active' : '' }}">Today</button>
        <button wire:click="setPeriod('week')"  class="va-period-btn {{ $period === 'week'  ? 'active' : '' }}">Last 7 Days</button>
        <button wire:click="setPeriod('month')" class="va-period-btn {{ $period === 'month' ? 'active' : '' }}">Last 30 Days</button>
    </div>

    {{-- Top Stats --}}
    <div class="va-stats-grid">
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">Total Visitors</div>
                <div class="va-stat-value">{{ number_format($this->stats['visitors']) }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-copper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">Page Views</div>
                <div class="va-stat-value">{{ number_format($this->stats['pageviews']) }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">Avg. Session</div>
                <div class="va-stat-value">{{ $this->stats['avg_session'] }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-muted">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">Bounce Rate</div>
                <div class="va-stat-value">{{ $this->stats['bounce_rate'] }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">New Visitors</div>
                <div class="va-stat-value">{{ number_format($this->stats['new_visitors']) }}</div>
            </div>
        </div>
    </div>

    {{-- Middle Row: Countries + Devices + Browsers --}}
    <div class="va-mid-grid">

        {{-- Countries --}}
        <div class="va-card va-card-countries">
            <div class="va-card-header">
                <h3 class="va-card-title">Top Countries</h3>
            </div>
            <div class="va-card-body">
                @foreach($this->countries as $row)
                    <div class="va-country-row">
                        <div class="va-country-flag">{{ $row['code'] }}</div>
                        <div class="va-country-info">
                            <div class="va-country-name">{{ $row['country'] }}</div>
                            <div class="va-bar-wrap">
                                <div class="va-bar" style="width: {{ $row['pct'] }}%"></div>
                            </div>
                        </div>
                        <div class="va-country-nums">
                            <span class="va-num">{{ number_format($row['visitors']) }}</span>
                            <span class="va-pct">{{ $row['pct'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Devices --}}
        <div class="va-card va-card-small">
            <div class="va-card-header">
                <h3 class="va-card-title">Devices</h3>
            </div>
            <div class="va-card-body">
                @foreach($this->devices as $d)
                    <div class="va-device-row">
                        <div class="va-device-label">{{ $d['label'] }}</div>
                        <div class="va-bar-wrap" style="flex:1; margin: 0 0.75rem;">
                            <div class="va-bar" style="width: {{ $d['pct'] }}%; background: {{ $d['color'] }};"></div>
                        </div>
                        <div class="va-device-pct">{{ $d['pct'] }}%</div>
                    </div>
                @endforeach

                <div class="va-donut-wrap">
                    <svg viewBox="0 0 36 36" class="va-donut">
                        <circle class="va-donut-hole" cx="18" cy="18" r="14"/>
                        <circle class="va-donut-ring"  cx="18" cy="18" r="14"/>
                        @php $donutOffset = 25; @endphp
                        @foreach($this->devices as $d)
                            <circle class="va-donut-seg" cx="18" cy="18" r="14"
                                stroke="{{ $d['color'] }}"
                                stroke-dasharray="{{ $d['pct'] }} {{ 100 - $d['pct'] }}"
                                stroke-dashoffset="{{ $donutOffset }}"/>
                            @php $donutOffset -= $d['pct']; @endphp
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>

        {{-- Browsers --}}
        <div class="va-card va-card-small">
            <div class="va-card-header">
                <h3 class="va-card-title">Browsers</h3>
            </div>
            <div class="va-card-body">
                @foreach($this->browsers as $b)
                    <div class="va-browser-row">
                        <div class="va-browser-icon">{{ $b['icon'] }}</div>
                        <div class="va-browser-name">{{ $b['label'] }}</div>
                        <div class="va-bar-wrap" style="flex:1; margin: 0 0.75rem;">
                            <div class="va-bar" style="width: {{ $b['pct'] }}%;"></div>
                        </div>
                        <div class="va-browser-pct">{{ $b['pct'] }}%</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Top Pages --}}
    <div class="va-card" style="margin-bottom: 2rem;">
        <div class="va-card-header">
            <h3 class="va-card-title">Top Pages</h3>
        </div>
        <div class="va-card-body" style="padding: 0;">
            <table class="va-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Path</th>
                        <th>Views</th>
                        <th>Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->topPages as $p)
                        <tr>
                            <td><span class="va-page-label">{{ $p['label'] }}</span></td>
                            <td><code class="va-path">{{ $p['path'] }}</code></td>
                            <td><strong>{{ number_format($p['views']) }}</strong></td>
                            <td>
                                <div class="va-inline-bar-wrap">
                                    <div class="va-bar" style="width: {{ $p['pct'] }}%;"></div>
                                    <span>{{ $p['pct'] }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Visitors --}}
    <div class="va-card">
        <div class="va-card-header">
            <h3 class="va-card-title">Recent Visitors</h3>
        </div>
        <div class="va-card-body" style="padding: 0; overflow-x: auto;">
            <table class="va-table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Page</th>
                        <th>Duration</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->recentVisitors as $v)
                        <tr>
                            <td><code class="va-ip">{{ $v['ip'] }}</code></td>
                            <td>
                                <div class="va-location">
                                    <span class="va-city">{{ $v['city'] }}</span>
                                    <span class="va-ctry">{{ $v['country'] }}</span>
                                </div>
                            </td>
                            <td>{{ $v['browser'] }}</td>
                            <td>{{ $v['os'] }}</td>
                            <td><code class="va-path">{{ $v['page'] }}</code></td>
                            <td><span class="va-time">{{ $v['duration'] }}</span></td>
                            <td><span class="va-time">{{ $v['time'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link href="{{ asset('css/admin/visitor-analytics.css') }}" rel="stylesheet">
</div>
