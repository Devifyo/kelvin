<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use RuntimeException;
use ZipArchive;

/**
 * Converts a Word .docx file into clean, semantic HTML for the site's editors
 * ("Import from Word" in the Resource Library and Blog admin).
 *
 * Kept: headings (shifted down one level so the page title stays the only H1)
 * with their Word numbering, paragraphs and alignment, bold/italic/underline/
 * strike/super/subscript, links, bookmarks (so a Word table of contents keeps
 * working), numbered and bulleted lists with nesting, tables with merged
 * cells, header rows and shading, pictures at their Word size, captions, text
 * boxes, quotes, footnotes and endnotes.
 *
 * Dropped on purpose: fonts, font sizes, colours of body text, page layout,
 * headers/footers and table-of-contents page numbers — none of it belongs on
 * a web page, and the site's stylesheet supplies the typography.
 *
 * Pictures are handed to $storeImage(bytes, extension) which returns their
 * public URL. Formats a browser cannot display (EMF/WMF from Visio, TIFF …)
 * become a visible placeholder and are listed in warnings().
 */
class DocxToHtml
{
    private const W   = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    private const R   = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    private const WP  = 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing';
    private const A   = 'http://schemas.openxmlformats.org/drawingml/2006/main';
    private const V   = 'urn:schemas-microsoft-com:vml';
    private const MC  = 'http://schemas.openxmlformats.org/markup-compatibility/2006';
    private const M   = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    private const PKG = 'http://schemas.openxmlformats.org/package/2006/relationships';

    private const WEB_IMAGES = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    private const EMU_PER_PX = 9525;
    private const INLINE_IMAGE_MAX_WIDTH = 200;

    private ZipArchive $zip;
    /** @var callable(string,string):string */
    private $storeImage;

    private array $styles = [];      // styleId => [name, basedOn, type, numId, ilvl, outline, b, i, u, strike, vert]
    private array $numToAbstract = [];
    private array $levels = [];      // abstractId => ilvl => [fmt, text, start]
    private array $counters = [];    // numId => ilvl => count
    private array $rels = [];        // part => rId => [target, external]
    private array $notes = ['footnote' => [], 'endnote' => []];
    private array $noteOrder = [];   // [type, id, number]
    private array $noteNumber = ['footnote' => 0, 'endnote' => 0];
    private array $imageUrls = [];   // zip path => url
    private array $warnings = [];
    private array $aliases = [];     // bookmark name => element id it now lives on
    private array $pendingIds = [];  // bookmarks waiting for the next block
    private array $fieldStack = [];
    private array $deferred = [];    // text boxes / floating pictures emitted after the paragraph
    private ?int $missingWithoutCaption = null;
    private int $imageCount = 0;
    private bool $pastCover = false; // set once the first heading / contents entry is written
    private string $part = 'word/document.xml';

    /**
     * Removes the editor-only "⚠ Picture not imported" notes from HTML that
     * is about to be shown to readers. They exist so the admin notices the
     * gap in the editor; visitors should never see them.
     */
    public static function withoutImportNotes(string $html): string
    {
        return preg_replace('#<p\b[^>]*class="doc-import-missing"[^>]*>.*?</p>\s*#s', '', $html) ?? $html;
    }

    public function __construct(callable $storeImage)
    {
        $this->storeImage = $storeImage;
    }

    /** @return array{html: string, images: int, warnings: list<string>} */
    public function convert(string $path): array
    {
        $this->zip = new ZipArchive();

        if ($this->zip->open($path) !== true || $this->zip->locateName('word/document.xml') === false) {
            throw new RuntimeException('This file could not be opened as a Word document. It may be damaged or incomplete. Open it in Word, save it again as .docx, and retry.');
        }

        try {
            $this->loadStyles();
            $this->loadNumbering();
            $this->loadNotes();

            $doc  = $this->xml('word/document.xml');
            $body = $doc ? $this->child($doc->documentElement, 'body') : null;

            if (! $body) {
                throw new RuntimeException('No document body was found in this file.');
            }

            $html = $this->blocks($body) . $this->notesSection();
            $html = $this->resolveAliases($html);
            $html = $this->finish($html);

            return ['html' => trim($html), 'images' => $this->imageCount, 'warnings' => $this->warnings];
        } finally {
            $this->zip->close();
        }
    }

    // ── Package parts ────────────────────────────────────────────────────

    private function xml(string $name): ?DOMDocument
    {
        $raw = $this->zip->getFromName($name);
        if ($raw === false || $raw === '') {
            return null;
        }

        $doc = new DOMDocument();
        $ok  = $doc->loadXML($raw, LIBXML_NONET | LIBXML_COMPACT | LIBXML_PARSEHUGE | LIBXML_NOWARNING | LIBXML_NOERROR);

        return $ok ? $doc : null;
    }

    private function relsFor(string $part): array
    {
        if (isset($this->rels[$part])) {
            return $this->rels[$part];
        }

        $dir  = dirname($part);
        $file = $dir . '/_rels/' . basename($part) . '.rels';
        $out  = [];

        if ($doc = $this->xml($file)) {
            foreach ($doc->getElementsByTagNameNS(self::PKG, 'Relationship') as $rel) {
                $target   = $rel->getAttribute('Target');
                $external = $rel->getAttribute('TargetMode') === 'External';
                if (! $external) {
                    $target = $this->normalizePath(str_starts_with($target, '/') ? ltrim($target, '/') : $dir . '/' . $target);
                }
                $out[$rel->getAttribute('Id')] = [$target, $external];
            }
        }

        return $this->rels[$part] = $out;
    }

    private function normalizePath(string $path): string
    {
        $parts = [];
        foreach (explode('/', $path) as $seg) {
            if ($seg === '..') {
                array_pop($parts);
            } elseif ($seg !== '.' && $seg !== '') {
                $parts[] = $seg;
            }
        }

        return implode('/', $parts);
    }

    private function loadStyles(): void
    {
        $doc = $this->xml('word/styles.xml');
        if (! $doc) {
            return;
        }

        foreach ($doc->getElementsByTagNameNS(self::W, 'style') as $s) {
            $id  = $s->getAttributeNS(self::W, 'styleId');
            $pPr = $this->child($s, 'pPr');
            $rPr = $this->child($s, 'rPr');
            $num = $pPr ? $this->child($pPr, 'numPr') : null;

            $this->styles[$id] = [
                'name'    => strtolower(trim((string) $this->val($this->child($s, 'name')))),
                'basedOn' => $this->val($this->child($s, 'basedOn')),
                'type'    => $s->getAttributeNS(self::W, 'type'),
                'numId'   => $num ? $this->val($this->child($num, 'numId')) : null,
                'ilvl'    => $num ? $this->val($this->child($num, 'ilvl')) : null,
                'outline' => $pPr ? $this->val($this->child($pPr, 'outlineLvl')) : null,
                'b'       => $rPr && $this->child($rPr, 'b') ? $this->toggle($this->child($rPr, 'b')) : null,
                'i'       => $rPr && $this->child($rPr, 'i') ? $this->toggle($this->child($rPr, 'i')) : null,
                'u'       => $rPr ? $this->underline($this->child($rPr, 'u')) : null,
                'strike'  => $rPr && $this->child($rPr, 'strike') ? $this->toggle($this->child($rPr, 'strike')) : null,
                'dstrike' => $rPr && $this->child($rPr, 'dstrike') ? $this->toggle($this->child($rPr, 'dstrike')) : null,
                'vert'    => $rPr ? $this->val($this->child($rPr, 'vertAlign')) : null,
            ];
        }
    }

