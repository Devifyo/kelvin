<?php

namespace Tests\Feature;

use App\Livewire\Admin\WelcomePageSettings;
use Database\Seeders\WelcomePageContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WelcomePageFaqReorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_items_load_with_stable_ids(): void
    {
        $this->seed(WelcomePageContentSeeder::class);

        $items = Livewire::test(WelcomePageSettings::class)->get('faq_items');

        $this->assertCount(8, $items);
        $ids = array_column($items, '_id');
        $this->assertCount(8, array_filter($ids), 'every item has an _id');
        $this->assertCount(8, array_unique($ids), 'ids are unique');
    }

    public function test_reorder_faq_items_applies_new_order(): void
    {
        $this->seed(WelcomePageContentSeeder::class);

        $component = Livewire::test(WelcomePageSettings::class);
        $ids       = array_column($component->get('faq_items'), '_id');

        $reversed = array_reverse($ids);
        $component->call('reorderFaqItems', $reversed);

        $newIds = array_column($component->get('faq_items'), '_id');
        $this->assertEquals($reversed, $newIds, 'items follow the dragged order');
    }

    public function test_reorder_ignores_unknown_ids(): void
    {
        $this->seed(WelcomePageContentSeeder::class);

        $component = Livewire::test(WelcomePageSettings::class);
        $ids       = array_column($component->get('faq_items'), '_id');

        // Drop one + inject a bogus id — result should contain only the known 7.
        $partial = array_slice($ids, 1);
        $partial[] = 'does-not-exist';
        $component->call('reorderFaqItems', $partial);

        $this->assertCount(7, $component->get('faq_items'));
    }
}
