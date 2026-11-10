<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A Resource Library entry. Despite the class name (kept for history), a Paper
 * can be a downloadable document, a book sold externally, or a plain link.
 */
class Paper extends Model
{
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_BOOK     = 'book';
    public const TYPE_LINK     = 'link';

    public const TYPES = [
        self::TYPE_DOCUMENT => 'Document (PDF / file)',
        self::TYPE_BOOK     => 'Book (external purchase link)',
        self::TYPE_LINK     => 'External link (website)',
    ];

    protected $fillable = [
        'title', 'slug', 'category_id', 'sub_category', 'resource_type',
        'description', 'long_description', 'html_content', 'featured_image',
        'file_path', 'external_url', 'cta_label',
        'meta_title', 'meta_description', 'meta_keywords',
        'is_active', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
    ];

    protected static function booted(): void
    {
        // Every resource needs a slug for its public page — derive one from the
        // title whenever a creation path (seeder, tinker, import) leaves it blank.
        static::creating(function (self $paper) {
            if (blank($paper->slug) && filled($paper->title)) {
                $paper->slug = static::uniqueSlug($paper->title);
            }
            if (blank($paper->resource_type)) {
                $paper->resource_type = self::TYPE_DOCUMENT;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc');
    }

    // ── Type helpers ───────────────────────────────────────────────────────

    public function getIsDocumentAttribute(): bool
    {
        return ($this->resource_type ?: self::TYPE_DOCUMENT) === self::TYPE_DOCUMENT;
    }

    public function getIsBookAttribute(): bool
    {
        return $this->resource_type === self::TYPE_BOOK;
    }

    public function getIsLinkAttribute(): bool
    {
        return $this->resource_type === self::TYPE_LINK;
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->resource_type) {
            self::TYPE_BOOK => 'Book',
            self::TYPE_LINK => 'Link',
            default         => strtoupper($this->file_extension ?: 'Document'),
        };
    }

    // ── File helpers ───────────────────────────────────────────────────────

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getFileExtensionAttribute(): ?string
    {
        return $this->file_path ? strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) : null;
    }

    /** Human readable size ("1.2 MB") or null if the file cannot be read. */
    public function getFileSizeLabelAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        try {
            $disk = Storage::disk('public');
            if (! $disk->exists($this->file_path)) {
                return null;
            }
            $bytes = $disk->size($this->file_path);
        } catch (\Throwable) {
            return null;
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return max(1, (int) round($bytes / 1024)) . ' KB';
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? Storage::url($this->featured_image) : null;
    }

    // ── Call-to-action ─────────────────────────────────────────────────────

    /** Where the primary button on the resource page sends the visitor. */
    public function getCtaUrlAttribute(): ?string
    {
        return $this->is_document ? $this->file_url : ($this->external_url ?: null);
    }

    /** Button label: admin override, otherwise derived from the resource type. */
    public function getCtaTextAttribute(): string
    {
        if (filled($this->cta_label)) {
            return $this->cta_label;
        }

        if ($this->is_book) {
            return 'Buy on Amazon';
        }

        if ($this->is_link) {
            return 'Visit Website';
        }

        return match ($this->file_extension) {
            'pdf'         => 'View PDF',
            'ppt', 'pptx' => 'View Presentation',
            null          => 'File Pending',
            default       => 'View Document',
        };
    }

    // ── Content / SEO ──────────────────────────────────────────────────────

    /**
     * Body of the resource page. Falls back to the card summary so every
     * resource renders a complete page before the long copy is written.
     * Any <h1> in authored HTML is demoted to keep exactly one H1 per page.
     */
    public function getLongDescriptionHtmlAttribute(): string
    {
        $html = filled(strip_tags((string) $this->long_description))
            ? $this->long_description
            : $this->description;

        return preg_replace('#<(/?)h1(\s[^>]*)?>#i', '<$1h2$2>', (string) $html);
    }

    public function getPlainSummaryAttribute(): string
    {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description))), 155, '');
    }

    /** <title> for the resource page — explicit meta title wins, else a branded, keyword-bearing build. */
    public function seoTitle(): string
    {
        if (filled($this->meta_title)) {
            return $this->meta_title;
        }

        $brand = ' | Kevin Thompson, Ph.D.';
        $withType = $this->title . ($this->category ? ' — ' . Str::singular($this->category->name) : '');

        return mb_strlen($withType . $brand) <= 60
            ? $withType . $brand
            : $this->title . $brand;
    }

    public function seoDescription(): string
    {
        return filled($this->meta_description) ? $this->meta_description : $this->plain_summary;
    }

    // ── HTML version (full text) ───────────────────────────────────────────
    //
    // A document resource may carry its full text as HTML (html_content). When
    // it does, the paper is also published as an ordinary web page at
    // /agile-hardware-papers-and-presentations/{slug}/read (route papers.read).

    /** True when this document has a full-text web version to read online. */
    public function getHasHtmlVersionAttribute(): bool
    {
        return $this->is_document && $this->htmlPlainText() !== '';
    }

    /** Full text for the reading page; any <h1> is demoted so the page keeps one H1. */
    public function getHtmlContentHtmlAttribute(): string
    {
        return preg_replace('#<(/?)h1(\s[^>]*)?>#i', '<$1h2$2>', $this->htmlWithoutRepeatedTitle());
    }

    /**
     * A paper pasted from Word usually opens with its own title. The reading
     * page already prints the title as its H1, so an opening heading that
     * merely repeats it is dropped rather than shown twice.
     */
    private function htmlWithoutRepeatedTitle(): string
    {
        $html = ltrim((string) $this->html_content);

        if (preg_match('#^<(h[1-6])\b[^>]*>(.*?)</\1>#is', $html, $m)) {
            $normalize = fn (string $t) => mb_strtolower(trim(preg_replace('/\s+/u', ' ',
                str_replace("\u{00A0}", ' ', html_entity_decode(strip_tags($t), ENT_QUOTES | ENT_HTML5)))));

            if ($normalize($m[2]) === $normalize((string) $this->title)) {
                $html = ltrim(substr($html, strlen($m[0])));
            }
        }

        return $html;
    }

    public function getHtmlWordCountAttribute(): int
    {
        return str_word_count($this->htmlPlainText());
    }

    public function getHtmlReadMinutesAttribute(): int
    {
        return max(1, (int) ceil($this->html_word_count / 200));
    }

    /** URL of the reading page, or null when there is no HTML version. */
    public function getReadUrlAttribute(): ?string
    {
        return $this->has_html_version ? route('papers.read', $this->slug) : null;
    }

    private function htmlPlainText(): string
    {
        // Tags are replaced by a space so adjacent blocks don't fuse into one word.
        $text = strip_tags(preg_replace('/<[^>]+>/', ' $0', $html = $this->htmlWithoutRepeatedTitle()) ?? $html);

        $text = str_replace("\u{00A0}", ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5));

        // A failed preg (invalid UTF-8) must not make a real paper look empty.
        return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
    }

    /**
     * <title> for the reading page. Deliberately different from the summary
     * page's title so the two URLs never compete with identical titles.
     */
    public function readSeoTitle(): string
    {
        $base  = (filled($this->meta_title) ? $this->meta_title : $this->title) . ' — Full Text';
        $brand = ' | Kevin Thompson, Ph.D.';

        return (! str_contains($base, 'Kevin Thompson') && mb_strlen($base . $brand) <= 60)
            ? $base . $brand
            : $base;
    }

    /**
     * Meta description for the reading page: the opening of the paper itself,
     * so it differs from the summary page's description.
     */
    public function readSeoDescription(): string
    {
        $lead = Str::limit($this->htmlPlainText(), 155, '', preserveWords: true);

        return (mb_strlen($lead) < 50 || $lead === $this->seoDescription())
            ? Str::limit('Full text: ' . $this->plain_summary, 155, '')
            : $lead;
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'resource';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
