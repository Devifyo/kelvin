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
        $html = self::sub('#\s+style=(["\'])(.*?)\1#is', function ($m) {
            $keep = array_filter(
                array_map('trim', explode(';', $m[2])),
                fn ($d) => $d !== '' && ! preg_match(
                    '#^(mso-|tab-stops|text-autospace|page-break|font-family|line-height|margin|text-indent)#i',
                    $d
                )
            );

            return $keep ? ' style=' . $m[1] . implode('; ', $keep) . $m[1] : '';
        }, $html);

        // Leftover empty wrappers.
        $html = self::sub('#<span>\s*</span>#i', '', $html);
        $html = self::sub('#<p[^>]*>(?:\s|&nbsp;|\xC2\xA0|<br\s*/?>)*+</p>#i', '', $html);

        $html = trim((string) $html);

        // "Empty" means no text and no images.
        $text = trim(str_replace("\u{00A0}", ' ', html_entity_decode(strip_tags($html, '<img>'), ENT_QUOTES | ENT_HTML5)));

        return $text === '' ? null : $html;
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
