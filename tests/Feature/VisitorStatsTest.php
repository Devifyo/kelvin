<?php

namespace Tests\Feature;

use App\Models\VisitorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorStatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Build the canonical "today" scenario:
     *  - Visitor A: session s1, two page views (durations 30s + 0s)
     *  - Visitor B: session s2, one page view  (duration 90s) — most engaged
     *  - Two bot hits (should be excluded from every human metric)
     */
    private function seedToday(): void
    {
        // Visitor A — session s1
        VisitorLog::create([
            'ip_address' => '1.1.1.1', 'visitor_id' => 'aaaaaaaa', 'session_id' => 's1',
            'country' => 'India', 'city' => 'Delhi', 'browser' => 'Chrome', 'device' => 'Desktop',
            'page' => '/', 'session_duration' => 30, 'is_new_visitor' => true, 'is_bot' => false,
        ]);
        VisitorLog::create([
            'ip_address' => '1.1.1.1', 'visitor_id' => 'aaaaaaaa', 'session_id' => 's1',
            'country' => 'India', 'city' => 'Delhi', 'browser' => 'Chrome', 'device' => 'Desktop',
            'page' => '/about', 'session_duration' => 0, 'is_new_visitor' => false, 'is_bot' => false,
        ]);

        // Visitor B — session s2 (the longest session)
        VisitorLog::create([
            'ip_address' => '2.2.2.2', 'visitor_id' => 'bbbbbbbb', 'session_id' => 's2',
            'country' => 'France', 'city' => 'Paris', 'browser' => 'Safari', 'device' => 'Mobile',
            'page' => '/', 'session_duration' => 90, 'is_new_visitor' => true, 'is_bot' => false,
        ]);

        // Two bots
        VisitorLog::create([
            'ip_address' => '9.9.9.9', 'page' => '/', 'device' => 'Bot',
            'session_duration' => 0, 'is_bot' => true, 'bot_reason' => 'crawler-signature',
        ]);
        VisitorLog::create([
            'ip_address' => '9.9.9.8', 'page' => '/', 'device' => 'Bot',
            'session_duration' => 0, 'is_bot' => true, 'bot_reason' => 'empty-user-agent',
        ]);
    }

    public function test_human_metrics_exclude_bots(): void
    {
        $this->seedToday();
        $stats = VisitorLog::stats('today');

        $this->assertEquals(3, $stats['pageviews'], 'Only human page views are counted');
        $this->assertEquals(2, $stats['visitors'], 'Two distinct human visitors');
        $this->assertEquals(2, $stats['sessions'], 'Two distinct sessions');
    }

    public function test_pages_per_session(): void
    {
        $this->seedToday();
        $stats = VisitorLog::stats('today');

        // 3 human page views / 2 sessions = 1.5
        $this->assertEquals('1.5', $stats['pages_per_session']);
    }

    public function test_avg_and_longest_session(): void
    {
        $this->seedToday();
        $stats = VisitorLog::stats('today');

        // session totals: s1 = 30, s2 = 90 → avg 60s, max 90s
        $this->assertEquals('1m 0s', $stats['avg_session']);
        $this->assertEquals('1m 30s', $stats['max_session']);
        $this->assertEquals(90, $stats['max_session_sec']);
        $this->assertEquals('Paris, France', $stats['max_session_from']);
    }

    public function test_human_vs_bot_split(): void
    {
        $this->seedToday();
        $stats = VisitorLog::stats('today');

        $this->assertEquals(3, $stats['human_pageviews']);
        $this->assertEquals(2, $stats['bot_pageviews']);
        // 2 bots / (3 humans + 2 bots) = 40%
        $this->assertEquals(40, $stats['bot_pct']);
    }

    public function test_returning_visitor_is_detected_from_prior_activity(): void
    {
        $this->seedToday();

        // Visitor A was also seen yesterday → returning today.
        // (created_at isn't fillable, so force it after creation.)
        $prior = VisitorLog::create([
            'ip_address' => '1.1.1.1', 'visitor_id' => 'aaaaaaaa', 'session_id' => 's0',
            'browser' => 'Chrome', 'device' => 'Desktop', 'page' => '/', 'session_duration' => 0,
            'is_bot' => false,
        ]);
        $prior->forceFill(['created_at' => now()->subDay()])->save();

        $stats = VisitorLog::stats('today');

        $this->assertEquals(1, $stats['returning_visitors'], 'Visitor A returned from a prior day');
    }

    public function test_empty_database_does_not_error(): void
    {
        $stats = VisitorLog::stats('today');

        $this->assertEquals(0, $stats['pageviews']);
        $this->assertEquals(0, $stats['visitors']);
        $this->assertEquals('0m 0s', $stats['avg_session']);
        $this->assertEquals(0, $stats['bot_pct']);
        $this->assertEquals('0.0', $stats['pages_per_session']);
    }

    public function test_custom_date_range_only_includes_rows_within_bounds(): void
    {
        $inside = VisitorLog::create([
            'ip_address' => '1.1.1.1', 'session_id' => 'in', 'browser' => 'Chrome',
            'page' => '/', 'session_duration' => 0, 'is_bot' => false,
        ]);
        $inside->forceFill(['created_at' => now()->subDays(3)])->save();

        $outside = VisitorLog::create([
            'ip_address' => '2.2.2.2', 'session_id' => 'out', 'browser' => 'Chrome',
            'page' => '/', 'session_duration' => 0, 'is_bot' => false,
        ]);
        $outside->forceFill(['created_at' => now()->subDays(20)])->save();

        // Range covering only the last 5 days → only the "inside" row counts.
        $stats = VisitorLog::stats(
            'custom',
            now()->subDays(5)->toDateString(),
            now()->toDateString(),
        );

        $this->assertEquals(1, $stats['pageviews']);
        $this->assertEquals(1, $stats['visitors']);
    }
}
