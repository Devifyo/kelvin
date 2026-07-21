<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the JSON-LD emitted by the public pages.
 *
 * These exist because of a live production defect: Blade compiles the literal
 * schema context key (at-sign + "context") as its own directive when it appears
 * outside a PHP block, which replaced the key with compiled PHP source. The
 * markup stayed valid JSON, so nothing crashed — Google simply discarded the
 * block, and server internals leaked into the HTML. Only an output-level
 * assertion catches that class of bug.
 */
class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    /** Pages that emit FAQPage JSON-LD via a Blade partial. */
    private const FAQ_PAGES = ['/agile-consulting-services', '/faq'];

    /**
     * The FAQ partials render nothing when no sections exist, which would make
     * every assertion below vacuously pass. Seed real rows so the JSON-LD is
     * actually emitted and the guard has something to inspect.
     */
    protected function setUp(): void
    {
        parent::setUp();

        foreach (['services', 'faq'] as $page) {
            $section = FaqSection::create([
                'key'        => "test-{$page}",
                'page'       => $page,
                'name'       => 'Test Section',
                'title'      => 'Test',
                'is_active'  => true,
                'sort_order' => 1,
            ]);

            Faq::create([
                'faq_section_id' => $section->id,
                'question'       => 'Can Scrum be used for hardware development?',
                'answer'         => 'Yes — with adaptations for long lead-time components.',
                'is_active'      => true,
                'sort_order'     => 1,
            ]);
        }

        FaqSection::clearCache();
    }

    public function test_the_faq_pages_actually_emit_faqpage_json_ld(): void
    {
        // Guards the guard: if this fails, the assertions below are vacuous.
        foreach (self::FAQ_PAGES as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('FAQPage', false);
        }
    }

    public function test_no_compiled_blade_source_leaks_into_rendered_html(): void
    {
        foreach (array_merge(self::FAQ_PAGES, ['/', '/about-kevin-thompson', '/agile-insights-blog']) as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            foreach (['__contextArgs', '__contextPrevious', '<?php'] as $needle) {
                $this->assertStringNotContainsString(
                    $needle,
                    $html,
                    "{$path} leaked compiled Blade/PHP source ({$needle}) into its HTML response"
                );
            }
        }
    }

    public function test_every_json_ld_block_is_valid_and_declares_a_context(): void
    {
        foreach (array_merge(self::FAQ_PAGES, ['/', '/about-kevin-thompson']) as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            preg_match_all(
                '#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is',
                $html,
                $matches
            );

            $this->assertNotEmpty($matches[1], "{$path} emitted no JSON-LD at all");

            foreach ($matches[1] as $i => $block) {
                $decoded = json_decode($block, true);

                $this->assertIsArray(
                    $decoded,
                    "{$path} JSON-LD block #{$i} is not valid JSON: " . json_last_error_msg()
                );

                // The failure mode being guarded: valid JSON, but the context key
                // was clobbered, so it is not valid JSON-LD and gets discarded.
                $this->assertArrayHasKey(
                    '@context',
                    $decoded,
                    "{$path} JSON-LD block #{$i} parses as JSON but declares no context key"
                );
            }
        }
    }

    public function test_training_titles_are_branded_and_within_serp_width(): void
    {
        $service = \App\Models\Service::create([
            'title' => 'Advanced Product Owner', 'slug' => 'advanced-product-owner-test',
            'type' => 'training', 'is_active' => true, 'short_description' => 'Test course.', 'content' => 'Test content.',
        ]);

        // No meta_title (as in production) -> built title, branded, keyword-bearing.
        $this->assertSame('Advanced Product Owner Training | Kevin Thompson', $service->seoTitle());
        $this->assertLessThanOrEqual(60, mb_strlen($service->seoTitle()));

        // "Training" is dropped when appending it would overflow the SERP width.
        $long = \App\Models\Service::create([
            'title' => 'Agile Overview for Executives and Managers', 'slug' => 'long-test',
            'type' => 'training', 'is_active' => true, 'short_description' => 'Test course.', 'content' => 'Test content.',
        ]);
        $this->assertSame('Agile Overview for Executives and Managers | Kevin Thompson', $long->seoTitle());
        $this->assertLessThanOrEqual(60, mb_strlen($long->seoTitle()));

        // An explicit meta_title always wins.
        $service->meta_title = 'Hand Written Title';
        $this->assertSame('Hand Written Title', $service->seoTitle());
    }
}
