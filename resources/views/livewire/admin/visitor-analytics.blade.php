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
                <div class="va-stat-label">
                    Total Visitors
                    <span class="va-hint" data-tip="Unique visitors counted by IP address. The same IP is counted once every 12 hours.">?</span>
                </div>
                <div class="va-stat-value">{{ number_format($this->stats['visitors']) }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-copper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">
                    Page Views
                    <span class="va-hint" data-tip="Total individual page loads. The same page is re-counted after 1 minute.">?</span>
                </div>
                <div class="va-stat-value">{{ number_format($this->stats['pageviews']) }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">
                    Avg. Session
                    <span class="va-hint" data-tip="Average total time a visitor spends across all pages in one session, estimated from server-side request timing.">?</span>
                </div>
                <div class="va-stat-value">{{ $this->stats['avg_session'] }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-muted">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">
                    Bounce Rate
                    <span class="va-hint" data-tip="Percentage of visitors who viewed only one page and left without navigating further.">?</span>
                </div>
                <div class="va-stat-value">{{ $this->stats['bounce_rate'] }}</div>
            </div>
        </div>
        <div class="va-stat-card">
            <div class="va-stat-icon va-icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            </div>
            <div class="va-stat-body">
                <div class="va-stat-label">
                    New Visitors
                    <span class="va-hint" data-tip="Visitors whose very first visit to this site occurred within the selected period.">?</span>
                </div>
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

    {{-- World Map --}}
    <div class="va-card va-map-card">
        <div class="va-card-header">
            <h3 class="va-card-title">Visitor World Map</h3>
            <span class="va-map-legend">
                <span class="va-legend-dot" style="background:#edb97a"></span> Low
                <span class="va-legend-dot" style="background:#d4924e"></span> Medium
                <span class="va-legend-dot" style="background:#b5722a"></span> High
                <span class="va-legend-dot" style="background:#7a3e0a"></span> Very High
            </span>
        </div>
        <div wire:ignore style="height:440px;">
            <div id="va-world-map" style="height:440px;"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        if (window._vaMapReady) return;
        window._vaMapReady = true;

        const mapData = @json($this->mapData);

        // Wait until the container has a real height before initialising.
        // This fixes the "half-loaded tiles" issue caused by Livewire rendering
        // the component before the browser has laid out the full page height.
        function boot() {
            const el = document.getElementById('va-world-map');
            if (!el || el.offsetHeight === 0) {
                setTimeout(boot, 50);
                return;
            }
            initMap(el);
        }

        function initMap(el) {
            const map = L.map(el, {
                center: [25, 10],
                zoom: 2,
                minZoom: 1,
                maxZoom: 10,
                zoomControl: true,
                scrollWheelZoom: false,
            });

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Force Leaflet to recalculate the container size after full render
            setTimeout(function () { map.invalidateSize(); }, 200);

            // ── Colour scale (copper/bronze matching site theme) ──────────
            function visitorColor(pct) {
                if (pct >= 0.6) return '#7a3e0a';
                if (pct >= 0.3) return '#b5722a';
                if (pct >= 0.1) return '#d4924e';
                return '#edb97a';
            }

            // ── Logarithmic radius scaling ────────────────────────────────
            function markerRadius(count, maxCount, minR, maxR) {
                if (maxCount <= 1) return minR;
                return minR + (Math.log(count + 1) / Math.log(maxCount + 1)) * (maxR - minR);
            }

            // ── Country bubbles ───────────────────────────────────────────
            const maxCountry = Math.max(...(mapData.countries.length ? mapData.countries.map(c => c.visitors) : [1]));

            mapData.countries.forEach(function (c) {
                if (!c.lat || !c.lon) return;
                const pct    = c.visitors / maxCountry;
                const radius = markerRadius(c.visitors, maxCountry, 7, 44);
                const color  = visitorColor(pct);

                // Build city list before the chained call
                const countryCities = mapData.cities
                    .filter(function (city) { return city.country === c.name; })
                    .slice(0, 5);

                const citiesHtml = countryCities.map(function (city) {
                    return '<div class="va-map-tip-city">' +
                        '<span class="va-map-tip-city-name">' + city.city + '</span>' +
                        '<span class="va-map-tip-city-visits">' + city.visitors.toLocaleString() + '</span>' +
                    '</div>';
                }).join('');

                L.circleMarker([c.lat, c.lon], {
                    radius:      radius,
                    fillColor:   color,
                    fillOpacity: 0.80,
                    color:       '#ffffff',
                    weight:      1.5,
                    opacity:     0.9,
                })
                .bindTooltip(
                    '<div class="va-map-tip">' +
                        '<strong>' + c.name + '</strong>' +
                        '<span>' + c.visitors.toLocaleString() + ' visitor' + (c.visitors !== 1 ? 's' : '') + '</span>' +
                        (citiesHtml ? '<div class="va-map-tip-cities">' + citiesHtml + '</div>' : '') +
                    '</div>',
                    { direction: 'top', offset: [0, -4], className: 'va-leaflet-tip' }
                )
                .addTo(map);
            });

            // ── City dots ─────────────────────────────────────────────────
            const maxCity = Math.max(...(mapData.cities.length ? mapData.cities.map(c => c.visitors) : [1]));

            mapData.cities.forEach(function (c) {
                if (!c.lat || !c.lon) return;
                const radius = markerRadius(c.visitors, maxCity, 3, 10);

                L.circleMarker([c.lat, c.lon], {
                    radius:      radius,
                    fillColor:   '#f5e6cc',
                    fillOpacity: 0.55,
                    color:       '#c08040',
                    weight:      1,
                    opacity:     0.6,
                })
                .bindTooltip(
                    '<div class="va-map-tip">' +
                        '<strong>' + c.city + '</strong>' +
                        '<span>' + c.country + ' — ' + c.visitors.toLocaleString() + ' visitor' + (c.visitors !== 1 ? 's' : '') + '</span>' +
                    '</div>',
                    { direction: 'top', offset: [0, -2], className: 'va-leaflet-tip' }
                )
                .addTo(map);
            });
        }

        boot();
    })();
    </script>

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

    {{-- All Visitors (infinite scroll) --}}
    <div class="va-card">
        <div class="va-card-header">
            <h3 class="va-card-title">All Visitors</h3>
            <span class="va-visitors-total">{{ number_format($this->totalVisitorCount) }} total</span>
        </div>

        <div
            class="va-visitors-scroll"
            x-data="{ loading: false, hasMore: $wire.entangle('hasMoreVisitors') }"
            @scroll.passive="
                if (loading || !hasMore) return;
                const el = $event.target;
                if (el.scrollTop + el.clientHeight >= el.scrollHeight - 200) {
                    loading = true;
                    $wire.loadMoreVisitors().then(() => loading = false);
                }
            "
        >
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
                    @foreach($visitors as $i => $v)
                        <tr wire:key="vrow-{{ $i }}">
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
                            <td>
                                <div class="va-location">
                                    <span class="va-city">{{ $v['time'] }}</span>
                                    <span class="va-ctry">{{ $v['date'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($hasMoreVisitors)
                <div class="va-visitors-loader">
                    <span class="va-spinner"></span>
                    <span>Loading more visitors…</span>
                </div>
            @else
                <div class="va-visitors-end">All visitors loaded</div>
            @endif
        </div>
    </div>

    <link href="{{ asset('css/admin/visitor-analytics.css') }}" rel="stylesheet">
</div>
