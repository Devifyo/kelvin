<?php

namespace Tests\Feature;

use App\Livewire\Admin\VisitorAnalytics;
use App\Models\VisitorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VisitorAnalyticsComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_visitors_lists_both_humans_and_bots_with_reasons(): void
    {
        VisitorLog::create([
            'ip_address' => '1.1.1.1', 'browser' => 'Chrome', 'os' => 'Windows',
            'device' => 'Desktop', 'page' => '/', 'session_duration' => 45, 'is_bot' => false,
        ]);
        VisitorLog::create([
            'ip_address' => '9.9.9.9', 'device' => 'Bot', 'page' => '/',
            'session_duration' => 0, 'is_bot' => true, 'bot_reason' => 'crawler-signature',
        ]);
        VisitorLog::create([
            'ip_address' => '9.9.9.8', 'device' => 'Bot', 'page' => '/',
            'session_duration' => 0, 'is_bot' => true, 'bot_reason' => 'empty-user-agent',
        ]);

        Livewire::test(VisitorAnalytics::class)
            ->assertSee('Human')
            ->assertSee('Bot')
            ->assertSee('Human — passed all bot checks')
            ->assertSee('known crawler signature')
            ->assertSee('no browser identity');
    }

    public function test_apply_range_activates_custom_period(): void
    {
        Livewire::test(VisitorAnalytics::class)
            ->set('startDate', now()->subDays(3)->toDateString())
            ->set('endDate', now()->toDateString())
            ->call('applyRange')
            ->assertHasNoErrors()
            ->assertSet('period', 'custom');
    }

    public function test_apply_range_rejects_end_before_start(): void
    {
        Livewire::test(VisitorAnalytics::class)
            ->set('startDate', now()->toDateString())
            ->set('endDate', now()->subDays(3)->toDateString())
            ->call('applyRange')
            ->assertHasErrors('endDate')
            ->assertSet('period', 'today');
    }

    public function test_choosing_a_preset_clears_the_custom_range(): void
    {
        Livewire::test(VisitorAnalytics::class)
            ->set('startDate', now()->subDays(3)->toDateString())
            ->set('endDate', now()->toDateString())
            ->call('applyRange')
            ->call('setPeriod', 'week')
            ->assertSet('period', 'week')
            ->assertSet('startDate', null)
            ->assertSet('endDate', null);
    }
}
