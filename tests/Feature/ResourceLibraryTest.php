<?php

namespace Tests\Feature;

use App\Livewire\Admin\ManagePapers;
use App\Models\Category;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Resource Library: the listing opens on "Featured", every resource has its own
 * page, and cards link to that page instead of the raw file.
 */
class ResourceLibraryTest extends TestCase
{
    use RefreshDatabase;

    private Category $caseStudies;
    private Category $whitePapers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->caseStudies = Category::create(['name' => 'Case Studies', 'slug' => 'case-studies', 'type' => 'paper']);
        $this->whitePapers = Category::create(['name' => 'White Papers', 'slug' => 'white-papers', 'type' => 'paper']);
    }

    private function makePaper(array $overrides = []): Paper
    {
        return Paper::create(array_merge([
            'title'        => 'Plantronics Case Study',
            'category_id'  => $this->caseStudies->id,
            'sub_category' => 'Hardware-Oriented Engagements',
            'description'  => '<p>Short summary shown on the card.</p>',
            'file_path'    => 'papers/plantronics.pdf',
            'is_active'    => true,
            'is_featured'  => false,
            'sort_order'   => 1,
        ], $overrides));
    }

    /** The rendered card heading — distinguishes a visible card from the JSON-LD ItemList, which names every resource. */
    private function cardTitle(string $title): string
    {
        return 'class="paper-title-link">' . e($title) . '</a>';
    }

    // ── Listing ────────────────────────────────────────────────────────────

    public function test_listing_opens_on_the_featured_view_and_shows_only_featured_resources(): void
    {
        $featured = $this->makePaper(['title' => 'Featured Paper', 'is_featured' => true]);
        $other    = $this->makePaper(['title' => 'Ordinary Paper', 'sort_order' => 2]);

        $html = $this->get(route('papers'))->assertOk()->getContent();

        $this->assertStringContainsString($this->cardTitle('Featured Paper'), $html);
        $this->assertStringNotContainsString($this->cardTitle('Ordinary Paper'), $html, 'Non-featured card leaked into the Featured view');
        $this->assertSame(1, substr_count($html, '<h1'));

        // The Featured tab is first and marked current.
        $this->assertMatchesRegularExpression('#class="filter-btn active"\s+aria-current="page"\s*>\s*Featured#', $html);

        // Crawlers still discover every resource URL from the CollectionPage ItemList.
        $this->assertStringContainsString(route('papers.show', $other->slug), $html);
        $this->assertStringContainsString(route('papers.show', $featured->slug), $html);
    }

    public function test_listing_falls_back_to_all_documents_when_nothing_is_featured(): void
    {
        $this->makePaper(['title' => 'Only Paper']);

        $html = $this->get(route('papers'))->assertOk()->getContent();

        $this->assertStringContainsString($this->cardTitle('Only Paper'), $html);
        $this->assertDoesNotMatchRegularExpression('#class="filter-btn[^"]*"[^>]*>\s*Featured\s*<#', $html, 'Featured tab should be hidden when nothing is featured');
        $this->assertMatchesRegularExpression('#class="filter-btn active"\s+aria-current="page"\s*>\s*All Documents#', $html);
    }

    public function test_category_tabs_and_all_documents_still_filter(): void
    {
        $this->makePaper(['title' => 'A Case Study', 'is_featured' => true]);
        $this->makePaper(['title' => 'A White Paper', 'category_id' => $this->whitePapers->id, 'sort_order' => 2]);

        $all = $this->get(route('papers', ['category' => 'all']))->assertOk()->getContent();
        $this->assertStringContainsString($this->cardTitle('A Case Study'), $all);
        $this->assertStringContainsString($this->cardTitle('A White Paper'), $all);

        $white = $this->get(route('papers', ['category' => 'white-papers']))->assertOk()->getContent();
        $this->assertStringContainsString($this->cardTitle('A White Paper'), $white);
        $this->assertStringNotContainsString($this->cardTitle('A Case Study'), $white);
    }

    public function test_cards_link_to_the_resource_page_not_the_pdf(): void
    {
        $paper = $this->makePaper(['is_featured' => true]);

        $html = $this->get(route('papers'))->assertOk()->getContent();

        $this->assertStringContainsString('href="' . route('papers.show', $paper->slug) . '"', $html);
        $this->assertStringNotContainsString('/storage/papers/plantronics.pdf', $html);
        $this->assertStringContainsString('Short summary shown on the card.', $html);
    }

    // ── Resource page ──────────────────────────────────────────────────────

    public function test_document_resource_page_renders_with_one_h1_pdf_link_and_valid_json_ld(): void
    {
        $paper = $this->makePaper([
            'long_description' => '<h1>Should be demoted</h1><p>Long, self-contained summary paragraph.</p>',
            'meta_title'       => 'Plantronics Agile Hardware Case Study',
            'meta_description' => 'How Plantronics applied Agile to hardware R&D.',
        ]);

        $response = $this->get(route('papers.show', $paper->slug))->assertOk();
        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), 'Resource page must have exactly one <h1>');
        $this->assertStringContainsString('<title>Plantronics Agile Hardware Case Study', $html);
        $this->assertStringContainsString('content="How Plantronics applied Agile to hardware R&amp;D."', $html);
        $this->assertStringContainsString('Long, self-contained summary paragraph.', $html);
        $this->assertStringContainsString('<h2>Should be demoted</h2>', $html);
        $this->assertStringContainsString('/storage/papers/plantronics.pdf', $html);
        $this->assertStringContainsString('View PDF', $html);
        $this->assertStringContainsString('<link rel="canonical"    href="' . route('papers.show', $paper->slug) . '"', $html);

        foreach (['__contextArgs', '__contextPrevious', '<?php'] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }

        preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $html, $m);
        $this->assertNotEmpty($m[1]);
        $types = [];
        foreach ($m[1] as $block) {
            $decoded = json_decode($block, true);
            $this->assertIsArray($decoded, 'Invalid JSON-LD: ' . json_last_error_msg());
            $this->assertArrayHasKey('@context', $decoded);
            $types[] = $decoded['@type'] ?? null;
        }
        $this->assertContains('DigitalDocument', $types);
        $this->assertContains('BreadcrumbList', $types);

        $doc = collect($m[1])->map(fn ($b) => json_decode($b, true))->firstWhere('@type', 'DigitalDocument');
        $this->assertSame(['@id' => url('/') . '/#person'], $doc['author']);
        $this->assertSame(['@id' => url('/') . '/#organization'], $doc['publisher']);
        $this->assertSame('application/pdf', $doc['encoding']['encodingFormat']);
    }

    public function test_resource_page_falls_back_to_the_card_summary_until_long_copy_exists(): void
    {
        $paper = $this->makePaper(['long_description' => null]);

        $this->get(route('papers.show', $paper->slug))
            ->assertOk()
            ->assertSee('Short summary shown on the card.');
    }

    public function test_book_resource_page_links_to_amazon_instead_of_a_pdf(): void
    {
        $books = Category::create(['name' => 'Books', 'slug' => 'books', 'type' => 'paper']);
        $book = $this->makePaper([
            'title'         => 'Solutions for Agile Governance in the Enterprise',
            'category_id'   => $books->id,
            'resource_type' => Paper::TYPE_BOOK,
            'file_path'     => null,
            'external_url'  => 'https://www.amazon.com/dp/EXAMPLE',
        ]);

        $html = $this->get(route('papers.show', $book->slug))->assertOk()->getContent();

        $this->assertStringContainsString('href="https://www.amazon.com/dp/EXAMPLE"', $html);
        $this->assertStringContainsString('Buy on Amazon', $html);
        $this->assertStringNotContainsString('View PDF', $html);
        $this->assertStringNotContainsString('File Pending', $html);
        $this->assertStringContainsString('"@type": "Book"', $html);
        $this->assertStringContainsString('"@type": "Offer"', $html);
        $this->assertStringContainsString('<meta property="og:type"        content="book">', $html);
    }

    public function test_hidden_resources_are_not_reachable(): void
    {
        $paper = $this->makePaper(['is_active' => false]);

        $this->get(route('papers.show', $paper->slug))->assertNotFound();
        $this->get(route('papers', ['category' => 'all']))->assertOk()->assertDontSee('Plantronics Case Study');
    }

    public function test_slug_is_generated_automatically_and_kept_unique(): void
    {
        $a = Paper::create(['title' => 'The Agile PMO', 'category_id' => $this->caseStudies->id, 'description' => 'x']);
        $b = Paper::create(['title' => 'The Agile PMO', 'category_id' => $this->caseStudies->id, 'description' => 'y']);

        $this->assertSame('the-agile-pmo', $a->slug);
        $this->assertSame('the-agile-pmo-2', $b->slug);
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function test_admin_can_create_a_resource_with_page_copy_and_seo_fields(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(ManagePapers::class)
            ->call('create')
            ->set('title', 'Agile Processes for Hardware Development')
            ->set('category_id', $this->whitePapers->id)
            ->set('sub_category', 'Methodology')
            ->set('description', '<p>Card summary.</p>')
            ->set('long_description', '<p>Full page copy.</p>')
            ->set('meta_title', 'Agile Processes for Hardware Development | Kevin Thompson')
            ->set('meta_description', 'The foundational white paper.')
            ->set('is_featured', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $paper = Paper::firstOrFail();
        $this->assertSame('agile-processes-for-hardware-development', $paper->slug);
        $this->assertTrue($paper->is_featured);
        $this->assertSame(Paper::TYPE_DOCUMENT, $paper->resource_type);
        $this->assertSame('<p>Full page copy.</p>', $paper->long_description);

        $this->get(route('papers.show', $paper->slug))
            ->assertOk()
            ->assertSee('Full page copy.')
            ->assertSee('<title>Agile Processes for Hardware Development | Kevin Thompson', false);
    }

    public function test_admin_book_resource_requires_a_destination_url(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(ManagePapers::class)
            ->call('create')
            ->set('title', 'Solutions for Agile Governance in the Enterprise')
            ->set('category_id', $this->caseStudies->id)
            ->set('description', '<p>The book.</p>')
            ->set('resource_type', Paper::TYPE_BOOK)
            ->set('external_url', '')
            ->call('save')
            ->assertHasErrors(['external_url']);
    }

    public function test_admin_slug_must_be_unique(): void
    {
        $this->actingAs(User::factory()->create());
        $this->makePaper(['slug' => 'taken']);

        Livewire::test(ManagePapers::class)
            ->call('create')
            ->set('title', 'Another')
            ->set('slug', 'taken')
            ->set('category_id', $this->caseStudies->id)
            ->set('description', '<p>x</p>')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique']);
    }

    public function test_admin_can_toggle_featured_from_the_list(): void
    {
        $this->actingAs(User::factory()->create());
        $paper = $this->makePaper();

        Livewire::test(ManagePapers::class)->call('toggleFeatured', $paper->id);
        $this->assertTrue($paper->fresh()->is_featured);

        Livewire::test(ManagePapers::class)->call('toggleFeatured', $paper->id);
        $this->assertFalse($paper->fresh()->is_featured);
    }
}
