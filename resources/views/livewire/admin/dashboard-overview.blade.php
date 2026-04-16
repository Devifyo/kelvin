<div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Active Classes</h3>
                <div class="stat-number">{{ format_stat($stats['training']) }}</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Published Papers</h3>
                <div class="stat-number">{{ format_stat($stats['papers']) }}</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Inquiries</h3>
                <div class="stat-number">{{ format_stat($stats['contacts']) }}</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
        </div>
    </div>

    {{-- Visitor Snapshot Widget --}}
    <div class="vsw">
        <div class="vsw-head">
            <div class="vsw-head-left">
                <h2 class="vsw-title">Visitor Overview</h2>
                <select wire:model.live="visitorPeriod" class="vsw-period-select">
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="1month">Last 1 Month</option>
                </select>
            </div>
            <a href="{{ route('admin.visitors') }}" class="vsw-more">
                Full Analytics
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        @php
            $visitorLabel = match($visitorPeriod) {
                '7days'  => 'Visitors (7 Days)',
                '1month' => 'Visitors (Month)',
                default  => 'Visitors Today',
            };
            $visitorBarPct  = min(round($visitorStats['visitors'] / max($visitorStats['visitors'], 1) * 60), 100);
            $pageviewBarPct = $visitorStats['visitors'] > 0
                ? min(round($visitorStats['pageviews'] / $visitorStats['visitors'] / 10 * 100), 100)
                : 0;
            $sessionBarPct  = min(round($visitorStats['avg_session_sec'] / 600 * 100), 100);
        @endphp

        <div class="vsw-metrics">
            <div class="vsw-metric">
                <span class="vsw-label">{{ $visitorLabel }}</span>
                <span class="vsw-val">{{ number_format($visitorStats['visitors']) }}</span>
                <div class="vsw-bar"><div class="vsw-fill" style="width:{{ $visitorBarPct }}%"></div></div>
            </div>
            <div class="vsw-metric">
                <span class="vsw-label">Page Views</span>
                <span class="vsw-val">{{ number_format($visitorStats['pageviews']) }}</span>
                <div class="vsw-bar"><div class="vsw-fill" style="width:{{ $pageviewBarPct }}%"></div></div>
            </div>
            <div class="vsw-metric">
                <span class="vsw-label">Avg. Session</span>
                <span class="vsw-val">{{ $visitorStats['avg_session'] }}</span>
                <div class="vsw-bar"><div class="vsw-fill" style="width:{{ $sessionBarPct }}%"></div></div>
            </div>
            <div class="vsw-metric">
                <span class="vsw-label">Bounce Rate</span>
                <span class="vsw-val">{{ $visitorStats['bounce_rate'] }}</span>
                <div class="vsw-bar"><div class="vsw-fill" style="width:{{ $visitorStats['bounce_pct'] }}%"></div></div>
            </div>
        </div>

        <div class="vsw-geo">
            <p class="vsw-geo-label">Top Countries</p>
            @forelse($topCountries as $country)
                <div class="vsw-geo-row">
                    <span class="vsw-cc">{{ $country['code'] }}</span>
                    <span class="vsw-cn">{{ $country['country'] }}</span>
                    <div class="vsw-gbar"><div class="vsw-gfill" style="width:{{ $country['pct'] }}%"></div></div>
                    <span class="vsw-pct">{{ $country['pct'] }}%</span>
                </div>
            @empty
                <p class="vsw-empty">No visitor data for this period.</p>
            @endforelse
        </div>
    </div>

    <div class="inquiries-section">
        <div class="section-header">
            <h2 class="section-title">Recent Contact Inquiries</h2>
        </div>
        
        <div class="table-card">
            @if($inquiries->isNotEmpty())
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Contact</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                                <tr class="{{ $inquiry->is_read ? 'row-read' : 'row-unread' }}">
                                    <td>
                                        <div class="contact-cell">
                                            <div class="avatar">
                                                {{ Str::upper(substr($inquiry->name, 0, 1)) }}
                                            </div>
                                            <div class="contact-details">
                                                <span class="c-name">{{ $inquiry->name }}</span>
                                                <span class="c-email">{{ $inquiry->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="message-cell">
                                            <span class="m-subject">{{ Str::limit($inquiry->subject, 35) }}</span>
                                            <span class="m-excerpt">{{ Str::limit($inquiry->message, 45) }}</span>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @if($inquiry->is_read)
                                            <span class="status-pill pill-read">Read</span>
                                        @else
                                            <span class="status-pill pill-new">
                                                <span class="dot"></span> New
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="date-text">{{ $inquiry->created_at->format('M d, Y') }}</span>
                                    </td>
                                    
                                    <td class="action-cell">
                                        <button wire:click="viewMessage({{ $inquiry->id }})" class="btn-view" title="View Message">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <p>Your dashboard is clear. No recent inquiries.</p>
                </div>
            @endif
        </div>
    </div>

    @if($showModal && $selectedInquiry)
        <div class="modal-overlay" wire:click.self="closeModal">
            <div class="modal-card">
                <div class="modal-header">
                    <h3 class="modal-title">Message Details</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <div class="modal-body">
                    <div class="msg-meta">
                        <div><strong>From:</strong> {{ $selectedInquiry->name }} ({{ $selectedInquiry->email }})</div>
                        <div><strong>Date:</strong> {{ $selectedInquiry->created_at->format('F j, Y, g:i a') }}</div>
                        <div><strong>Subject:</strong> {{ $selectedInquiry->subject }}</div>
                    </div>
                    
                    <div class="msg-content">
                        {!! nl2br(e($selectedInquiry->message)) !!} 
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-modal-close">Close</button>
                    <a href="mailto:{{ $selectedInquiry->email }}" class="btn-modal-reply">Reply via Email</a>
                </div>
            </div>
        </div>
    @endif
</div>