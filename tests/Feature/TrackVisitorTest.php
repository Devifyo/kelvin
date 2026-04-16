<?php

namespace Tests\Feature;

use App\Http\Middleware\TrackVisitor;
use App\Models\VisitorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TrackVisitorTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ──────────────────────────────────────────────────────────

    private function makeRequest(
        string $path   = '/',
        string $method = 'GET',
        string $ip     = '127.0.0.1',
        string $ua     = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
    ): Request {
        $request = Request::create($path, $method);
        $request->server->set('REMOTE_ADDR', $ip);
        $request->headers->set('User-Agent', $ua);
        return $request;
    }

    private function ok(): Response
    {
        return new Response('OK', 200);
    }

    private function track(Request $request, ?Response $response = null): void
    {
        (new TrackVisitor())->terminate($request, $response ?? $this->ok());
    }

    // ── 1. Basic tracking ────────────────────────────────────────────────

    public function test_records_a_new_pageview_for_get_200(): void
    {
        $this->track($this->makeRequest('/'));

        $this->assertDatabaseCount('visitor_logs', 1);
        $this->assertDatabaseHas('visitor_logs', ['page' => '/']);
    }

    public function test_stores_correct_page_path(): void
    {
        $this->track($this->makeRequest('/agile-consulting-services'));

        $this->assertDatabaseHas('visitor_logs', ['page' => '/agile-consulting-services']);
    }

    // ── 2. Filtering — should NOT track ─────────────────────────────────

    public function test_ignores_post_requests(): void
    {
        $this->track($this->makeRequest('/', 'POST'));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_put_requests(): void
    {
        $this->track($this->makeRequest('/', 'PUT'));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_non_200_responses(): void
    {
        $this->track($this->makeRequest('/'), new Response('Not Found', 404));
        $this->track($this->makeRequest('/'), new Response('Moved', 301));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_admin_routes(): void
    {
        $this->track($this->makeRequest('/admin'));
        $this->track($this->makeRequest('/admin/dashboard'));
        $this->track($this->makeRequest('/admin/visitors'));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_debugbar_routes(): void
    {
        $this->track($this->makeRequest('/_debugbar/assets/stylesheets'));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_health_check_route(): void
    {
        $this->track($this->makeRequest('/up'));

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_ignores_known_bots(): void
    {
        $bots = [
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
            'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
            'Twitterbot/1.0',
        ];

        foreach ($bots as $ua) {
            $this->track($this->makeRequest('/', 'GET', '1.2.3.4', $ua));
        }

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    // ── 3. Deduplication ─────────────────────────────────────────────────

    public function test_same_ip_and_page_within_cooldown_is_counted_once(): void
    {
        $request = $this->makeRequest('/about-kevin-thompson', 'GET', '1.2.3.4');

        $this->track($request);
        $this->track($request); // immediate repeat — should be deduped

        $this->assertDatabaseCount('visitor_logs', 1);
    }

    public function test_same_ip_different_pages_are_both_counted(): void
    {
        $this->track($this->makeRequest('/about-kevin-thompson', 'GET', '1.2.3.4'));
        $this->track($this->makeRequest('/agile-consulting-services', 'GET', '1.2.3.4'));

        $this->assertDatabaseCount('visitor_logs', 2);
    }

    public function test_same_page_after_cooldown_expires_is_counted_again(): void
    {
        $request = $this->makeRequest('/about-kevin-thompson', 'GET', '5.6.7.8');

        $this->track($request);

        // Simulate cooldown expired by flushing only the pv_ key
        $pvKey = 'pv_' . md5('5.6.7.8/about-kevin-thompson');
        Cache::forget($pvKey);

        $this->track($request);

        $this->assertDatabaseCount('visitor_logs', 2);
    }

    public function test_different_ips_on_same_page_are_counted_separately(): void
    {
        $this->track($this->makeRequest('/', 'GET', '1.1.1.1'));
        $this->track($this->makeRequest('/', 'GET', '2.2.2.2'));

        $this->assertDatabaseCount('visitor_logs', 2);
    }

    // ── 4. Session duration stamping ──────────────────────────────────────

    public function test_stamping_duration_when_visitor_navigates_to_next_page(): void
    {
        $ip = '10.0.0.5'; // local IP avoids geo HTTP call

        // Page A recorded
        $this->track($this->makeRequest('/about-kevin-thompson', 'GET', $ip));
        $pageA = VisitorLog::first();
        $this->assertEquals(0, $pageA->session_duration);

        // Simulate 45 seconds passing by backdating the cache entry
        $lastPvKey = 'last_pv_' . md5($ip);
        Cache::put($lastPvKey, ['id' => $pageA->id, 'time' => now()->timestamp - 45], TrackVisitor::SESSION_TIMEOUT);

        // Page B request arrives — should stamp Page A's duration
        $this->track($this->makeRequest('/agile-consulting-services', 'GET', $ip));

        $this->assertEquals(45, $pageA->fresh()->session_duration);
    }

    public function test_duration_stays_zero_when_gap_exceeds_session_timeout(): void
    {
        $ip = '10.0.0.6';

        $this->track($this->makeRequest('/about-kevin-thompson', 'GET', $ip));
        $pageA = VisitorLog::first();

        // Backdate cache entry beyond SESSION_TIMEOUT (31 minutes ago)
        $lastPvKey = 'last_pv_' . md5($ip);
        Cache::put($lastPvKey, ['id' => $pageA->id, 'time' => now()->timestamp - (31 * 60)], TrackVisitor::SESSION_TIMEOUT);

        $this->track($this->makeRequest('/agile-consulting-services', 'GET', $ip));

        $this->assertEquals(0, $pageA->fresh()->session_duration, 'Duration must stay 0 when gap > 30 min');
    }

    public function test_duration_is_stamped_exactly_at_session_timeout_boundary(): void
    {
        $ip = '10.0.0.7';

        $this->track($this->makeRequest('/about-kevin-thompson', 'GET', $ip));
        $pageA = VisitorLog::first();

        $lastPvKey = 'last_pv_' . md5($ip);
        Cache::put($lastPvKey, ['id' => $pageA->id, 'time' => now()->timestamp - 1800], TrackVisitor::SESSION_TIMEOUT);

        $this->track($this->makeRequest('/agile-consulting-services', 'GET', $ip));

        $this->assertEquals(1800, $pageA->fresh()->session_duration, 'Exactly 30 min should be stamped');
    }

    // ── 5. New vs returning visitor ───────────────────────────────────────

    public function test_first_ever_ip_is_marked_as_new_visitor(): void
    {
        $this->track($this->makeRequest('/', 'GET', '10.0.1.1'));

        $this->assertDatabaseHas('visitor_logs', [
            'ip_address'   => '10.0.1.1',
            'is_new_visitor' => 1,
        ]);
    }

    public function test_same_ip_on_second_page_is_not_new_visitor(): void
    {
        $ip = '10.0.1.2';

        $this->track($this->makeRequest('/', 'GET', $ip));
        // Clear pv dedup so second request is recorded, but leave visitor_seen_ cache intact
        Cache::forget('pv_' . md5($ip . '/'));

        $this->track($this->makeRequest('/', 'GET', $ip)); // still within VISITOR_WINDOW

        $logs = VisitorLog::where('ip_address', $ip)->get();
        $this->assertCount(2, $logs);
        $this->assertTrue((bool) $logs->first()->is_new_visitor, 'First visit should be new');
        $this->assertFalse((bool) $logs->last()->is_new_visitor, 'Second visit same window should not be new');
    }

    public function test_ip_returning_after_visitor_window_expires_is_not_new(): void
    {
        $ip = '10.0.1.3';

        $this->track($this->makeRequest('/', 'GET', $ip));

        // Simulate VISITOR_WINDOW expiry but IP already exists in DB
        Cache::forget('visitor_seen_' . md5($ip));

        // Clear pv dedup too so second request is tracked
        Cache::forget('pv_' . md5($ip . '/'));

        $this->track($this->makeRequest('/', 'GET', $ip));

        $secondLog = VisitorLog::where('ip_address', $ip)->latest()->first();
        $this->assertFalse((bool) $secondLog->is_new_visitor, 'IP already in DB — not a new visitor');
    }

    // ── 6. Private / local IP geo handling ───────────────────────────────

    public function test_loopback_ip_is_recorded_as_local_country(): void
    {
        $this->track($this->makeRequest('/', 'GET', '127.0.0.1'));

        $this->assertDatabaseHas('visitor_logs', ['country' => 'Local']);
    }

    public function test_ipv6_loopback_is_recorded_as_local(): void
    {
        $request = Request::create('/');
        $request->server->set('REMOTE_ADDR', '::1');
        $request->headers->set('User-Agent', 'Mozilla/5.0 Chrome/120');
        $this->track($request);

        $this->assertDatabaseHas('visitor_logs', ['country' => 'Local']);
    }

    public function test_class_a_private_ip_is_local(): void
    {
        $this->track($this->makeRequest('/', 'GET', '10.0.0.1'));

        $this->assertDatabaseHas('visitor_logs', ['country' => 'Local']);
    }

    public function test_class_b_private_ip_is_local(): void
    {
        foreach (['172.16.0.1', '172.20.5.5', '172.31.255.255'] as $ip) {
            $this->track($this->makeRequest('/', 'GET', $ip));
        }

        $this->assertEquals(3, VisitorLog::where('country', 'Local')->count());
    }

    public function test_class_b_public_ip_is_NOT_treated_as_local(): void
    {
        // 172.32.x.x is NOT RFC 1918 — old bug treated it as local
        $this->track($this->makeRequest('/', 'GET', '172.32.0.1'));

        // It should NOT have country = 'Local' (geo lookup runs, returns null for test env)
        $log = VisitorLog::first();
        $this->assertNotEquals('Local', $log->country);
    }

    public function test_class_c_private_ip_is_local(): void
    {
        $this->track($this->makeRequest('/', 'GET', '192.168.1.100'));

        $this->assertDatabaseHas('visitor_logs', ['country' => 'Local']);
    }

    // ── 7. Device / browser detection ────────────────────────────────────

    public function test_detects_desktop_device(): void
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36';
        $this->track($this->makeRequest('/', 'GET', '127.0.0.1', $ua));

        $this->assertDatabaseHas('visitor_logs', ['device' => 'Desktop']);
    }

    public function test_detects_mobile_device(): void
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1';
        $this->track($this->makeRequest('/', 'GET', '127.0.0.1', $ua));

        $this->assertDatabaseHas('visitor_logs', ['device' => 'Mobile']);
    }

    public function test_detects_tablet_device(): void
    {
        $ua = 'Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1';
        $this->track($this->makeRequest('/', 'GET', '127.0.0.1', $ua));

        $this->assertDatabaseHas('visitor_logs', ['device' => 'Tablet']);
    }

    // ── 8. Edge cases ─────────────────────────────────────────────────────

    public function test_path_is_normalised_with_leading_slash(): void
    {
        // Request::create normalises path — confirm our ltrim logic handles it
        $this->track($this->makeRequest('/about-kevin-thompson'));

        $this->assertDatabaseHas('visitor_logs', ['page' => '/about-kevin-thompson']);
    }

    public function test_referrer_is_stored(): void
    {
        $request = $this->makeRequest('/', 'GET', '127.0.0.1');
        $request->headers->set('referer', 'https://google.com');

        $this->track($request);

        $this->assertDatabaseHas('visitor_logs', ['referrer' => 'https://google.com']);
    }

    public function test_session_duration_starts_at_zero(): void
    {
        $this->track($this->makeRequest('/'));

        $this->assertDatabaseHas('visitor_logs', ['session_duration' => 0]);
    }
}
