<?php

namespace Tests\Unit;

use App\Support\WordHtmlCleaner;
use PHPUnit\Framework\TestCase;

/**
 * Word paste clean-up for the Resource Library "Full Text (HTML)" editor.
 * The cleaner may tidy markup but must never lose the author's content.
 */
class WordHtmlCleanerTest extends TestCase
{
    public function test_strips_word_clutter_and_keeps_the_structure(): void
    {
        $word = '<!--[if gte mso 9]><xml><w:WordDocument><w:View>Normal</w:View></w:WordDocument></xml><![endif]-->'
            . '<style>p.MsoNormal{margin:0}</style>'
            . '<h2 class="MsoTitle" style="mso-style-name:x; text-align:center">Heading<o:p></o:p></h2>'
            . '<p class="MsoNormal" lang="EN-US" style="margin-bottom:0in;line-height:normal;mso-pagination:none">'
            . '<span style=\'font-family:"Times New Roman",serif;color:red\'>Body &amp; text</span><o:p></o:p></p>'
            . '<p class="MsoNormal"><o:p>&nbsp;</o:p></p><p>&nbsp;</p>'
            . '<table class="MsoTableGrid" style="border-collapse:collapse;mso-yfti-tbllook:1184"><tr><td style="width:100px;mso-border-alt:solid">Cell</td></tr></table>'
            . '<p><img src="/storage/x.png" alt="x"></p>'
            . '<p><a href="https://example.org" hreflang="en">link</a></p><ul><li>one</li></ul>';

        $clean = WordHtmlCleaner::clean($word);

        foreach (['<!--', 'mso-', 'Mso', '<o:p', '<w:', '<style', 'lang="EN-US"', 'font-family', 'line-height', '&nbsp;'] as $gone) {
            $this->assertStringNotContainsString($gone, $clean, "Word clutter survived: {$gone}");
        }

        foreach ([
            '<h2 style="text-align:center">Heading</h2>',
            "<span style='color:red'>Body &amp; text</span>",
            '<table style="border-collapse:collapse">',
            '<td style="width:100px">Cell</td>',
            '<img src="/storage/x.png" alt="x">',
            '<a href="https://example.org" hreflang="en">link</a>',
            '<ul><li>one</li></ul>',
        ] as $kept) {
            $this->assertStringContainsString($kept, $clean, "Legitimate markup was lost: {$kept}");
        }
    }

    /**
     * A Word table of contents + numbered headings, as pasted from a real
     * paper: hidden page numbers, tab-stop spacer spans, bookmarks and blank
     * cover-page paragraphs. The links must keep working and the clutter go.
     */
    public function test_word_table_of_contents_and_numbered_headings_come_through_clean(): void
    {
        $word = '<p style="text-align: right" align="right"><span style="font-size: 36.0pt; color: #a4451d">&nbsp;</span></p>'
            . '<p><span>&nbsp;</span></p>'
            . '<p><a href="#_Toc239231744"><span>4</span><span style="color: windowtext; text-decoration: none; text-underline: none"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span>The Compounding Effect</span>'
            . '<span style="color: windowtext; display: none; text-decoration: none; text-underline: none"><span>. </span></span>'
            . '<span style="color: windowtext; display: none; text-decoration: none; text-underline: none">12</span></a></p>'
            . '<h2><a name="_Ref238809230"></a><a name="_Toc239231744"></a><span><span>4<span style="font: 7.0pt \'Times New Roman\'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span>The Compounding Effect</h2>'
            . '<p>Cost grows as <span style="font-size: 12.0pt; position: relative; top: 3.0pt"><img src="/storage/eq.png" width="82" height="19"></span>, which grows as the square of <em>N</em>.</p>'
            . '<p><img src="/storage/figure.jpg" width="624" height="375"></p>';

        $clean = WordHtmlCleaner::clean($word);

        $this->assertSame(
            '<p><a href="#_Toc239231744">4 The Compounding Effect</a></p>'
            . '<h2><a id="_Ref238809230"></a><a id="_Toc239231744"></a>4 The Compounding Effect</h2>'
            . '<p>Cost grows as <span style="font-size: 12.0pt; position: relative; top: 3.0pt"><img class="inline-image" src="/storage/eq.png" width="82" height="19"></span>, which grows as the square of <em>N</em>.</p>'
            . '<p><img src="/storage/figure.jpg" width="624" height="375"></p>',
            $clean
        );
    }

    public function test_hidden_spans_are_removed_with_their_nested_content_and_nothing_else(): void
    {
        $clean = WordHtmlCleaner::clean(
            '<p>Keep <span style="color: red">red <span>plain</span> text</span>'
            . '<span style="display: none">gone <span style="color: blue">nested gone</span> too</span> end.</p>'
        );

        $this->assertSame('<p>Keep <span style="color: red">red plain text</span> end.</p>', $clean);
    }

    public function test_effectively_empty_input_becomes_null(): void
    {
        $this->assertNull(WordHtmlCleaner::clean(null));
        $this->assertNull(WordHtmlCleaner::clean(''));
        $this->assertNull(WordHtmlCleaner::clean('   '));
        $this->assertNull(WordHtmlCleaner::clean('<p>&nbsp;</p>'));
        $this->assertNull(WordHtmlCleaner::clean('<p><span style="font-size: 36.0pt">&nbsp;</span></p>'));
        $this->assertNull(WordHtmlCleaner::clean('<p class="MsoNormal"><o:p>&nbsp;</o:p></p><p><br></p>'));
    }

    public function test_an_image_only_document_is_not_treated_as_empty(): void
    {
        $this->assertSame('<p><img src="/a.png"></p>', WordHtmlCleaner::clean('<p><img src="/a.png"></p>'));
    }

    public function test_clean_html_passes_through_unchanged(): void
    {
        $html = '<h2>Intro</h2><p>Plain <strong>text</strong> with a <a href="/faq">link</a>.</p><ol><li>a</li></ol>';

        $this->assertSame($html, WordHtmlCleaner::clean($html));
    }

    /**
     * Regression: PCRE returns null when a pattern hits its backtrack / JIT
     * limit or meets invalid UTF-8. That null used to cascade and the whole
     * paper was saved as empty.
     */
    public function test_content_survives_inputs_that_make_pcre_fail(): void
    {
        $cases = [
            'huge comment'        => '<p>before</p><!--' . str_repeat('x ', 600000) . '--><p>after</p>',
            'unclosed comment'    => '<p>before</p><!--[if x]' . str_repeat('y ', 600000),
            'long whitespace run' => '<p>' . str_repeat(' ', 900000) . 'text</p>',
            'invalid utf-8'       => "<p>caf\xe9 text</p>",
        ];

        foreach ($cases as $label => $input) {
            $clean = WordHtmlCleaner::clean($input);
            $this->assertNotNull($clean, "Content was wiped for: {$label}");
            $this->assertStringContainsString(str_contains($input, 'before') ? 'before' : 'text', $clean, $label);
        }
    }

    public function test_a_one_megabyte_word_paste_is_cleaned_quickly(): void
    {
        $chunk = '<p class="MsoNormal" style="margin:0;mso-pagination:none">Paragraph of body text.<o:p></o:p></p>';
        $big   = str_repeat($chunk, (int) ceil(1048576 / strlen($chunk)));

        $start = microtime(true);
        $clean = WordHtmlCleaner::clean($big);
        $took  = microtime(true) - $start;

        $this->assertNotNull($clean);
        $this->assertStringNotContainsString('Mso', $clean);
        $this->assertLessThan(2.0, $took, 'Cleaning 1MB of Word HTML took too long');
    }
}