    /** First non-null value of $key along the style's basedOn chain. */
    private function styleProp(?string $id, string $key): mixed
    {
        for ($guard = 0; $id !== null && isset($this->styles[$id]) && $guard < 20; $guard++) {
            if (($this->styles[$id][$key] ?? null) !== null) {
                return $this->styles[$id][$key];
            }
            $id = $this->styles[$id]['basedOn'];
        }

        return null;
    }

    /** Style names along the chain, own name first. */
    private function styleNames(?string $id): array
    {
        $names = [];
        for ($guard = 0; $id !== null && isset($this->styles[$id]) && $guard < 20; $guard++) {
            $names[] = $this->styles[$id]['name'];
            $id      = $this->styles[$id]['basedOn'];
        }

        return $names;
    }

    private function loadNumbering(): void
    {
        $doc = $this->xml('word/numbering.xml');
        if (! $doc) {
            return;
        }

        foreach ($doc->getElementsByTagNameNS(self::W, 'abstractNum') as $abs) {
            $aid = $abs->getAttributeNS(self::W, 'abstractNumId');
            foreach ($this->children($abs, 'lvl') as $lvl) {
                $this->levels[$aid][(int) $lvl->getAttributeNS(self::W, 'ilvl')] = [
                    'fmt'   => $this->val($this->child($lvl, 'numFmt')) ?? 'decimal',
                    'text'  => $this->val($this->child($lvl, 'lvlText')) ?? '',
                    'start' => (int) ($this->val($this->child($lvl, 'start')) ?? 1),
                    'legal' => $this->child($lvl, 'isLgl') !== null,
                ];
            }
        }

        foreach ($doc->getElementsByTagNameNS(self::W, 'num') as $num) {
            if ($num->parentNode !== $doc->documentElement) {
                continue;
            }
            $this->numToAbstract[$num->getAttributeNS(self::W, 'numId')] = $this->val($this->child($num, 'abstractNumId'));
        }
    }

    private function loadNotes(): void
    {
        foreach (['footnote' => 'word/footnotes.xml', 'endnote' => 'word/endnotes.xml'] as $type => $file) {
            if (! $doc = $this->xml($file)) {
                continue;
            }
            foreach ($doc->getElementsByTagNameNS(self::W, $type) as $note) {
                $kind = $note->getAttributeNS(self::W, 'type');
                if ($kind === '' || $kind === 'normal') {
                    $this->notes[$type][$note->getAttributeNS(self::W, 'id')] = $note;
                }
            }
        }
    }

    // ── Block level ──────────────────────────────────────────────────────

    /** Converts the block children of a container (body, cell, text box, note). */
    private function blocks(DOMNode $container): string
    {
        $out   = '';
        $lists = []; // stack of [tag, ilvl, numId]

        $closeLists = function (int $toDepth = 0) use (&$lists, &$out) {
            while (count($lists) > $toDepth) {
                $l = array_pop($lists);
                $out .= '</li></' . $l['tag'] . '>';
            }
        };

        foreach ($this->blockNodes($container) as $node) {
            if ($node->localName === 'bookmarkStart') {
                $this->rememberBookmark($node);
                continue;
            }

            if ($node->localName === 'tbl') {
                $closeLists();
                $out .= $this->table($node);
                continue;
            }

            // Paragraph
            $p = $this->paragraph($node);

            if ($p === null) { // empty paragraph
                continue;
            }

            if ($p['kind'] === 'list') {
                // Close deeper lists, or a sibling list of a different kind.
                while ($lists && end($lists)['ilvl'] > $p['ilvl']) {
                    $closeLists(count($lists) - 1);
                }
                $top = $lists ? end($lists) : null;

                if ($top && $top['ilvl'] === $p['ilvl'] && ($top['tag'] !== $p['tag'] || $top['numId'] !== $p['numId'])) {
                    $closeLists(count($lists) - 1);
                    $top = $lists ? end($lists) : null;
                }

                if ($top && $top['ilvl'] === $p['ilvl']) {
                    $out .= '</li><li' . $p['idAttr'] . '>';
                } else {
                    $out .= '<' . $p['tag'] . $p['listAttrs'] . '><li' . $p['idAttr'] . '>';
                    $lists[] = ['tag' => $p['tag'], 'ilvl' => $p['ilvl'], 'numId' => $p['numId']];
                }
                $out .= $p['inner'];
                $extra = $this->flushDeferred();
                if ($extra !== '') {
                    $out .= $extra;
                }
                continue;
            }

            $closeLists();
            $out .= $p['html'] . "\n" . $this->flushDeferred();
        }

        $closeLists();

        // Consecutive quote paragraphs read as one quotation.
        return str_replace("</blockquote>\n<blockquote>", '', $out);
    }

    /** Paragraphs, tables and body-level bookmarks, unwrapping content controls. */
    private function blockNodes(DOMNode $container): array
    {
        $nodes = [];
        foreach ($container->childNodes as $n) {
            if (! $n instanceof DOMElement) {
                continue;
            }
            if ($n->namespaceURI === self::W && in_array($n->localName, ['p', 'tbl', 'bookmarkStart'], true)) {
                $nodes[] = $n;
            } elseif ($n->namespaceURI === self::W && in_array($n->localName, ['sdt', 'customXml', 'ins', 'smartTag'], true)) {
                $inner = $n->localName === 'sdt' ? $this->child($n, 'sdtContent') : $n;
                if ($inner) {
                    array_push($nodes, ...$this->blockNodes($inner));
                }
            } elseif ($n->namespaceURI === self::MC && $n->localName === 'AlternateContent') {
                $pick = $this->firstChildNS($n, self::MC, 'Choice') ?? $this->firstChildNS($n, self::MC, 'Fallback');
                if ($pick) {
                    array_push($nodes, ...$this->blockNodes($pick));
                }
            }
        }

        return $nodes;
    }

    private function rememberBookmark(DOMElement $b): void
    {
        $name = $b->getAttributeNS(self::W, 'name');
        if ($name !== '' && $name !== '_GoBack') {
            $this->pendingIds[] = $name;
        }
    }

