<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

/**
 * Admin → Resource Library.
 *
 * Each row is a Paper: a downloadable document, a book sold externally, or a
 * plain link. Every resource has its own public page, so the modal carries the
 * long description + SEO fields alongside the card summary.
 */
#[Layout('layouts.admin')]
class ManagePapers extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $paperId = null;
    public $modalTab = 'details';

    // ── Details ──────────────────────────────────────────────────────────
    public $title, $slug, $category_id, $new_category_name, $sub_category, $description;
    public $resource_type = Paper::TYPE_DOCUMENT;
    public $file, $existing_file, $existing_file_name;
    public $external_url, $cta_label;
    public $is_active = true, $is_featured = false, $sort_order = 0;

    // ── Resource page ────────────────────────────────────────────────────
    public $long_description;
    public $featured_image, $existing_featured_image;
    public $remove_featured_image = false;

    // ── SEO ──────────────────────────────────────────────────────────────
    public $meta_title, $meta_description, $meta_keywords;

    // ── List filters ─────────────────────────────────────────────────────
    public $filterCategory = '';
    public $filterStatus = '';
    public $filterFeatured = '';

    public function setStatusFilter(string $value): void
    {
        $this->filterStatus = $value;
        $this->resetPage();
    }

    public function setFeaturedFilter(string $value): void
    {
        $this->filterFeatured = $value;
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:papers,slug,' . ($this->paperId ?: 'NULL') . ',id',
            'category_id'       => 'required',
            'new_category_name' => 'required_if:category_id,new|nullable|string|max:255',
            'sub_category'      => 'nullable|string|max:255',
            'resource_type'     => 'required|in:' . implode(',', array_keys(Paper::TYPES)),
            'description'       => 'required|string|max:2000',
            'long_description'  => 'nullable|string',
            'file'              => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:30720', // 30MB
            'external_url'      => 'nullable|url|max:2048|required_if:resource_type,' . Paper::TYPE_BOOK . ',' . Paper::TYPE_LINK,
            'cta_label'         => 'nullable|string|max:60',
            'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string|max:255',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'sort_order'        => 'integer',
        ];
    }

    protected $messages = [
        'slug.regex'                => 'Use lowercase letters, numbers and hyphens only (e.g. agile-pmo).',
        'slug.unique'               => 'Another resource already uses this URL slug.',
        'external_url.required_if'  => 'Books and external links need a destination URL.',
        'external_url.url'          => 'Enter a full URL starting with https://',
    ];

    /** Auto-fill the slug from the title until the resource has been saved once. */
    public function updatedTitle($value): void
    {
        if (! $this->paperId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug($value): void
    {
        $this->slug = Str::slug($value);
    }

    /**
     * Reorder papers based on drag-and-drop.
     * Receives an ordered array of paper IDs from the frontend.
     */
    public function reorder(array $orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Paper::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        $this->dispatch('notify', message: 'Order updated.', type: 'success');
    }

    public function create()
    {
        $this->resetForm();
        $this->sort_order = (int) Paper::max('sort_order') + 1;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $paper = Paper::findOrFail($id);

        $this->resetForm();

        $this->paperId            = $paper->id;
        $this->title              = $paper->title;
        $this->slug               = $paper->slug;
        $this->category_id        = $paper->category_id;
        $this->sub_category       = $paper->sub_category;
        $this->resource_type      = $paper->resource_type ?: Paper::TYPE_DOCUMENT;
        $this->description        = $paper->description;
        $this->long_description   = $paper->long_description;
        $this->existing_file      = $paper->file_url;
        $this->existing_file_name = $paper->file_path ? basename($paper->file_path) : null;
        $this->external_url       = $paper->external_url;
        $this->cta_label          = $paper->cta_label;
        $this->existing_featured_image = $paper->featured_image_url;
        $this->meta_title         = $paper->meta_title;
        $this->meta_description   = $paper->meta_description;
        $this->meta_keywords      = $paper->meta_keywords;
        $this->is_active          = (bool) $paper->is_active;
        $this->is_featured        = (bool) $paper->is_featured;
        $this->sort_order         = $paper->sort_order;

        $this->showModal = true;
    }

    protected function resetForm(): void
    {
        $this->reset([
            'paperId', 'title', 'slug', 'category_id', 'new_category_name', 'sub_category',
            'description', 'long_description', 'file', 'existing_file', 'existing_file_name',
            'external_url', 'cta_label', 'featured_image', 'existing_featured_image',
            'remove_featured_image', 'meta_title', 'meta_description', 'meta_keywords',
        ]);
        $this->resource_type = Paper::TYPE_DOCUMENT;
        $this->is_active     = true;
        $this->is_featured   = false;
        $this->sort_order    = 0;
        $this->modalTab      = 'details';
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function save()
    {
        if (blank($this->slug) && filled($this->title)) {
            $this->slug = Str::slug($this->title);
        }

        $this->validate();

        // Handle dynamic Category Creation specifically for 'paper' type
        $finalCategoryId = $this->category_id;
        if ($this->category_id === 'new' && !empty($this->new_category_name)) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($this->new_category_name)],
                ['name' => $this->new_category_name, 'type' => 'paper']
            );
            $finalCategoryId = $category->id;
        }

        $isDocument = $this->resource_type === Paper::TYPE_DOCUMENT;

        $data = [
            'title'            => $this->title,
            'slug'             => $this->slug,
            'category_id'      => $finalCategoryId,
            'sub_category'     => $this->sub_category,
            'resource_type'    => $this->resource_type,
            'description'      => $this->description,
            'long_description' => $this->long_description,
            'external_url'     => $isDocument ? null : $this->external_url,
            'cta_label'        => $this->cta_label ?: null,
            'meta_title'       => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'meta_keywords'    => $this->meta_keywords ?: null,
            'is_active'        => (bool) $this->is_active,
            'is_featured'      => (bool) $this->is_featured,
            'sort_order'       => (int) $this->sort_order,
        ];

        if ($this->file) {
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('papers', $originalName, 'public');
        }

        if ($this->featured_image) {
            $data['featured_image'] = $this->featured_image->store('papers/covers', 'public');
        } elseif ($this->remove_featured_image) {
            $data['featured_image'] = null;
        }

        Paper::updateOrCreate(['id' => $this->paperId], $data);

        $this->dispatch('notify', message: 'Resource saved successfully.', type: 'success');
        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $p = Paper::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
    }

    public function toggleFeatured($id)
    {
        $p = Paper::findOrFail($id);
        $p->update(['is_featured' => !$p->is_featured]);

        $this->dispatch(
            'notify',
            message: $p->is_featured ? 'Added to Featured.' : 'Removed from Featured.',
            type: 'success'
        );
    }

    public function deletePaper($id)
    {
        Paper::destroy($id);
        $this->dispatch('notify', message: 'Resource deleted.', type: 'success');
    }

    public function render()
    {
        $papers = Paper::with('category')
            ->when($this->search, function($q) {
                $q->where(fn ($qq) => $qq->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%')
                    ->orWhere('sub_category', 'like', '%'.$this->search.'%'));
            })
            ->when($this->filterCategory, function($q) {
                $q->where('category_id', $this->filterCategory);
            })
            ->when($this->filterStatus !== '', function($q) {
                $q->where('is_active', (bool) $this->filterStatus);
            })
            ->when($this->filterFeatured !== '', function($q) {
                $q->where('is_featured', (bool) $this->filterFeatured);
            })
            ->orderBy('sort_order')->paginate(15);

        $categories = Category::where('type', 'paper')->orderBy('name')->get();

        $featuredCount = Paper::active()->featured()->count();

        return view('livewire.admin.manage-papers', compact('papers', 'categories', 'featuredCount'));
    }
}
