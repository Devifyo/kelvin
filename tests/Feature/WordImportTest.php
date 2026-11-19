<?php

namespace Tests\Feature;

use App\Livewire\Admin\BlogPosts;
use App\Livewire\Admin\ManagePapers;
use App\Models\Category;
use App\Models\Paper;
use App\Models\User;
use App\Support\DocxToHtml;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use ZipArchive;

/**
 * "Import from Word": a .docx is uploaded from the Resource Library or Blog
 * editor, converted on the server (App\Support\DocxToHtml), its pictures are
 * stored, and clean HTML goes back into the editor.
 *
 * The fixture document is built in code so every Word feature the client's
 * papers use is covered: numbered headings, a linked table of contents with
 * page numbers, lists, a table with a shaded header and merged cells, a
 * picture with a caption and a cross-reference, an endnote, a text box stored
 * twice (modern + fallback), a Visio/EMF picture, hidden text and links.
 */
class WordImportTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';

    private function admin(): User
    {
        Role::findOrCreate(config('roles.admin'));

        return tap(User::factory()->create())->assignRole(config('roles.admin'));
    }

    // ── Fixture .docx ────────────────────────────────────────────────────

    private function docx(?string $body = null): string
    {
        $W = 'xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"'
           . ' xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"'
           . ' xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"'
           . ' xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"';

        $run   = fn (string $t, string $rPr = '') => "<w:r>" . ($rPr ? "<w:rPr>{$rPr}</w:rPr>" : '') . '<w:t xml:space="preserve">' . $t . '</w:t></w:r>';
        $p     = fn (string $inner, string $pPr = '') => '<w:p>' . ($pPr ? "<w:pPr>{$pPr}</w:pPr>" : '') . $inner . '</w:p>';
        $style = fn (string $id) => '<w:pStyle w:val="' . $id . '"/>';
        $num   = fn (int $numId, int $ilvl = 0) => '<w:numPr><w:ilvl w:val="' . $ilvl . '"/><w:numId w:val="' . $numId . '"/></w:numPr>';
        $cell  = fn (string $text, string $tcPr = '') => '<w:tc>' . ($tcPr ? "<w:tcPr>{$tcPr}</w:tcPr>" : '') . '<w:p>' . ($text !== '' ? $run($text) : '') . '</w:p></w:tc>';
        $fill  = '<w:shd w:val="clear" w:color="auto" w:fill="1A2332"/>';
        $box   = '<w:txbxContent>' . $p($run('Policies about Quality Assurance', '<w:b/>')) . $p($run('All defects must be fixed immediately')) . '</w:txbxContent>';

        $body ??= ''
            // Cover page: large text by hand, not a heading
            . $p($run('Agile Processes for Hardware Development', '<w:sz w:val="72"/>'), '<w:jc w:val="right"/>')
            . $p($run('AND WHAT TO DO ABOUT IT', '<w:sz w:val="44"/>'), '<w:jc w:val="right"/>')
            . $p('') // empty spacer paragraph
            // Table of contents entry with a hidden page-number field
            . $p('<w:hyperlink w:anchor="_Toc100">' . $run('1') . '<w:r><w:tab/></w:r>' . $run('Introduction', '<w:rStyle w:val="Hyperlink"/>')
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:tab/></w:r>'
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:fldChar w:fldCharType="begin"/></w:r>'
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:instrText xml:space="preserve"> PAGEREF _Toc100 \h </w:instrText></w:r>'
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:fldChar w:fldCharType="separate"/></w:r>'
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:t>3</w:t></w:r>'
                . '<w:r><w:rPr><w:webHidden/></w:rPr><w:fldChar w:fldCharType="end"/></w:r></w:hyperlink>', $style('TOC1'))
            // Numbered headings
            . $p('<w:bookmarkStart w:id="1" w:name="_Toc100"/>' . $run('Introduction') . '<w:bookmarkEnd w:id="1"/>', $style('Heading1'))
            . $p($run('Plain, ') . $run('bold', '<w:b/>') . $run(' and bold', '<w:b/>') . $run(' and ') . $run('italic', '<w:i/>') . $run(' text') . $run(' hidden', '<w:vanish/>') . $run('.'))
            . $p($run('Details'), $style('Heading2'))
            // Lists
            . $p($run('First bullet'), $style('ListParagraph') . $num(2))
            . $p($run('Nested bullet'), $style('ListParagraph') . $num(2, 1))
            . $p($run('Second bullet'), $style('ListParagraph') . $num(2))
            . $p($run('Step one'), $style('ListParagraph') . $num(3))
            . $p($run('Step two'), $style('ListParagraph') . $num(3))
            // Cross-reference (REF field) to the caption below, plus an endnote
            . $p($run('See ') . '<w:r><w:fldChar w:fldCharType="begin"/></w:r><w:r><w:instrText xml:space="preserve"> REF _Ref200 \h </w:instrText></w:r><w:r><w:fldChar w:fldCharType="separate"/></w:r>'
                . $run('Figure 1', '<w:b/>') . '<w:r><w:fldChar w:fldCharType="end"/></w:r>' . $run(' for details.')
                . '<w:r><w:rPr><w:vertAlign w:val="superscript"/></w:rPr><w:endnoteReference w:id="2"/></w:r>')
            // Picture + caption
            . $p('<w:r><w:drawing><wp:inline><wp:extent cx="5943600" cy="2971800"/><wp:docPr id="1" name="Picture 1"/><a:graphic><a:graphicData><pic:pic><pic:blipFill><a:blip r:embed="rIdImg"/></pic:blipFill></pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing></w:r>')
            . $p('<w:bookmarkStart w:id="2" w:name="_Ref200"/>' . $run('Figure 1: Release plan') . '<w:bookmarkEnd w:id="2"/>', $style('Caption'))
            // Visio drawing stored as EMF
            . $p('<w:r><w:object><v:shape style="width:300pt;height:150pt"><v:imagedata r:id="rIdEmf" o:title=""/></v:shape></w:object></w:r>')
            . $p($run('Figure 2: Release cycle'), $style('Caption'))
            // Text box stored twice: DrawingML choice + VML fallback
            . $p('<w:r><mc:AlternateContent><mc:Choice Requires="wps"><w:drawing><wp:anchor><wp:extent cx="100" cy="100"/><a:graphic><a:graphicData><wps:wsp><wps:txbx>' . $box . '</wps:txbx></wps:wsp></a:graphicData></a:graphic></wp:anchor></w:drawing></mc:Choice>'
                . '<mc:Fallback><w:pict><v:shape><v:textbox>' . $box . '</v:textbox></v:shape></w:pict></mc:Fallback></mc:AlternateContent></w:r>')
            // Table: shaded header, merged columns and rows
            . '<w:tbl>'
            . '<w:tr>' . $cell('Ceremony', $fill) . $cell('Time box', $fill) . $cell('Output', $fill) . '</w:tr>'
            . '<w:tr>' . $cell('Sprint Planning', '<w:vMerge w:val="restart"/>') . $cell('2 hours per week of Sprint', '<w:gridSpan w:val="2"/>') . '</w:tr>'
            . '<w:tr>' . $cell('', '<w:vMerge/>') . $cell('1 hr') . $cell('Plan') . '</w:tr>'
            . '</w:tbl>'
            // Links: external, and one that must never survive
            . $p('<w:hyperlink r:id="rIdWeb">' . $run('Kevin\'s site', '<w:rStyle w:val="Hyperlink"/>') . '</w:hyperlink>' . $run(' and ') . '<w:hyperlink r:id="rIdJs">' . $run('bad link') . '</w:hyperlink>');

        $files = [
            '[Content_Types].xml' => '<?xml version="1.0"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"/>',
            'word/document.xml' => "<?xml version=\"1.0\"?><w:document {$W}><w:body>{$body}<w:sectPr/></w:body></w:document>",
            'word/_rels/document.xml.rels' => '<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rIdImg" Type="image" Target="media/image1.png"/>'
                . '<Relationship Id="rIdEmf" Type="image" Target="media/image2.emf"/>'
                . '<Relationship Id="rIdWeb" Type="hyperlink" Target="https://example.org/" TargetMode="External"/>'
                . '<Relationship Id="rIdJs" Type="hyperlink" Target="javascript:alert(1)" TargetMode="External"/>'
                . '</Relationships>',
            'word/styles.xml' => "<?xml version=\"1.0\"?><w:styles {$W}>"
                . '<w:style w:type="paragraph" w:styleId="Normal"><w:name w:val="Normal"/></w:style>'
                . '<w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:pPr><w:numPr><w:numId w:val="1"/></w:numPr><w:outlineLvl w:val="0"/></w:pPr></w:style>'
                . '<w:style w:type="paragraph" w:styleId="Heading2"><w:name w:val="heading 2"/><w:basedOn w:val="Heading1"/><w:pPr><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:outlineLvl w:val="1"/></w:pPr></w:style>'
                . '<w:style w:type="paragraph" w:styleId="TOC1"><w:name w:val="toc 1"/></w:style>'
                . '<w:style w:type="paragraph" w:styleId="Caption"><w:name w:val="caption"/></w:style>'
                . '<w:style w:type="paragraph" w:styleId="ListParagraph"><w:name w:val="List Paragraph"/></w:style>'
                . '<w:style w:type="character" w:styleId="Hyperlink"><w:name w:val="Hyperlink"/><w:rPr><w:u w:val="single"/></w:rPr></w:style>'
                . '</w:styles>',
            'word/numbering.xml' => "<?xml version=\"1.0\"?><w:numbering {$W}>"
                . '<w:abstractNum w:abstractNumId="0"><w:lvl w:ilvl="0"><w:start w:val="1"/><w:numFmt w:val="decimal"/><w:lvlText w:val="%1"/></w:lvl><w:lvl w:ilvl="1"><w:start w:val="1"/><w:numFmt w:val="decimal"/><w:lvlText w:val="%1.%2"/></w:lvl></w:abstractNum>'
                . '<w:abstractNum w:abstractNumId="1"><w:lvl w:ilvl="0"><w:numFmt w:val="bullet"/><w:lvlText w:val="•"/></w:lvl><w:lvl w:ilvl="1"><w:numFmt w:val="bullet"/><w:lvlText w:val="o"/></w:lvl></w:abstractNum>'
                . '<w:abstractNum w:abstractNumId="2"><w:lvl w:ilvl="0"><w:start w:val="1"/><w:numFmt w:val="lowerLetter"/><w:lvlText w:val="%1)"/></w:lvl></w:abstractNum>'
                . '<w:num w:numId="1"><w:abstractNumId w:val="0"/></w:num><w:num w:numId="2"><w:abstractNumId w:val="1"/></w:num><w:num w:numId="3"><w:abstractNumId w:val="2"/></w:num>'
                . '</w:numbering>',
            'word/endnotes.xml' => "<?xml version=\"1.0\"?><w:endnotes {$W}>"
                . '<w:endnote w:type="separator" w:id="0"><w:p><w:r><w:separator/></w:r></w:p></w:endnote>'
                . '<w:endnote w:id="2"><w:p><w:r><w:endnoteRef/></w:r>' . $run(' Royce, 1970.') . '</w:p></w:endnote>'
                . '</w:endnotes>',
            'word/media/image1.png' => base64_decode(self::PNG),
            'word/media/image2.emf' => 'EMF-BYTES',
        ];

        $path = tempnam(sys_get_temp_dir(), 'docx') . '.docx';
        $zip  = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();

        return $path;
    }

    private function convert(?string $body = null): array
    {
        $stored = [];
        $result = (new DocxToHtml(function ($bytes, $ext) use (&$stored) {
            $stored[] = [$bytes, $ext];

            return '/storage/blog/content-images/img' . count($stored) . '.' . $ext;
        }))->convert($this->docx($body));

        $result['stored'] = $stored;

        return $result;
    }

    // ── The converter ────────────────────────────────────────────────────

    public function test_numbered_headings_shift_down_a_level_and_keep_their_word_numbers(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<h2 id="_Toc100">1 Introduction</h2>', $html);
        $this->assertStringContainsString('<h3>1.1 Details</h3>', $html);
        $this->assertStringNotContainsString('<h1', $html, 'The page title must stay the only H1');
    }

    public function test_table_of_contents_links_work_and_lose_their_page_numbers(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<p class="doc-toc doc-toc-1"><a href="#_Toc100">1 Introduction</a></p>', $html);
        $this->assertStringNotContainsString('PAGEREF', $html);
        $this->assertStringNotContainsString('<u>', $html, 'Links must not carry a second underline');
    }

    public function test_cover_page_text_keeps_its_weight_without_becoming_headings(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<p class="doc-cover-title" style="text-align: right">Agile Processes for Hardware Development</p>', $html);
        $this->assertStringContainsString('<p class="doc-cover-subtitle" style="text-align: right">AND WHAT TO DO ABOUT IT</p>', $html);
        $this->assertStringNotContainsString('<p></p>', $html, 'Empty spacer paragraphs are dropped');
    }

    public function test_inline_formatting_is_kept_and_merged_and_hidden_text_is_dropped(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<p>Plain, <strong>bold and bold</strong> and <em>italic</em> text.</p>', $html);
        $this->assertStringNotContainsString('hidden', $html);
    }

    public function test_lists_nest_and_keep_their_numbering_style(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<ul><li>First bullet<ul><li>Nested bullet</li></ul></li><li>Second bullet</li></ul>', $html);
        $this->assertStringContainsString('<ol type="a"><li>Step one</li><li>Step two</li></ol>', $html);
    }

    public function test_pictures_are_stored_sized_and_described_by_their_caption(): void
    {
        $result = $this->convert();

        $this->assertSame(1, $result['images']);
        $this->assertSame([base64_decode(self::PNG), 'png'], $result['stored'][0]);
        $this->assertStringContainsString('<p><img src="/storage/blog/content-images/img1.png" alt="Figure 1: Release plan" width="624" height="312"></p>', $result['html']);
        $this->assertStringContainsString('<p id="_Ref200" class="doc-caption">Figure 1: Release plan</p>', $result['html']);
    }

    public function test_cross_references_and_endnotes_link_both_ways(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('See <a href="#_Ref200"><strong>Figure 1</strong></a> for details.<sup><a href="#endnote-1" id="endnote-ref-1">[1]</a></sup>', $html);
        $this->assertStringContainsString('<section class="doc-notes"><h2>Notes</h2><ol><li id="endnote-1"><p>Royce, 1970. <a href="#endnote-ref-1" class="doc-note-back" aria-label="Back to the text">↑</a></p></li></ol></section>', $html);
    }

    public function test_a_visio_emf_picture_is_flagged_not_lost_and_named_by_its_caption(): void
    {
        $result = $this->convert();

        $this->assertStringContainsString('<p class="doc-import-missing">⚠ Picture not imported (EMF format).', $result['html']);
        $this->assertContains('Picture not imported (EMF format) — Figure 2: Release cycle', $result['warnings']);
        $this->assertCount(1, $result['stored'], 'The EMF file itself is never stored');
    }

    public function test_a_text_box_stored_twice_by_word_appears_once(): void
    {
        $html = $this->convert()['html'];

        $this->assertSame(1, substr_count($html, 'Policies about Quality Assurance'));
        $this->assertStringContainsString('<aside class="doc-textbox"><p><strong>Policies about Quality Assurance</strong></p>', $html);
    }

    public function test_tables_keep_header_rows_and_merged_cells(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<table class="doc-table"><thead><tr><th style="background-color: #1a2332; color: #ffffff">Ceremony</th>', $html);
        $this->assertStringContainsString('<td rowspan="2">Sprint Planning</td><td colspan="2">2 hours per week of Sprint</td>', $html);
        $this->assertStringContainsString('<tr><td>1 hr</td><td>Plan</td></tr>', $html);
    }

    public function test_safe_links_survive_and_script_links_are_dropped(): void
    {
        $html = $this->convert()['html'];

        $this->assertStringContainsString('<a href="https://example.org/">Kevin&apos;s site</a>', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringContainsString(' and bad link</p>', $html);
    }

    public function test_a_damaged_file_gives_a_clear_message(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'bad') . '.docx';
        file_put_contents($path, substr(file_get_contents($this->docx()), 0, 300)); // cut off, like the first sample upload

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('It may be damaged or incomplete');

        (new DocxToHtml(fn () => ''))->convert($path);
    }

    public function test_the_clients_real_paper_converts_cleanly(): void
    {
        $file = base_path('Common Pathologies in Hardware Product Development-v3.docx');
        if (! is_file($file)) {
            $this->markTestSkipped('Client sample not present on this machine.');
        }

        $result = (new DocxToHtml(fn ($b, $ext) => '/storage/x.' . $ext))->convert($file);
        $html   = $result['html'];

        $this->assertSame(3, $result['images']);
        $this->assertStringContainsString('<h2 id="_Toc239231731">1 Introduction</h2>', $html);
        $this->assertStringContainsString('<a href="#_Ref238809230">1.1 The Recurring Pattern</a>', $html, 'Contents link points at the heading id');
        $this->assertStringContainsString('<h3 id="_Ref238809230">1.1 The Recurring Pattern</h3>', $html);
        $this->assertStringNotContainsString('<h1', $html);
        $this->assertStringNotContainsString('PAGEREF', $html);
        $this->assertDoesNotMatchRegularExpression('#<p[^>]*>\s*</p>#', $html);
    }

    // ── The endpoint ─────────────────────────────────────────────────────

    public function test_import_endpoint_converts_and_stores_the_pictures(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $res = $this->postJson(route('admin.word-import'), [
            'file' => new UploadedFile($this->docx(), 'paper.docx', null, null, true),
        ])->assertOk()->assertJsonPath('images', 1);

        preg_match('#src="(/storage/blog/content-images/[^"]+\.png)"#', $res->json('html'), $m);
        $this->assertNotEmpty($m, 'The picture URL is in the returned HTML');
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $m[1]));
        $this->assertContains('Picture not imported (EMF format) — Figure 2: Release cycle', $res->json('warnings'));
    }

    public function test_import_endpoint_rejects_other_files_and_damaged_documents(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->postJson(route('admin.word-import'), [
            'file' => UploadedFile::fake()->createWithContent('paper.doc', 'old word'),
        ])->assertStatus(422)->assertJsonPath('message', fn ($m) => str_contains($m, 'Only Word .docx files'));

        $this->postJson(route('admin.word-import'), [
            'file' => UploadedFile::fake()->createWithContent('paper.docx', 'not a zip'),
        ])->assertStatus(422)->assertJsonPath('message', fn ($m) => str_contains($m, 'damaged or incomplete'));

        $this->postJson(route('admin.word-import'), [])->assertStatus(422);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_import_endpoint_is_admin_only(): void
    {
        $file = new UploadedFile($this->docx(), 'paper.docx', null, null, true);

        $this->postJson(route('admin.word-import'), ['file' => $file])->assertUnauthorized();

        $this->actingAs(User::factory()->create());
        $this->postJson(route('admin.word-import'), ['file' => $file])->assertForbidden();
    }

    // ── The editors ──────────────────────────────────────────────────────

    public function test_both_editors_offer_import_from_word_with_matching_content_styles(): void
    {
        $this->actingAs($this->admin());

        foreach ([ManagePapers::class, BlogPosts::class] as $component) {
            Livewire::test($component)
                ->call('create')
                ->assertSeeHtml('data-word-import-scope')
                ->assertSeeHtml('data-import-url="' . route('admin.word-import') . '"')
                ->assertSeeHtml('css/admin/editor-content.css')
                ->assertSee('Import from Word');
        }

        $this->get(route('admin.papers.index'))->assertOk()->assertSee('js/admin/word-import.js', false);
    }

    // ── Editor image upload route (also used for pasted pictures) ────────

    public function test_editor_upload_route_accepts_only_web_pictures(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->postJson(route('admin.tinymce.upload'), [
            'file' => UploadedFile::fake()->createWithContent('x.png', base64_decode(self::PNG)),
        ])->assertOk()->assertJsonPath('location', fn ($l) => str_starts_with($l, '/storage/blog/content-images/'));

        foreach ([
            UploadedFile::fake()->createWithContent('drawing.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
        ] as $bad) {
            $this->postJson(route('admin.tinymce.upload'), ['file' => $bad])->assertStatus(422);
        }

        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    // ── Saved and published ──────────────────────────────────────────────

    public function test_an_imported_paper_survives_saving_and_reads_well_publicly(): void
    {
        $this->actingAs($this->admin());
        $category = Category::create(['name' => 'White Papers', 'slug' => 'white-papers', 'type' => 'paper']);
        $imported = $this->convert()['html'];

        Livewire::test(ManagePapers::class)
            ->call('create')
            ->set('title', 'Agile Processes for Hardware Development')
            ->set('category_id', $category->id)
            ->set('description', '<p>Card summary.</p>')
            ->set('html_content', $imported)
            ->call('save')
            ->assertHasNoErrors();

        $saved = Paper::firstOrFail()->html_content;

        // The Word paste cleaner must not undo what the importer produced.
        foreach (['class="doc-toc doc-toc-1"', 'class="doc-caption"', 'class="doc-notes"', 'class="doc-textbox"', 'id="_Toc100"', 'rowspan="2"', 'style="text-align: right"', 'background-color: #1a2332'] as $kept) {
            $this->assertStringContainsString($kept, $saved, "Lost on save: {$kept}");
        }

        $html = $this->get(route('papers.read', Paper::first()->slug))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), 'Reading page must have exactly one H1');
        $this->assertStringContainsString('<h2 id="_Toc100">1 Introduction</h2>', $html);
        $this->assertStringContainsString('<h2>Notes</h2>', $html);

        // The cover title repeats the H1, and the "picture not imported" note is
        // for the editor only — neither reaches readers.
        $this->assertStringNotContainsString('doc-cover-title', $html);
        $this->assertStringNotContainsString('Picture not imported', $html);
        $this->assertStringContainsString('Picture not imported', $saved, 'The note stays in the editor until fixed');
    }
}
