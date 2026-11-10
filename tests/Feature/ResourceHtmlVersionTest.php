<?php

namespace Tests\Feature;

use App\Livewire\Admin\ManagePapers;
use App\Models\Category;
use App\Models\Paper;
use App\Models\User;
use App\Services\SeoGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Resource Library — optional full-text HTML version of a document.
 *
 * When a document has html_content it is also published as an ordinary,
 * indexable web page at /agile-hardware-papers-and-presentations/{slug}/read
 * with the same SEO structure as a blog article; the summary page's "View PDF"
 * becomes "View". Resources without it must behave exactly as before.
 */
class ResourceHtmlVersionTest extends TestCase
{
    use RefreshDatabase;

    private const BODY = '<h2>Why hardware is different</h2>'
        . '<p>Hardware teams face long lead times, physical prototypes and supplier dependencies that software teams never meet, so Scrum has to be adapted rather than copied.</p>'
        . '<ul><li>Plan around procurement</li><li>Prototype in increments</li></ul>'
        . '<table><tr><td>Cadence</td><td>Two weeks</td></tr></table>';

    private Category $whitePapers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->whitePapers = Category::create(['name' => 'White Papers', 'slug' => 'white-papers', 'type' => 'paper']);
    }

    private function makePaper(array $overrides = []): Paper
    {
        return Paper::create(array_merge([
            'title'        => 'Agile Processes for Hardware Development',
            'category_id'  => $this->whitePapers->id,
            'sub_category' => 'Methodology',
            'description'  => '<p>Short summary shown on the card.</p>',
            'file_path'    => 'papers/agile-hardware.pdf',
            'is_active'    => true,
            'is_featured'  => false,
            'sort_order'   => 1,
        ], $overrides));
    }

    /** @return array<int, array<string, mixed>> every JSON-LD block on the page, decoded */
    private function jsonLd(string $html): array
    {
        preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $html, $m);
        $this->assertNotEmpty($m[1], 'No JSON-LD found');

        return array_map(function ($block) {
            $decoded = json_decode($block, true);
            $this->assertIsArray($decoded, 'Invalid JSON-LD: ' . json_last_error_msg());
            $this->assertArrayHasKey('@context', $decoded);

            return $decoded;
        }, $m[1]);
    }

    private function metaDescription(string $html): string
    {
        $this->assertSame(1, preg_match('#<meta name="description"\s+content="([^"]*)"#', $html, $m), 'meta description missing');

        return $m[1];
    }

    private function pageTitle(string $html): string
    {
        $this->assertSame(1, preg_match('#<title>(.*?)</title>#s', $html, $m), '<title> missing');

        return trim($m[1]);
    }

    // ── Navigation ─────────────────────────────────────────────────────────

    public function test_client_list_sits_between_about_and_consulting_in_the_main_nav_and_the_drawer(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $link = '<a href="' . route('clients') . '"';

        foreach (['<div class="nav-menu">' => 'desktop nav', '<div class="drawer" id="drawer">' => 'mobile drawer'] as $marker => $label) {
            $start = strpos($html, $marker);
            $this->assertNotFalse($start, "{$label} not found");
            $block = substr($html, $start, 2500);

            $about      = strpos($block, '>About</a>');
            $clients    = strpos($block, '>Client List</a>');
            $consulting = strpos($block, '>Consulting &amp; Training</a>') ?: strpos($block, '>Consulting & Training</a>');

            $this->assertNotFalse($about, "{$label}: About missing");
            $this->assertNotFalse($clients, "{$label}: Client List missing");
            $this->assertNotFalse($consulting, "{$label}: Consulting & Training missing");
            $this->assertTrue($about < $clients && $clients < $consulting, "{$label}: Client List is not between About and Consulting & Training");
            $this->assertStringContainsString($link, $block, "{$label}: Client List does not point at the client showcase");
        }
    }

    public function test_client_showcase_page_renders_and_marks_its_nav_link_active(): void
    {
        $html = $this->get(route('clients'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<a href="' . route('clients') . '" class="active">Client List</a>', $html);
    }

    // ── Reading page ───────────────────────────────────────────────────────

    public function test_reading_page_renders_the_full_text_server_side_with_one_h1(): void
    {
        $paper = $this->makePaper(['html_content' => self::BODY]);

        $html = $this->get(route('papers.read', $paper->slug))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), 'Reading page must have exactly one H1');
        $this->assertStringContainsString('<h1 class="page-title">Agile Processes for Hardware Development</h1>', $html);
        $this->assertStringContainsString('<h2>Why hardware is different</h2>', $html);
        $this->assertStringContainsString('physical prototypes and supplier dependencies', $html);
        $this->assertStringContainsString('<li>Plan around procurement</li>', $html);
        $this->assertStringContainsString('min read', $html);

        // The original file stays downloadable from the reading page.
        $this->assertStringContainsString('Download PDF', $html);
        $this->assertStringContainsString($paper->file_url, $html);

        // And it links back to the summary page and the library.
        $this->assertStringContainsString(route('papers.show', $paper->slug), $html);
        $this->assertStringContainsString('href="' . route('papers') . '"', $html);
    }

    public function test_reading_page_404s_when_there_is_no_html_version(): void
    {
        $none   = $this->makePaper(['slug' => 'no-html']);
        $blank  = $this->makePaper(['slug' => 'blank-html', 'html_content' => "  \n "]);
        $nbsp   = $this->makePaper(['slug' => 'nbsp-html', 'html_content' => '<p>&nbsp;</p><p><br></p>']);
        $hidden = $this->makePaper(['slug' => 'hidden-html', 'html_content' => self::BODY, 'is_active' => false]);
        $book   = $this->makePaper(['slug' => 'book-html', 'html_content' => self::BODY, 'resource_type' => Paper::TYPE_BOOK, 'file_path' => null, 'external_url' => 'https://www.amazon.com/dp/1604271582']);
        $link   = $this->makePaper(['slug' => 'link-html', 'html_content' => self::BODY, 'resource_type' => Paper::TYPE_LINK, 'file_path' => null, 'external_url' => 'https://example.org']);

        foreach ([$none, $blank, $nbsp, $hidden, $book, $link] as $paper) {
            $this->get(route('papers.read', $paper->slug))->assertNotFound();
            $this->assertFalse($paper->has_html_version && $paper->is_active, "{$paper->slug} should not expose a reading page");
        }

        $this->get(route('papers.read', 'does-not-exist'))->assertNotFound();
    }

    public function test_h1_in_the_pasted_content_is_demoted_and_a_repeated_title_is_dropped(): void
    {
        $demoted = $this->makePaper([
            'slug'         => 'demoted',
            'html_content' => '<h1 class="x">A Different Heading</h1><p>' . str_repeat('Body text here. ', 10) . '</p>',
        ]);
        $html = $this->get(route('papers.read', $demoted->slug))->assertOk()->getContent();
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<h2 class="x">A Different Heading</h2>', $html);

        // Word pastes usually start with the paper's own title — it must not print twice.
        $repeated = $this->makePaper([
            'slug'         => 'repeated',
            'html_content' => '<h1>Agile&nbsp;Processes for  Hardware Development</h1><p>' . str_repeat('Body text here. ', 10) . '</p>',
        ]);
        $html = $this->get(route('papers.read', $repeated->slug))->assertOk()->getContent();
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertSame(0, preg_match('#<h2[^>]*>Agile(&nbsp;|\s)Processes#', $html), 'The repeated title heading should be dropped');
    }

    public function test_reading_page_seo_matches_the_blog_article_structure(): void
    {
        $paper = $this->makePaper(['html_content' => self::BODY, 'meta_keywords' => 'agile hardware, scrum']);

        $read    = $this->get(route('papers.read', $paper->slug))->assertOk()->getContent();
        $summary = $this->get(route('papers.show', $paper->slug))->assertOk()->getContent();

        // Canonical: each page points at itself.
        $this->assertStringContainsString('<link rel="canonical"    href="' . route('papers.read', $paper->slug) . '"', $read);
        $this->assertStringContainsString('<link rel="canonical"    href="' . route('papers.show', $paper->slug) . '"', $summary);

        // Title and description: present, and not duplicates of the summary page.
        $this->assertStringContainsString('Full Text', $this->pageTitle($read));
        $this->assertNotSame($this->pageTitle($summary), $this->pageTitle($read));
        $this->assertNotSame('', $this->metaDescription($read));
        $this->assertNotSame($this->metaDescription($summary), $this->metaDescription($read));
        $this->assertStringContainsString('Hardware teams face long lead times', html_entity_decode($this->metaDescription($read)));

        // Open Graph article tags, as on blog posts.
        $this->assertMatchesRegularExpression('#<meta property="og:type"\s+content="article"#', $read);
        $this->assertStringContainsString('<meta property="article:published_time"', $read);
        $this->assertStringContainsString('<meta property="article:modified_time"', $read);
        $this->assertStringContainsString('<meta property="article:author"', $read);
        $this->assertStringContainsString('<meta property="article:section" content="White Papers">', $read);

        // The page views must not redeclare what the layout owns.
        $this->assertSame(1, substr_count($read, '<title>'));
        $this->assertSame(1, substr_count($read, 'rel="canonical"'));
        $this->assertSame(1, substr_count($read, '<meta name="description"'));
    }

    public function test_reading_page_structured_data(): void
    {
        $paper = $this->makePaper(['html_content' => self::BODY]);

        $html   = $this->get(route('papers.read', $paper->slug))->assertOk()->getContent();
        $blocks = collect($this->jsonLd($html));

        foreach (['__contextArgs', '__contextPrevious', '<?php'] as $needle) {
            $this->assertStringNotContainsString($needle, $html, "Blade leaked {$needle}");
        }

        $article = $blocks->firstWhere('@type', 'ScholarlyArticle');
        $this->assertNotNull($article, 'ScholarlyArticle JSON-LD missing');
        $this->assertSame(1, $blocks->where('@type', 'ScholarlyArticle')->count());

        // Entities are referenced by @id — never an inline Person / Organization.
        $this->assertSame(['@id' => url('/') . '/#person'], $article['author']);
        $this->assertSame(['@id' => url('/') . '/#organization'], $article['publisher']);

        $this->assertSame(route('papers.read', $paper->slug), $article['url']);
        $this->assertSame(route('papers.read', $paper->slug), $article['mainEntityOfPage']);
        $this->assertSame('Agile Processes for Hardware Development', $article['headline']);
        $this->assertGreaterThan(20, $article['wordCount']);
        $this->assertSame('White Papers', $article['articleSection']);
        $this->assertArrayHasKey('datePublished', $article);
        $this->assertArrayHasKey('dateModified', $article);
        $this->assertStringEndsWith('/storage/papers/agile-hardware.pdf', $article['associatedMedia']['contentUrl']);
        $this->assertSame('application/pdf', $article['associatedMedia']['encodingFormat']);

        $crumbs = $blocks->firstWhere('@type', 'BreadcrumbList');
        $this->assertNotNull($crumbs);
        $items = $crumbs['itemListElement'];
        $this->assertSame(range(1, count($items)), array_column($items, 'position'));
        $this->assertSame(route('papers.read', $paper->slug), end($items)['item']);
        $this->assertContains(route('papers.show', $paper->slug), array_column($items, 'item'));
    }

    public function test_markup_mentioned_in_the_paper_cannot_break_out_of_the_json_ld_block(): void
    {
        $paper = $this->makePaper([
            'html_content' => '<p>Close the tag with &lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt; and carry on writing enough text to be used as the description.</p>',
        ]);

        $html = $this->get(route('papers.read', $paper->slug))->assertOk()->getContent();

        // Every block still parses, and the text is intact once decoded.
        $article = collect($this->jsonLd($html))->firstWhere('@type', 'ScholarlyArticle');
        $this->assertStringContainsString('</script><script>alert(1)</script>', $article['description']);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function test_reading_page_works_for_a_document_with_no_file(): void
    {
        $paper = $this->makePaper(['html_content' => self::BODY, 'file_path' => null]);

        $html = $this->get(route('papers.read', $paper->slug))->assertOk()->getContent();
        $this->assertStringNotContainsString('Download', $html);
        $article = collect($this->jsonLd($html))->firstWhere('@type', 'ScholarlyArticle');
        $this->assertArrayNotHasKey('associatedMedia', $article);

        $summary = $this->get(route('papers.show', $paper->slug))->assertOk()->getContent();
        $this->assertStringContainsString('href="' . route('papers.read', $paper->slug) . '" class="btn-resource"', $summary);
        $this->assertStringNotContainsString('File Pending', $summary);
    }

    // ── Summary page ───────────────────────────────────────────────────────

    public function test_summary_page_swaps_view_pdf_for_view_when_an_html_version_exists(): void
    {
        $paper = $this->makePaper(['html_content' => self::BODY]);

        $html = $this->get(route('papers.show', $paper->slug))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringNotContainsString('View PDF', $html);
        $this->assertMatchesRegularExpression(
            '#<a href="' . preg_quote(route('papers.read', $paper->slug), '#') . '" class="btn-resource">\s*View\s*<svg#',
            $html
        );
        // Download is unchanged and still serves the file.
        $this->assertMatchesRegularExpression('#<a href="' . preg_quote($paper->file_url, '#') . '" class="btn-resource-secondary" download>#', $html);
        $this->assertStringContainsString('Download PDF', $html);

        $doc = collect($this->jsonLd($html))->firstWhere('@type', 'DigitalDocument');
        $this->assertSame(route('papers.read', $paper->slug), $doc['workExample']['url']);
        $this->assertSame('application/pdf', $doc['encoding']['encodingFormat']);
    }

    public function test_summary_page_is_unchanged_without_an_html_version(): void
    {
        $paper = $this->makePaper();

        $html = $this->get(route('papers.show', $paper->slug))->assertOk()->getContent();

        $this->assertStringContainsString('View PDF', $html);
        $this->assertStringContainsString('Download PDF', $html);
        $this->assertStringNotContainsString('/read"', $html);

        $doc = collect($this->jsonLd($html))->firstWhere('@type', 'DigitalDocument');
        $this->assertArrayNotHasKey('workExample', $doc);
    }

    public function test_presentations_keep_their_file_only_behaviour(): void
    {
        $deck = $this->makePaper(['title' => 'Conference Deck', 'file_path' => 'papers/deck.pptx']);

        $html = $this->get(route('papers.show', $deck->slug))->assertOk()->getContent();

        $this->assertStringContainsString('View Presentation', $html);
        $this->assertStringContainsString('Download PPTX', $html);
        $this->get(route('papers.read', $deck->slug))->assertNotFound();
    }

    // ── Listing ────────────────────────────────────────────────────────────

    public function test_listing_shows_the_read_online_badge_only_for_resources_with_an_html_version(): void
    {
        $this->makePaper(['title' => 'With Web Version', 'html_content' => self::BODY]);
        $this->makePaper(['title' => 'File Only', 'sort_order' => 2]);

        $html = $this->get(route('papers', ['category' => 'all']))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertSame(1, substr_count($html, 'class="paper-read-badge"'));

        $withPos  = strpos($html, 'class="paper-title-link">With Web Version</a>');
        $badgePos = strpos($html, 'class="paper-read-badge"');
        $filePos  = strpos($html, 'class="paper-title-link">File Only</a>');
        $this->assertNotFalse($withPos);
        $this->assertNotFalse($filePos);
        $this->assertTrue($badgePos < $withPos && $withPos < $filePos, 'Badge is attached to the wrong card');
    }

    // ── sitemap.xml / llms.txt ─────────────────────────────────────────────

    public function test_sitemap_and_llms_list_the_reading_page_only_for_eligible_resources(): void
    {
        $with    = $this->makePaper(['slug' => 'with-html', 'html_content' => self::BODY]);
        $without = $this->makePaper(['slug' => 'file-only', 'sort_order' => 2]);
        $hidden  = $this->makePaper(['slug' => 'hidden-html', 'html_content' => self::BODY, 'is_active' => false, 'sort_order' => 3]);

        // SeoGenerator writes into public_path(). Point that at a throwaway
        // directory so the committed public/sitemap.xml and llms.txt are never touched.
        $realPublic = public_path();
        $tmp = storage_path('framework/testing/seo-' . uniqid());
        File::ensureDirectoryExists($tmp);
        $this->app->usePublicPath($tmp);

        try {
            SeoGenerator::generateSitemap();
            SeoGenerator::generateLlms();

            $sitemap = File::get($tmp . '/sitemap.xml');
            $llms    = File::get($tmp . '/llms.txt');
        } finally {
            $this->app->usePublicPath($realPublic);
            File::deleteDirectory($tmp);
        }

        $dom = new \DOMDocument();
        $this->assertTrue($dom->loadXML($sitemap), 'sitemap.xml is not valid XML');

        $this->assertStringContainsString('<loc>' . route('papers.show', $with->slug) . '</loc>', $sitemap);
        $this->assertStringContainsString('<loc>' . route('papers.read', $with->slug) . '</loc>', $sitemap);
        $this->assertStringContainsString('<loc>' . route('papers.show', $without->slug) . '</loc>', $sitemap);
        $this->assertStringNotContainsString(route('papers.read', $without->slug), $sitemap);
        $this->assertStringNotContainsString($hidden->slug, $sitemap);

        $this->assertStringContainsString('Full text (HTML): ' . route('papers.read', $with->slug), $llms);
        $this->assertStringNotContainsString(route('papers.read', $without->slug), $llms);
        $this->assertStringNotContainsString($hidden->slug, $llms);
        $this->assertSame(1, substr_count($llms, 'Full text (HTML):'));
    }

    // ── Admin ──────────────────────────────────────────────────────────────

    public function test_admin_can_paste_a_word_document_and_it_is_saved_clean(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(ManagePapers::class)
            ->call('create')
            ->set('title', 'Agile Processes for Hardware Development')
            ->set('category_id', $this->whitePapers->id)
            ->set('description', '<p>Card summary.</p>')
            ->set('html_content', '<!--[if gte mso 9]><xml></xml><![endif]--><p class="MsoNormal" style="mso-pagination:none;text-align:center">Pasted from Word.<o:p></o:p></p><p class="MsoNormal"><o:p>&nbsp;</o:p></p>')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $paper = Paper::firstOrFail();
        $this->assertSame('<p style="text-align:center">Pasted from Word.</p>', $paper->html_content);
        $this->assertTrue($paper->has_html_version);

        $this->get(route('papers.read', $paper->slug))->assertOk()->assertSee('Pasted from Word.');
    }

    public function test_admin_edit_loads_the_html_and_clearing_it_unpublishes_the_reading_page(): void
    {
        $this->actingAs(User::factory()->create());
        $paper = $this->makePaper(['html_content' => self::BODY]);

        Livewire::test(ManagePapers::class)
            ->call('edit', $paper->id)
            ->assertSet('html_content', self::BODY)
            ->set('html_content', '<p>&nbsp;</p>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull($paper->fresh()->html_content);
        $this->get(route('papers.read', $paper->slug))->assertNotFound();
        $this->get(route('papers.show', $paper->slug))->assertOk()->assertSee('View PDF');
    }

    public function test_admin_switching_a_resource_to_a_book_drops_its_html_version(): void
    {
        $this->actingAs(User::factory()->create());
        $paper = $this->makePaper(['html_content' => self::BODY]);

        Livewire::test(ManagePapers::class)
            ->call('edit', $paper->id)
            ->set('resource_type', Paper::TYPE_BOOK)
            ->set('external_url', 'https://www.amazon.com/dp/1604271582')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull($paper->fresh()->html_content);
    }

    public function test_admin_list_flags_resources_that_have_an_html_version(): void
    {
        $this->actingAs(User::factory()->create());
        $with = $this->makePaper(['slug' => 'with-html', 'html_content' => self::BODY]);
        $this->makePaper(['slug' => 'file-only', 'sort_order' => 2]);

        $html = Livewire::test(ManagePapers::class)->html();

        $this->assertSame(1, substr_count($html, 'rl-type-html'));
        $this->assertStringContainsString(route('papers.read', $with->slug), $html);
        $this->assertStringNotContainsString(route('papers.read', 'file-only'), $html);

        // Admin convention: SweetAlert only, never native dialogs.
        $this->assertStringNotContainsString('wire:confirm', $html);
        $this->assertStringNotContainsString('confirm(', $html);
    }
}
