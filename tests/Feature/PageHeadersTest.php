<?php

namespace Tests\Feature;

use App\Livewire\Admin\PageHeaders;
use App\Models\PageHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_registered_page_is_seeded_by_the_migration(): void
    {
        foreach (array_keys(PageHeader::PAGES) as $pageKey) {
            $this->assertDatabaseHas('page_headers', ['page_key' => $pageKey]);
        }
    }

    public function test_saving_updates_the_public_page_immediately(): void
    {
        Livewire::test(PageHeaders::class)
            ->call('selectPage', 'blog')
            ->set('title_regular', 'Hardware')
            ->set('title_em', 'Thinking')
            ->set('subtitle', 'Fresh copy from the admin panel.')
            ->call('save')
            ->assertHasNoErrors();

        // Cache is invalidated on save, so the front end sees it right away.
        $this->assertSame('Hardware', PageHeader::for('blog')->title_regular);

        $this->get(route('blog'))
            ->assertOk()
            ->assertSee('<h1 class="page-title">Hardware <em>Thinking</em></h1>', false)
            ->assertSee('Fresh copy from the admin panel.');
    }

    public function test_the_h1_cannot_be_emptied(): void
    {
        Livewire::test(PageHeaders::class)
            ->call('selectPage', 'papers')
            ->set('title_regular', '')
            ->call('save')
            ->assertHasErrors(['title_regular' => 'required']);

        $this->assertSame(
            PageHeader::PAGES['papers']['defaults']['title_regular'],
            PageHeader::for('papers')->title_regular
        );
    }

    public function test_every_public_page_renders_exactly_one_h1(): void
    {
        foreach (PageHeader::PAGES as $pageKey => $config) {
            $html = $this->get(route($config['route']))->assertOk()->getContent();

            $this->assertSame(
                1,
                substr_count($html, '<h1'),
                "{$pageKey} must render exactly one <h1>"
            );
        }
    }

    public function test_a_missing_row_falls_back_to_the_original_copy(): void
    {
        PageHeader::where('page_key', 'services')->delete();
        PageHeader::clearCache();

        $this->get(route('services.training'))
            ->assertOk()
            ->assertSee(PageHeader::PAGES['services']['defaults']['title_em'], false);
    }

    public function test_switching_away_with_unsaved_edits_asks_before_discarding(): void
    {
        $component = Livewire::test(PageHeaders::class)
            ->call('selectPage', 'blog')
            ->set('kicker', 'Half-finished edit')
            ->call('selectPage', 'papers');

        // The switch is refused and the view is asked to raise its own dialog —
        // no browser confirm() is involved.
        $component->assertDispatched('confirm-page-switch')
            ->assertSet('pageKey', 'blog')
            ->assertSet('kicker', 'Half-finished edit');

        // Confirming replays the call with $force = true.
        $component->call('selectPage', 'papers', true)
            ->assertSet('pageKey', 'papers')
            ->assertSet('kicker', PageHeader::PAGES['papers']['defaults']['kicker']);
    }

    public function test_switching_with_no_pending_edits_needs_no_confirmation(): void
    {
        Livewire::test(PageHeaders::class)
            ->call('selectPage', 'papers')
            ->assertNotDispatched('confirm-page-switch')
            ->assertSet('pageKey', 'papers');
    }

    public function test_restore_defaults_repopulates_the_form_without_saving(): void
    {
        $component = Livewire::test(PageHeaders::class)
            ->call('selectPage', 'about')
            ->set('kicker', 'Something Else')
            ->call('restoreDefaults');

        $component->assertSet('kicker', PageHeader::PAGES['about']['defaults']['kicker']);

        $this->assertDatabaseMissing('page_headers', [
            'page_key' => 'about',
            'kicker'   => 'Something Else',
        ]);
    }
}