    /** Bookmarks collected for the block being written become its id. */
    private function takeIdAttr(): string
    {
        if (! $this->pendingIds) {
            return '';
        }
        $id = array_shift($this->pendingIds);
        foreach ($this->pendingIds as $alias) {
            $this->aliases[$alias] = $id;
        }
        $this->pendingIds = [];

        return ' id="' . $this->e($id) . '"';
    }

    /**
     * @return null|array{kind:string, html?:string, inner?:string, tag?:string, ilvl?:int, numId?:string, listAttrs?:string, idAttr:string}
     */
    private function paragraph(DOMElement $p): ?array
    {
        $pPr     = $this->child($p, 'pPr');
        $styleId = $pPr ? $this->val($this->child($pPr, 'pStyle')) : null;
        $names   = $this->styleNames($styleId);
        $name    = $names[0] ?? '';

        // Paragraph marks deleted in tracked changes or hidden.
        $numPr = $pPr ? $this->child($pPr, 'numPr') : null;
        $numId = $numPr ? $this->val($this->child($numPr, 'numId')) : null;
        $ilvl  = $numPr ? $this->val($this->child($numPr, 'ilvl')) : null;
        $numId ??= $this->styleProp($styleId, 'numId');
        $ilvl  ??= $this->styleProp($styleId, 'ilvl');
        $ilvl    = (int) ($ilvl ?? 0);
        $numbered = $numId !== null && $numId !== '0' && isset($this->numToAbstract[$numId]);

        $level = $this->headingLevel($names, $styleId, $pPr);
        $inner = $this->inline($p) . $this->closeFieldLinks();
        $inner = $this->tidyInline($inner);

        $hasContent = trim(strip_tags($inner, '<img><br>')) !== '' || str_contains($inner, '<img');

        if (! $hasContent) {
            // Bookmarks on an empty paragraph move to the next block; a text
            // box or floating picture inside it is still emitted.
            if ($this->deferred) {
                return ['kind' => 'block', 'html' => '', 'idAttr' => ''];
            }
            $this->bumpCounter($numbered ? $numId : null, $ilvl, false);

            return null;
        }

        $align = $this->alignStyle($pPr);

        if ($level !== null) {
            $prefix = $numbered ? $this->numberText($numId, $ilvl) : '';
            $text   = preg_replace('#</?(strong|em)>#', '', $inner);
            $text   = ($prefix !== '' && ! str_starts_with(trim(strip_tags($text)), $prefix)) ? $prefix . ' ' . ltrim($text) : $text;
            $tag    = 'h' . min(6, $level + 1);
            $this->pastCover = true;

            return ['kind' => 'block', 'html' => "<{$tag}" . $this->takeIdAttr() . ">{$text}</{$tag}>", 'idAttr' => ''];
        }

        $idAttr = $this->takeIdAttr();

        if ($numbered && ! $this->isAny($names, ['toc', 'table of figures', 'caption'])) {
            $lvl  = $this->levelDef($numId, $ilvl);
            $fmt  = $lvl['fmt'] ?? 'decimal';
            $this->bumpCounter($numId, $ilvl, false);

            if ($fmt === 'none') {
                return ['kind' => 'block', 'html' => "<p{$idAttr}{$align}>{$inner}</p>", 'idAttr' => ''];
            }

            $tag   = $fmt === 'bullet' ? 'ul' : 'ol';
            $attrs = '';
            if ($tag === 'ol') {
                $type = ['lowerLetter' => 'a', 'upperLetter' => 'A', 'lowerRoman' => 'i', 'upperRoman' => 'I'][$fmt] ?? null;
                if ($type) {
                    $attrs .= ' type="' . $type . '"';
                }
                $start = $this->counters[$numId][$ilvl] ?? 1;
                if ($start !== 1) {
                    $attrs .= ' start="' . $start . '"';
                }
            }

            return ['kind' => 'list', 'tag' => $tag, 'ilvl' => $ilvl, 'numId' => (string) $numId, 'listAttrs' => $attrs, 'inner' => $inner, 'idAttr' => $idAttr];
        }

        // A picture whose paragraph carries the Caption style is a figure, not a caption.
        if ($this->isAny($names, ['caption']) && trim(strip_tags($inner)) !== '') {
            if ($this->missingWithoutCaption !== null) {
                $this->warnings[$this->missingWithoutCaption] .= ' — ' . trim(html_entity_decode(strip_tags($inner), ENT_QUOTES | ENT_HTML5));
                $this->missingWithoutCaption = null;
            }

            return ['kind' => 'block', 'html' => "<p{$idAttr} class=\"doc-caption\">{$inner}</p>", 'idAttr' => ''];
        }

        if (preg_match('#^toc (\d)$#', $name, $m) || $name === 'table of figures') {
            $depth = isset($m[1]) ? (int) $m[1] : 1;
            $this->pastCover = true;

            return ['kind' => 'block', 'html' => "<p{$idAttr} class=\"doc-toc doc-toc-{$depth}\">{$inner}</p>", 'idAttr' => ''];
        }

        if ($this->isAny($names, ['toc heading'])) {
            return ['kind' => 'block', 'html' => "<h2{$idAttr}>{$inner}</h2>", 'idAttr' => ''];
        }

        if ($name === 'subtitle') {
            return ['kind' => 'block', 'html' => "<p{$idAttr} class=\"doc-subtitle\"{$align}>{$inner}</p>", 'idAttr' => ''];
        }

        if ($this->isAny($names, ['quote', 'intense quote', 'block text'])) {
            return ['kind' => 'block', 'html' => "<blockquote><p{$idAttr}>{$inner}</p></blockquote>", 'idAttr' => ''];
        }

        // "Contents", "List of Figures"… are section headings in all but style.
        $plain = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES | ENT_HTML5));
        if (preg_match('#^(table of contents|contents|list of (figures|tables|illustrations)|table of figures)$#i', $plain)) {
            $this->pastCover = true;

            return ['kind' => 'block', 'html' => "<h2{$idAttr}>" . $this->e($plain) . '</h2>', 'idAttr' => ''];
        }

        // Cover-page lines Word made large by hand (title, subtitle) keep
        // their visual weight without becoming headings — on the cover only.
        $size = $this->pastCover ? 0 : $this->largestFontSize($p);
        if ($size >= 28 && mb_strlen(strip_tags($inner)) < 200) {
            return ['kind' => 'block', 'html' => "<p{$idAttr} class=\"doc-cover-title\"{$align}>" . preg_replace('#</?strong>#', '', $inner) . '</p>', 'idAttr' => ''];
        }
        if ($size >= 14 && mb_strlen(strip_tags($inner)) < 200) {
            return ['kind' => 'block', 'html' => "<p{$idAttr} class=\"doc-cover-subtitle\"{$align}>{$inner}</p>", 'idAttr' => ''];
        }

        return ['kind' => 'block', 'html' => "<p{$idAttr}{$align}>{$inner}</p>", 'idAttr' => ''];
    }

    /** Largest directly-set font size in the paragraph, in points (0 when none). */
    private function largestFontSize(DOMElement $p): float
    {
        $max = 0;
        foreach ($this->xpath($p)->query('./w:r/w:rPr/w:sz | ./w:hyperlink/w:r/w:rPr/w:sz', $p) as $sz) {
            $max = max($max, (int) $sz->getAttributeNS(self::W, 'val'));
        }

        return $max / 2; // half-points
    }

    /** Word heading level 1–9 (Title counts as 1), or null. */
    private function headingLevel(array $names, ?string $styleId, ?DOMElement $pPr): ?int
    {
        foreach ($names as $n) {
            if (preg_match('#^heading (\d)$#', $n, $m)) {
                return (int) $m[1];
            }
            if ($n === 'title') {
                return 1;
            }
            if (preg_match('#^(toc|table of figures|caption|list paragraph|normal)#', $n)) {
                return null;
            }
        }

        $outline = $pPr ? $this->val($this->child($pPr, 'outlineLvl')) : null;
        $outline ??= $this->styleProp($styleId, 'outline');

        return ($outline !== null && (int) $outline < 9) ? (int) $outline + 1 : null;
    }

    private function isAny(array $names, array $wanted): bool
    {
        return (bool) array_intersect($names, $wanted);
    }

    private function alignStyle(?DOMElement $pPr): string
    {
        $jc = $pPr ? $this->val($this->child($pPr, 'jc')) : null;

        return match ($jc) {
            'center'         => ' style="text-align: center"',
            'right', 'end'   => ' style="text-align: right"',
            default          => '',
        };
    }

    // ── Numbering ────────────────────────────────────────────────────────

    private function levelDef(?string $numId, int $ilvl): ?array
    {
        $aid = $this->numToAbstract[$numId] ?? null;

        return $aid !== null ? ($this->levels[$aid][$ilvl] ?? null) : null;
    }

    /** Advances Word's counter for (numId, ilvl) and resets deeper levels. */
    private function bumpCounter(?string $numId, int $ilvl, bool $skip): void
    {
        if ($numId === null || $skip) {
            return;
        }
        $start = $this->levelDef($numId, $ilvl)['start'] ?? 1;
        $this->counters[$numId][$ilvl] = isset($this->counters[$numId][$ilvl]) ? $this->counters[$numId][$ilvl] + 1 : $start;
        foreach (array_keys($this->counters[$numId]) as $l) {
            if ($l > $ilvl) {
                unset($this->counters[$numId][$l]);
            }
        }
    }

    /** "3.1" for a numbered heading, built from Word's level text. */
    private function numberText(string $numId, int $ilvl): string
    {
        $this->bumpCounter($numId, $ilvl, false);
        $def = $this->levelDef($numId, $ilvl);
        if (! $def || $def['fmt'] === 'bullet' || $def['fmt'] === 'none') {
            return '';
        }

        $text = preg_replace_callback('#%(\d)#', function ($m) use ($numId, $ilvl, $def) {
            $l     = (int) $m[1] - 1;
            $count = $this->counters[$numId][$l] ?? ($this->levelDef($numId, $l)['start'] ?? 1);
            $fmt   = $def['legal'] ? 'decimal' : ($this->levelDef($numId, $l)['fmt'] ?? 'decimal');

            return $this->formatNumber($count, $fmt);
        }, $def['text']);

        return trim(rtrim(trim($text), '.'));
    }

    private function formatNumber(int $n, string $fmt): string
    {
        return match ($fmt) {
            'lowerLetter' => $this->letters($n),
            'upperLetter' => strtoupper($this->letters($n)),
            'lowerRoman'  => strtolower($this->roman($n)),
            'upperRoman'  => $this->roman($n),
            default       => (string) $n,
        };
    }

    private function letters(int $n): string
    {
        $s = '';
        for (; $n > 0; $n = intdiv($n - 1, 26)) {
            $s = chr(97 + ($n - 1) % 26) . $s;
        }

        return $s;
    }

    private function roman(int $n): string
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $s   = '';
        foreach ($map as $r => $v) {
            for (; $n >= $v; $n -= $v) {
                $s .= $r;
            }
        }

        return $s;
    }

    // ── Inline level ─────────────────────────────────────────────────────

    private function inline(DOMNode $node): string
    {
        $out = '';

        foreach ($node->childNodes as $n) {
            if (! $n instanceof DOMElement) {
                continue;
            }

            if ($n->namespaceURI === self::M && in_array($n->localName, ['oMath', 'oMathPara'], true)) {
                $out .= $this->math($n);
                continue;
            }

            if ($n->namespaceURI === self::MC && $n->localName === 'AlternateContent') {
                $pick = $this->firstChildNS($n, self::MC, 'Choice') ?? $this->firstChildNS($n, self::MC, 'Fallback');
                $out .= $pick ? $this->inline($pick) : '';
                continue;
            }

            if ($n->namespaceURI !== self::W) {
                continue;
            }

            switch ($n->localName) {
                case 'r':
                    $out .= $this->run($n);
                    break;

                case 'hyperlink':
                    $href = null;
                    if ($anchor = $n->getAttributeNS(self::W, 'anchor')) {
                        $href = '#' . $anchor;
                    } elseif ($rid = $n->getAttributeNS(self::R, 'id')) {
                        [$target, $external] = $this->relsFor($this->part)[$rid] ?? [null, false];
                        $href = $external ? $target : null;
                    }
                    $inner = $this->inline($n);
                    $out  .= ($href && $this->safeHref($href)) ? '<a href="' . $this->e($href) . '">' . $inner . '</a>' : $inner;
                    break;

                case 'fldSimple':
                    $out .= $this->simpleField($n);
                    break;

                case 'bookmarkStart':
                    $this->rememberBookmark($n);
                    break;

                case 'smartTag': case 'customXml': case 'ins': case 'moveTo': case 'dir': case 'bdo':
                    $out .= $this->inline($n);
                    break;

                case 'sdt':
                    if ($c = $this->child($n, 'sdtContent')) {
                        $out .= $this->inline($c);
                    }
                    break;
            }
        }

        return $out;
    }

    private function run(DOMElement $r): string
    {
        $rPr = $this->child($r, 'rPr');

        if ($rPr && ($this->toggle($this->child($rPr, 'vanish')) || $this->toggle($this->child($rPr, 'webHidden')))) {
            // Hidden text, including the page numbers Word writes into a TOC.
            $tail = '';
            foreach ($r->childNodes as $c) {
                if ($c instanceof DOMElement && $c->localName === 'fldChar') {
                    $tail .= $this->fieldChar($c);
                } elseif ($c instanceof DOMElement && $c->localName === 'instrText' && $this->fieldStack) {
                    $this->fieldStack[count($this->fieldStack) - 1]['instr'] .= $c->textContent;
                }
            }

            return $tail;
        }

        $cs     = $rPr ? $this->val($this->child($rPr, 'rStyle')) : null;
        $csName = $cs ? ($this->styles[$cs]['name'] ?? '') : '';
        $fmt    = [
            'strong' => $this->runToggle($rPr, 'b', $cs) || in_array($csName, ['strong', 'intense emphasis'], true),
            'em'     => $this->runToggle($rPr, 'i', $cs) || in_array($csName, ['emphasis', 'subtle emphasis', 'intense emphasis'], true),
            'u'      => $rPr && $this->child($rPr, 'u') ? (bool) $this->underline($this->child($rPr, 'u')) : (bool) $this->styleProp($cs, 'u'),
            's'      => $this->runToggle($rPr, 'strike', $cs) || $this->runToggle($rPr, 'dstrike', $cs),
        ];
        $vert = ($rPr ? $this->val($this->child($rPr, 'vertAlign')) : null) ?? $this->styleProp($cs, 'vert');

        $text = '';
        $head = ''; // finished output, in document order (field link closings stay outside formatting)
        foreach ($r->childNodes as $c) {
            if (! $c instanceof DOMElement) {
                continue;
            }

            if ($c->namespaceURI === self::W) {
                switch ($c->localName) {
                    case 'fldChar':
                        if ($text !== '' && ($c->getAttributeNS(self::W, 'fldCharType') !== 'begin')) {
                            $head .= $this->openFieldLinks() . $this->wrapRun($text, $fmt, $vert);
                            $text  = '';
                        }
                        $head .= $this->fieldChar($c);
                        continue 2;
                    case 'instrText':
                        if ($this->fieldStack) {
                            $this->fieldStack[count($this->fieldStack) - 1]['instr'] .= $c->textContent;
                        }
                        continue 2;
                }

                if ($this->insideHiddenField()) {
                    continue;
                }

                switch ($c->localName) {
                    case 't':
                        $text .= $this->e($c->textContent);
                        break;
                    case 'tab': case 'ptab':
                        $text .= "\t";
                        break;
                    case 'br': case 'cr':
                        $type = $c->getAttributeNS(self::W, 'type');
                        if ($type !== 'page' && $type !== 'column') {
                            $text .= '<br>';
                        }
                        break;
                    case 'noBreakHyphen':
                        $text .= '&#8209;';
                        break;
                    case 'drawing':
                        $text .= $this->drawing($c);
                        break;
                    case 'pict': case 'object':
                        $text .= $this->vml($c);
                        break;
                    case 'footnoteReference':
                        $text .= $this->noteRef('footnote', $c->getAttributeNS(self::W, 'id'));
                        break;
                    case 'endnoteReference':
                        $text .= $this->noteRef('endnote', $c->getAttributeNS(self::W, 'id'));
                        break;
                    case 'sym':
                        $char = hexdec($c->getAttributeNS(self::W, 'char'));
                        if ($char >= 0xF000) {
                            $char -= 0xF000;
                        }
                        if ($char >= 32) {
                            $text .= ['B7' => '•', 'A7' => '■', 'D8' => '➢', 'FC' => '✓'][strtoupper(dechex($char))] ?? '';
                        }
                        break;
                }
            } elseif ($c->namespaceURI === self::MC && $c->localName === 'AlternateContent') {
                if (! $this->insideHiddenField()) {
                    $text .= $this->alternateInRun($c);
                }
            }
        }

        if ($text === '') {
            return $head;
        }

        if (trim(str_replace(["\t", '<br>'], '', $text)) === '' && ! str_contains($text, '<img') && ! str_contains($text, '<sup')) {
            return $head . $text;
        }

        return $head . $this->openFieldLinks() . $this->wrapRun($text, $fmt, $vert);
    }

    /** Applies a run's formatting — only around real text, never a lone picture or note mark. */
    private function wrapRun(string $text, array $fmt, ?string $vert): string
    {
        if (preg_match('#^\s*<(img|sup)\b#', $text) || trim(strip_tags($text)) === '') {
            return $text;
        }
        if ($vert === 'superscript') {
            $text = "<sup>{$text}</sup>";
        } elseif ($vert === 'subscript') {
            $text = "<sub>{$text}</sub>";
        }
        foreach (['s', 'u', 'em', 'strong'] as $tag) {
            if ($fmt[$tag]) {
                $text = "<{$tag}>{$text}</{$tag}>";
            }
        }

        return $text;
    }

    /**
     * Word stores modern shapes twice: mc:Choice (DrawingML) and mc:Fallback
     * (VML). Use the Choice when it holds a picture or text box, otherwise the
     * Fallback — never both, or the content would appear twice.
     */
    private function alternateInRun(DOMElement $alt): string
    {
        $choice   = $this->firstChildNS($alt, self::MC, 'Choice');
        $fallback = $this->firstChildNS($alt, self::MC, 'Fallback');
        $usable   = fn (?DOMElement $b) => $b && $this->xpath($b)->query('.//a:blip | .//w:txbxContent | .//v:imagedata', $b)->length > 0;

        $branch = $usable($choice) ? $choice : ($usable($fallback) ? $fallback : $choice);
        if (! $branch) {
            return '';
        }

        $out = '';
        foreach ($branch->childNodes as $c) {
            if (! $c instanceof DOMElement || $c->namespaceURI !== self::W) {
                continue;
            }
            if ($c->localName === 'drawing') {
                $out .= $this->drawing($c);
            } elseif ($c->localName === 'pict' || $c->localName === 'object') {
                $out .= $this->vml($c);
            }
        }

        return $out;
    }

    private function runToggle(?DOMElement $rPr, string $prop, ?string $charStyle): bool
    {
        $el = $rPr ? $this->child($rPr, $prop) : null;
        if ($el) {
            return $this->toggle($el);
        }

        return (bool) $this->styleProp($charStyle, $prop);
    }

    private function tidyInline(string $html): string
    {
        // Merge adjacent identical formatting left by Word's run splitting.
        for ($i = 0; $i < 3; $i++) {
            $html = preg_replace('#</(strong|em|u|s|sup|sub)>(\s*)<\1>#', '$2', $html);
        }
        // Tabs and runs of spaces are Word layout.
        $html = preg_replace('#[\t ]*\t[\t ]*| {2,}#', ' ', $html);
        // Formatting wrapped around whitespace only.
        $html = preg_replace('#<(strong|em|u|s)>(\s*)</\1>#', '$2', $html);

        return trim(preg_replace('#^(<br>)+|(<br>)+$#', '', trim($html)));
    }

    // ── Fields (TOC, page numbers, cross-references, hyperlinks) ─────────

    private function fieldChar(DOMElement $c): string
    {
        $type = $c->getAttributeNS(self::W, 'fldCharType');

        if ($type === 'begin') {
            $this->fieldStack[] = ['instr' => '', 'phase' => 'instr', 'href' => null, 'open' => false, 'hidden' => false];

            return '';
        }

        if (! $this->fieldStack) {
            return '';
        }

        $top = &$this->fieldStack[count($this->fieldStack) - 1];

        if ($type === 'separate') {
            $top['phase'] = 'result';
            [$kind, $href] = $this->parseInstr($top['instr']);
            $top['hidden'] = in_array($kind, ['PAGEREF', 'PAGE', 'NUMPAGES', 'SECTIONPAGES'], true);
            $top['href']   = $href;

            return '';
        }

        // end
        $closing = $top['open'] ? '</a>' : '';
        unset($top);
        array_pop($this->fieldStack);

        return $closing;
    }

    /** Opening tag for a field link, emitted with the first visible text of its result. */
    private function openFieldLinks(): string
    {
        $out = '';
        foreach ($this->fieldStack as $i => $f) {
            if ($f['phase'] === 'result' && $f['href'] && ! $f['open']) {
                $this->fieldStack[$i]['open'] = true;
                $out .= '<a href="' . $this->e($f['href']) . '">';
            }
        }

        return $out;
    }

    /** A field link never spans paragraphs in the HTML; it re-opens with the next text. */
    private function closeFieldLinks(): string
    {
        $out = '';
        foreach ($this->fieldStack as $i => $f) {
            if ($f['open']) {
                $this->fieldStack[$i]['open'] = false;
                $out .= '</a>';
            }
        }

        return $out;
    }

    private function insideHiddenField(): bool
    {
        foreach ($this->fieldStack as $f) {
            if ($f['phase'] === 'instr' || $f['hidden']) {
                return true;
            }
        }

        return false;
    }

    /** @return array{0:string,1:?string} field kind and link target */
    private function parseInstr(string $instr): array
    {
        $instr = trim($instr);
        $kind  = strtoupper(strtok($instr, " \t") ?: '');

        if ($kind === 'HYPERLINK') {
            if (preg_match('#\\\\l\s+"([^"]+)"#', $instr, $m)) {
                return [$kind, '#' . $m[1]];
            }
            if (preg_match('#HYPERLINK\s+"([^"]+)"#i', $instr, $m)) {
                $href = $m[1];
                if (preg_match('#\\\\l\s+"([^"]+)"#', $instr, $a)) {
                    $href .= '#' . $a[1];
                }

                return [$kind, $this->safeHref($href) ? $href : null];
            }
        }

        if ($kind === 'REF' && preg_match('#^REF\s+(\S+).*\\\\h#i', $instr, $m)) {
            return [$kind, '#' . $m[1]];
        }

        return [$kind, null];
    }

    private function simpleField(DOMElement $f): string
    {
        [$kind, $href] = $this->parseInstr($f->getAttributeNS(self::W, 'instr'));

        if (in_array($kind, ['PAGEREF', 'PAGE', 'NUMPAGES', 'SECTIONPAGES'], true)) {
            return '';
        }

        $inner = $this->inline($f);

        return $href ? '<a href="' . $this->e($href) . '">' . $inner . '</a>' : $inner;
    }

    private function safeHref(string $href): bool
    {
        return (bool) preg_match('#^(https?://|mailto:|\#|/)#i', $href);
    }

    // ── Pictures and text boxes ──────────────────────────────────────────

    private function drawing(DOMElement $d): string
    {
        $xp = $this->xpath($d);

        // Text box shapes: their content is block level, so it follows the paragraph.
        $boxes = $xp->query('.//w:txbxContent', $d);
        if ($boxes->length) {
            foreach ($boxes as $box) {
                $this->deferTextBox($box);
            }

            return '';
        }

        $blips = $xp->query('.//a:blip', $d);
        if (! $blips->length) {
            $this->missing('a chart or diagram');

            return '';
        }

        $extent  = $xp->query('.//wp:extent', $d)->item(0);
        $docPr   = $xp->query('.//wp:docPr', $d)->item(0);
        $alt     = $this->cleanAlt($docPr ? ($docPr->getAttribute('descr') ?: $docPr->getAttribute('title')) : '');
        $width   = $extent ? (int) round((int) $extent->getAttribute('cx') / self::EMU_PER_PX) : null;
        $height  = $extent ? (int) round((int) $extent->getAttribute('cy') / self::EMU_PER_PX) : null;
        $floating = $xp->query('./wp:anchor', $d)->length > 0;

        $html = '';
        foreach ($blips as $blip) {
            $rid   = $blip->getAttributeNS(self::R, 'embed');
            $html .= $this->image($rid, $alt, $blips->length === 1 ? $width : null, $blips->length === 1 ? $height : null);
        }

        if ($floating && $html !== '') {
            $this->deferred[] = '<p class="doc-figure">' . $html . '</p>';

            return '';
        }

        return $html;
    }

    private function vml(DOMElement $el): string
    {
        $xp = $this->xpath($el);

        $boxes = $xp->query('.//w:txbxContent', $el);
        if ($boxes->length) {
            foreach ($boxes as $box) {
                $this->deferTextBox($box);
            }

            return '';
        }

        $img = $xp->query('.//v:imagedata', $el)->item(0);
        if (! $img) {
            return '';
        }

        $shape = $xp->query('.//v:shape', $el)->item(0);
        $style = $shape ? $shape->getAttribute('style') : '';
        $px    = fn ($k) => preg_match('#' . $k . ':\s*([\d.]+)(pt|px|in)#', $style, $m)
            ? (int) round((float) $m[1] * ['pt' => 96 / 72, 'px' => 1, 'in' => 96][$m[2]])
            : null;

        return $this->image($img->getAttributeNS(self::R, 'id'), $this->cleanAlt($img->getAttribute('o:title')), $px('width'), $px('height'));
    }

    /**
     * Word often fills a picture's description with the path it was inserted
     * from ("C:\Users\…\image.png", "Macintosh HD:Users:…") or a placeholder
     * ("Inline image 1"). That must never be published; the caption is used
     * instead (see finish()).
     */
    private function cleanAlt(?string $alt): string
    {
        $alt = trim(preg_replace('/\s+/u', ' ', (string) $alt));

        $junk = $alt === ''
            || preg_match('#^[a-z]:[\\/]|^\\\\|^macintosh hd:|^/(users|home|var|tmp)/|^file:#i', $alt)
            || preg_match('#[\\/:][^\\/:]*\.(png|jpe?g|gif|bmp|tiff?|emf|wmf|webp|svg)$#i', $alt)
            || preg_match('#^(inline )?(image|picture|graphic|figure|photo|screenshot|chart|diagram)\s*\d*$#i', $alt)
            || preg_match('#^(a )?(screenshot|picture) of a (computer|screen)#i', $alt);

        return $junk ? '' : $alt;
    }

    private function deferTextBox(DOMElement $box): void
    {
        [$savedIds, $savedDeferred] = [$this->pendingIds, $this->deferred];
        [$this->pendingIds, $this->deferred] = [[], []];
        $inner = $this->blocks($box);
        [$this->pendingIds, $this->deferred] = [$savedIds, $savedDeferred];

        if (trim(strip_tags($inner, '<img>')) !== '') {
            $this->deferred[] = '<aside class="doc-textbox">' . $inner . '</aside>';
        }
    }

    private function flushDeferred(): string
    {
        $out = implode("\n", $this->deferred);
        $this->deferred = [];

        return $out === '' ? '' : $out . "\n";
    }

    private function image(string $rid, string $alt, ?int $width, ?int $height): string
    {
        [$target, $external] = $this->relsFor($this->part)[$rid] ?? [null, false];

        if (! $target || $external) {
            $this->missing('a linked picture');

            return '';
        }

        $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

        if (! in_array($ext, self::WEB_IMAGES, true)) {
            return $this->missing(strtoupper($ext) . ' format');
        }

        if (! isset($this->imageUrls[$target])) {
            $bytes = $this->zip->getFromName($target);
            if ($bytes === false) {
                return $this->missing('missing from the file');
            }
            $this->imageUrls[$target] = ($this->storeImage)($bytes, $ext === 'jpeg' ? 'jpg' : $ext);
            $this->imageCount++;
        }

        $attrs = ' src="' . $this->e($this->imageUrls[$target]) . '" alt="' . $this->e($alt) . '"';
        if ($width && $height) {
            $attrs .= ' width="' . $width . '" height="' . $height . '"';
            if ($width < self::INLINE_IMAGE_MAX_WIDTH) {
                $attrs = ' class="inline-image"' . $attrs;
            }
        }

        return '<img' . $attrs . '>';
    }

    /** A visible placeholder for a picture that cannot be shown on the web. */
    private function missing(string $why): string
    {
        $this->warnings[] = 'Picture not imported (' . $why . ')';
        $this->missingWithoutCaption = array_key_last($this->warnings);

        $this->deferred[] = '<p class="doc-import-missing">⚠ Picture not imported (' . $this->e($why) . '). Save it from Word as a PNG or JPEG and insert it here.</p>';

        return '';
    }

    private function math(DOMElement $m): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $m->textContent));
        if ($text === '') {
            return '';
        }
        if (! in_array('Equations are imported as plain text — replace any that need exact layout with a picture', $this->warnings, true)) {
            $this->warnings[] = 'Equations are imported as plain text — replace any that need exact layout with a picture';
        }

        return '<span class="doc-math">' . $this->e($text) . '</span>';
    }

    // ── Tables ───────────────────────────────────────────────────────────

    private function table(DOMElement $tbl): string
    {
        $rows = [];

        foreach ($this->children($tbl, 'tr') as $tr) {
            $trPr   = $this->child($tr, 'trPr');
            $header = $trPr && $this->child($trPr, 'tblHeader') && $this->toggle($this->child($trPr, 'tblHeader'));
            $col    = 0;
            $cells  = [];

            foreach ($this->rowCells($tr) as $tc) {
                $tcPr  = $this->child($tc, 'tcPr');
                $span  = max(1, (int) ($tcPr ? ($this->val($this->child($tcPr, 'gridSpan')) ?? 1) : 1));
                $vm    = $tcPr ? $this->child($tcPr, 'vMerge') : null;
                $merge = $vm ? ($vm->getAttributeNS(self::W, 'val') === 'restart' ? 'restart' : 'continue') : null;
                $shd   = $tcPr ? $this->child($tcPr, 'shd') : null;
                $fill  = $shd ? strtoupper($shd->getAttributeNS(self::W, 'fill')) : '';

                $cells[] = ['tc' => $tc, 'col' => $col, 'span' => $span, 'merge' => $merge, 'fill' => preg_match('#^[0-9A-F]{6}$#', $fill) ? $fill : null];
                $col += $span;
            }

            $rows[] = ['header' => $header, 'cells' => $cells];
        }

        if (! $rows) {
            return '';
        }

        // A one-cell table is a box around content in Word, not data.
        if (count($rows) === 1 && count($rows[0]['cells']) === 1) {
            $inner = $this->blocks($rows[0]['cells'][0]['tc']);

            return trim(strip_tags($inner, '<img>')) === '' ? '' : '<aside class="doc-textbox">' . $inner . "</aside>\n";
        }

        // A shaded first row over unshaded rows is a header row.
        if (! $rows[0]['header'] && count($rows) > 1
            && $this->allShaded($rows[0]['cells']) && ! $this->allShaded($rows[1]['cells'])) {
            $rows[0]['header'] = true;
        }

        $html = '<table class="doc-table">';
        $inHead = false;

        foreach ($rows as $r => $row) {
            if ($row['header'] && $r === 0) {
                $html  .= '<thead>';
                $inHead = true;
            } elseif ($inHead && ! $row['header']) {
                $html  .= '</thead><tbody>';
                $inHead = false;
            } elseif ($r === 0) {
                $html .= '<tbody>';
            }

            $html .= '<tr>';
            foreach ($row['cells'] as $cell) {
                if ($cell['merge'] === 'continue') {
                    continue;
                }

                $rowspan = 1;
                if ($cell['merge'] === 'restart') {
                    for ($k = $r + 1; $k < count($rows); $k++) {
                        $below = collect($rows[$k]['cells'])->firstWhere('col', $cell['col']);
                        if (! $below || $below['merge'] !== 'continue') {
                            break;
                        }
                        $rowspan++;
                    }
                }

                $tag   = $row['header'] ? 'th' : 'td';
                $attrs = ($cell['span'] > 1 ? ' colspan="' . $cell['span'] . '"' : '') . ($rowspan > 1 ? ' rowspan="' . $rowspan . '"' : '');
                $attrs .= $this->shadeStyle($cell['fill']);

                $inner = $this->blocks($cell['tc']);
                // A cell holding one plain paragraph doesn't need the <p>.
                if (preg_match('#^<p(?: style="([^"]*)")?>(.*)</p>\s*$#s', $inner, $m) && ! str_contains($m[2], '<p')) {
                    $inner = $m[2];
                    if (($m[1] ?? '') !== '') {
                        $attrs = str_contains($attrs, 'style="')
                            ? preg_replace('#style="([^"]*)"#', 'style="$1; ' . $m[1] . '"', $attrs, 1)
                            : $attrs . ' style="' . $m[1] . '"';
                    }
                }

                $html .= "<{$tag}{$attrs}>" . trim($inner) . "</{$tag}>";
            }
            $html .= '</tr>';
        }

        $html .= $inHead ? '</thead>' : '</tbody>';

        return $html . "</table>\n";
    }

    /** Cells of a row, unwrapping content controls around them. */
    private function rowCells(DOMElement $tr): array
    {
        $cells = [];
        foreach ($tr->childNodes as $n) {
            if (! $n instanceof DOMElement || $n->namespaceURI !== self::W) {
                continue;
            }
            if ($n->localName === 'tc') {
                $cells[] = $n;
            } elseif ($n->localName === 'sdt' && ($c = $this->child($n, 'sdtContent'))) {
                array_push($cells, ...$this->children($c, 'tc'));
            }
        }

        return $cells;
    }

    private function allShaded(array $cells): bool
    {
        foreach ($cells as $c) {
            if (! $c['fill'] || $c['fill'] === 'FFFFFF') {
                return false;
            }
        }

        return (bool) $cells;
    }

    private function shadeStyle(?string $fill): string
    {
        if (! $fill || $fill === 'FFFFFF') {
            return '';
        }
        [$r, $g, $b] = sscanf($fill, '%02x%02x%02x');
        $dark = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255 < 0.5;

        return ' style="background-color: #' . strtolower($fill) . ($dark ? '; color: #ffffff' : '') . '"';
    }

    // ── Footnotes and endnotes ───────────────────────────────────────────

    private function noteRef(string $type, string $id): string
    {
        if (! isset($this->notes[$type][$id])) {
            return '';
        }
        $n = ++$this->noteNumber[$type];
        $this->noteOrder[] = [$type, $id, $n];

        return '<sup><a href="#' . $type . '-' . $n . '" id="' . $type . '-ref-' . $n . '">[' . $n . ']</a></sup>';
    }

    private function notesSection(): string
    {
        if (! $this->noteOrder) {
            return '';
        }

        $out = '';
        foreach (['footnote', 'endnote'] as $type) {
            $items = array_filter($this->noteOrder, fn ($o) => $o[0] === $type);
            if (! $items) {
                continue;
            }

            $savedPart  = $this->part;
            $this->part = 'word/' . $type . 's.xml';
            $list = '';

            foreach ($items as [$t, $id, $n]) {
                $this->pendingIds = [];
                $body = trim($this->blocks($this->notes[$type][$id]));
                $back = ' <a href="#' . $type . '-ref-' . $n . '" class="doc-note-back" aria-label="Back to the text">↑</a>';
                $body = preg_match('#</p>\s*$#', $body) ? preg_replace('#</p>\s*$#', $back . '</p>', $body) : $body . $back;
                $list .= '<li id="' . $type . '-' . $n . '">' . $body . '</li>';
            }

            $this->part = $savedPart;
            $out .= '<section class="doc-notes"><h2>' . ($type === 'endnote' ? 'Notes' : 'Footnotes') . '</h2><ol>' . $list . "</ol></section>\n";
        }

        return $out;
    }

    // ── Final polish ─────────────────────────────────────────────────────

    private function finish(string $html): string
    {
        // Links are underlined by the site; Word's "Hyperlink" style adds a second one.
        $html = preg_replace_callback('#<a\b[^>]*>.*?</a>#s', fn ($m) => preg_replace('#</?u>#', '', $m[0]), $html);

        // A picture with no alt text takes its figure caption (accessibility, search).
        $html = preg_replace_callback(
            // (allowing one short note paragraph between picture and caption)
            '#(<img\b[^>]*\balt=")("[^>]*>\s*</p>\s*(?:<p\b(?![^>]*doc-caption)[^>]*>(?:(?!</p>).){0,300}</p>\s*)?<p\b[^>]*class="doc-caption"[^>]*>)(.*?)</p>#s',
            fn ($m) => $m[1] . $this->e(trim(html_entity_decode(strip_tags($m[3]), ENT_QUOTES | ENT_HTML5))) . $m[2] . $m[3] . '</p>',
            $html
        );

        // One block per line keeps the editor's source view readable.
        return preg_replace('#(</(?:ol|ul|table|aside|blockquote|section)>)(?=<(?:p|h[1-6]|ol|ul|table|aside|blockquote|section)\b)#', "$1\n", $html);
    }

    // ── Links to bookmarks ───────────────────────────────────────────────

    private function resolveAliases(string $html): string
    {
        if (! $this->aliases) {
            return $html;
        }

        return preg_replace_callback('#href="\#([^"]+)"#', function ($m) {
            $name = html_entity_decode($m[1]);

            return isset($this->aliases[$name]) ? 'href="#' . $this->e($this->aliases[$name]) . '"' : $m[0];
        }, $html);
    }

    // ── DOM helpers ──────────────────────────────────────────────────────

    private function child(?DOMNode $el, string $name): ?DOMElement
    {
        return $el ? $this->firstChildNS($el, self::W, $name) : null;
    }

    private function firstChildNS(DOMNode $el, string $ns, string $name): ?DOMElement
    {
        foreach ($el->childNodes as $c) {
            if ($c instanceof DOMElement && $c->namespaceURI === $ns && $c->localName === $name) {
                return $c;
            }
        }

        return null;
    }

    /** @return list<DOMElement> */
    private function children(DOMNode $el, string $name): array
    {
        $out = [];
        foreach ($el->childNodes as $c) {
            if ($c instanceof DOMElement && $c->namespaceURI === self::W && $c->localName === $name) {
                $out[] = $c;
            }
        }

        return $out;
    }

    private function val(?DOMElement $el): ?string
    {
        if (! $el) {
            return null;
        }
        $v = $el->getAttributeNS(self::W, 'val');

        return $v === '' ? null : $v;
    }

    private function toggle(?DOMElement $el): bool
    {
        if (! $el) {
            return false;
        }
        $v = strtolower($el->getAttributeNS(self::W, 'val'));

        return ! in_array($v, ['0', 'false', 'off', 'none'], true);
    }

    private function underline(?DOMElement $el): ?bool
    {
        if (! $el) {
            return null;
        }

        return strtolower($el->getAttributeNS(self::W, 'val') ?: 'single') !== 'none';
    }

    private function xpath(DOMNode $node): DOMXPath
    {
        $xp = new DOMXPath($node->ownerDocument);
        $xp->registerNamespace('w', self::W);
        $xp->registerNamespace('wp', self::WP);
        $xp->registerNamespace('a', self::A);
        $xp->registerNamespace('v', self::V);

        return $xp;
    }

    private function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
    }
}
