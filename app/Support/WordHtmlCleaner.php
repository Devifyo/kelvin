<?php

namespace App\Support;

/**
 * Tidies HTML pasted from Microsoft Word before it is stored.
 *
 * Word wraps content in conditional comments, <o:p> tags, Mso* classes and
 * mso-* inline styles. None of it is needed on a web page, and it bloats the
 * markup crawlers have to read. Structure (headings, lists, tables, links,
 * images, emphasis) is left untouched.
 */
class WordHtmlCleaner
{
    /** Images narrower than this (px) are treated as inline, e.g. an equation in a sentence. */
    private const INLINE_IMAGE_MAX_WIDTH = 200;

    /** Returns the cleaned HTML, or null when nothing meaningful is left. */
    public static function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        // Conditional comments, then any remaining comments.
        $html = self::sub('#<!--\[if[\s\S]*?<!\[endif\]-->#i', '', $html);
        $html = self::sub('#<!--[\s\S]*?-->#', '', $html);

        // Blocks and tags that never belong in body copy.
        $html = self::sub('#<(style|xml|script)\b[^>]*>[\s\S]*?</\1>#i', '', $html);
        $html = self::sub('#<(meta|link)\b[^>]*>#i', '', $html);

        // Office namespaced tags: <o:p>, <w:...>, <v:...>, <m:...>, <st1:...>.
        $html = self::sub('#</?(?:o|w|v|m|st1):[a-z0-9]+[^>]*>#i', '', $html);

        // Word-only attributes.
        $html = self::sub('#\s+class=(["\'])\s*Mso[^"\']*\1#i', '', $html);
        $html = self::sub('#\s+lang=(["\'])[^"\']*\1#i', '', $html);

        // Drop Word's presentational declarations, keep anything deliberate
        // (text-align, table widths, colours chosen in the editor).
        // display:none is kept for now so the span pass below can recognise
        // the hidden TOC page numbers it marks.
        $html = self::sub('#\s+style=(["\'])(.*?)\1#is', function ($m) {
            $keep = array_filter(
                array_map('trim', explode(';', $m[2])),
                fn ($d) => $d !== ''
                    && ! preg_match('#^(mso-|tab-stops|text-autospace|page-break|font-family|font\s*:|line-height|margin|text-indent|text-underline)#i', $d)
                    && ! preg_match('#:\s*windowtext\s*$#i', $d)
            );

            return $keep ? ' style=' . $m[1] . implode('; ', $keep) . $m[1] : '';
        }, $html);

        // Hidden spans (Word's TOC page numbers and dot leaders) go, bare
        // <span> wrappers left behind by the style pass are unwrapped.
        $html = self::tidySpans($html);

        // Word's bookmarks (<a name="_Toc…">) become HTML5 ids so the TOC's
        // "#_Toc…" links keep working and the targets can be styled.
        $html = self::sub('#<a\s+name=(["\'])([^"\']+)\1\s*>#i', '<a id=$1$2$1>', $html);

        // Runs of non-breaking spaces are Word's tab stops (heading numbers,
        // TOC entries). On a web page they are just a gap.
        $html = self::sub('#(?:&nbsp;|\xC2\xA0){2,}\s*#u', ' ', $html);

        // Small images sit inside a sentence (equations, symbols) — mark them
        // so the stylesheet does not break them out as block figures.
        $html = self::sub('#<img\b(?![^>]*\bclass=)([^>]*\bwidth=["\']?(\d+))#i', function ($m) {
            return (int) $m[2] < self::INLINE_IMAGE_MAX_WIDTH
                ? '<img class="inline-image"' . $m[1]
                : $m[0];
        }, $html);

        // Leftover empty wrappers: a span around nothing but whitespace adds
        // nothing, whatever it is styled — keep the whitespace, drop the span.
        $html = self::sub('#<span\b[^>]*>(\s*)</span>#i', '$1', $html);
        $html = self::sub('#<p[^>]*>(?:\s|&nbsp;|\xC2\xA0|<br\s*/?>|<span\b[^>]*>|</span>)*+</p>#i', '', $html);

        $html = trim((string) $html);

        // "Empty" means no text and no images.
        $text = trim(str_replace("\u{00A0}", ' ', html_entity_decode(strip_tags($html, '<img>'), ENT_QUOTES | ENT_HTML5)));

        return $text === '' ? null : $html;
    }

    /**
     * One linear pass over the <span> tags: a span styled display:none is
     * removed together with everything inside it (Word hides TOC page numbers
     * that way), a <span> with no attributes at all is unwrapped, and every
     * other span is kept. Nesting is tracked so the right </span> goes with
     * the right opener — a regex cannot do that reliably.
     */
    private static function tidySpans(string $html): string
    {
        $parts = preg_split('#(<span\b[^>]*>|</span>)#i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($parts === false) {
            return $html;
        }

        $out    = '';
        $stack  = [];
        $hidden = 0; // depth inside a removed span

        foreach ($parts as $part) {
            if (preg_match('#^<span\b([^>]*)>$#i', $part, $m)) {
                $attrs = trim($m[1]);

                if (preg_match('#display\s*:\s*none#i', $attrs)) {
                    $mode = 'remove';
                    $hidden++;
                } elseif ($attrs === '') {
                    $mode = 'unwrap';
                } else {
                    $mode = 'keep';
                }

                $stack[] = $mode;

                if ($mode === 'keep' && $hidden === 0) {
                    $out .= $part;
                }
            } elseif (strcasecmp($part, '</span>') === 0) {
                $mode = array_pop($stack) ?? 'keep';

                if ($mode === 'remove') {
                    $hidden = max(0, $hidden - 1);
                } elseif ($mode === 'keep' && $hidden === 0) {
                    $out .= $part;
                }
            } elseif ($hidden === 0) {
                $out .= $part;
            }
        }

        return $out;
    }

    /**
     * preg_replace that can never lose the document. PCRE returns null when a
     * pattern hits its backtrack / JIT limit (a very long comment or run of
     * whitespace) or meets invalid UTF-8; without this guard that null would
     * cascade and the whole paper would be saved as empty. A step that fails
     * is simply skipped.
     */
    private static function sub(string $pattern, string|callable $replacement, string $html): string
    {
        $result = is_callable($replacement)
            ? preg_replace_callback($pattern, $replacement, $html)
            : preg_replace($pattern, $replacement, $html);

        return $result ?? $html;
    }
}
