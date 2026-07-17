<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class BlogPosts extends Component
{
    use WithPagination, WithFileUploads;

    public $searchTitle = '';
    public $filterStatus = 'all'; 

    public $showModal = false;
    public $postId = null;
    
    // Post Fields
    public $title, $slug, $excerpt, $content, $category_id;
    public $new_category_name; 
    
    // File Uploads
    public $featured_image; 
    public $existing_featured_image; 
    
    public $attachment; 
    public $existing_attachment; 

    // SEO & Publishing
    public $meta_title, $meta_description, $meta_keywords; 
    public $status = 'published';
    
    // New Timezone-Safe Properties
    public $publish_strategy = 'now'; // 'now' or 'custom'
    public $published_at; // Will hold the UTC ISO String

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $this->postId,
            'category_id' => 'required',
            'new_category_name' => 'required_if:category_id,new|nullable|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,inactive',
            'publish_strategy' => 'required|in:now,custom',
            'published_at' => 'nullable|string', // Validated as string because JS sends an ISO timestamp
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->postId) { $this->slug = Str::slug($value); }
    }

    public function create()
    {
        $this->reset(['postId', 'title', 'slug', 'excerpt', 'content', 'category_id', 'new_category_name', 'featured_image', 'existing_featured_image', 'attachment', 'existing_attachment', 'meta_title', 'meta_description', 'meta_keywords']);
        $this->status = 'published';
        $this->publish_strategy = 'now';
        $this->published_at = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->category_id = $post->category_id;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        
        $this->existing_featured_image = $post->featured_image_url;
        $this->existing_attachment = $post->attachment_url;
        
        $this->meta_title = $post->meta_title;
        $this->meta_description = $post->meta_description;
        $this->meta_keywords = $post->meta_keywords;
        $this->status = $post->status;
        
        // Setup Date Logic for Edit Modal
        if ($post->published_at) {
            $this->publish_strategy = 'custom';
            // Send the exact UTC string to the frontend so Flatpickr can convert it to local time
            $this->published_at = $post->published_at->toISOString();
        } else {
            $this->publish_strategy = 'now';
            $this->published_at = null;
        }

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // 1. Handle dynamic Category Creation
        $finalCategoryId = $this->category_id;
        if ($this->category_id === 'new' && !empty($this->new_category_name)) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($this->new_category_name)],
                ['name' => $this->new_category_name, 'type' => 'blog']
            );
            $finalCategoryId = $category->id;
        }

        // 2. Parse the Date safely in UTC
        $finalPublishDate = null;
        if ($this->publish_strategy === 'now') {
            $finalPublishDate = now();
        } elseif ($this->publish_strategy === 'custom' && $this->published_at) {
            // Carbon perfectly understands the ISO string sent from JS and converts it to UTC
            $finalPublishDate = Carbon::parse($this->published_at)->setTimezone('UTC');
        }

        // 3. Grab existing post data
        $postData = [
            'user_id' => auth()->id(),
            'category_id' => $finalCategoryId,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'status' => $this->status,
            'published_at' => $finalPublishDate,
        ];

        // 4. Handle File Uploads securely
        if ($this->featured_image) {
            $postData['featured_image'] = $this->featured_image->store('blog/featured', 'public');
        }
        if ($this->attachment) {
            $postData['attachment'] = $this->attachment->store('blog/attachments', 'public');
            $postData['attachment_mime_type'] = $this->attachment->getMimeType();
        }

        // 5. Save to Database
        Post::updateOrCreate(['id' => $this->postId], $postData);

        $this->dispatch('notify', message: 'Blog post saved successfully.', type: 'success');
        $this->closeModal();
    }

    public function closeModal() { $this->showModal = false; }
    public function setFilter($status) { $this->filterStatus = $status; $this->resetPage(); }
    
    public function toggleStatus($id) { 
        $post = Post::findOrFail($id); 
        $post->update(['status' => $post->status === 'published' ? 'draft' : 'published']); 
    }
    
    public function deletePost($id) {
        Post::destroy($id);
    }

    public function reorder(array $ids, int $offset = 0): void
    {
        $this->applyOrder($ids, $offset);
        $this->dispatch('notify', message: 'Post order saved.', type: 'success');
    }

    /** Write sort_order values without notifying (shared by drag + move-to-end actions). */
    private function applyOrder(array $ids, int $offset = 0): void
    {
        foreach ($ids as $index => $id) {
            Post::where('id', $id)->update(['sort_order' => $offset + $index]);
        }
    }

    /** Full list of post ids in the exact order the listing displays them (ignores pagination). */
    private function orderedIds(): array
    {
        return Post::orderByRaw('(sort_order IS NULL) ASC, sort_order ASC, created_at DESC')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Swap a post with whatever currently sits at the given 1-based position.
     * Works across pages: type "1" on the post at position 11 and the two trade places —
     * position-1's post moves to 11, this post moves to 1; everything else stays put.
     * The list is renumbered densely so positions stay 1,2,3… with no gaps.
     */
    public function moveToPosition($id, $position): void
    {
        if ($position === null || $position === '' || ! is_numeric($position)) {
            return;
        }

        $id  = (int) $id;
        $ids = $this->orderedIds();

        $curIdx = array_search($id, $ids, true);
        if ($curIdx === false) {
            return;
        }

        // Clamp to an existing slot (1 … count) and no-op if it's already there.
        $targetIdx = max(1, min((int) $position, count($ids))) - 1;
        if ($targetIdx === $curIdx) {
            return;
        }

        // Swap the two positions.
        [$ids[$curIdx], $ids[$targetIdx]] = [$ids[$targetIdx], $ids[$curIdx]];
        $this->applyOrder($ids);

        // Follow the post to whichever page it now lives on so the user sees the result.
        $this->gotoPage((int) ceil(($targetIdx + 1) / 10));
        $this->dispatch('notify', message: 'Moved to position '.($targetIdx + 1).'.', type: 'success');
    }

    public function render()
    {
        $query = Post::with('category', 'author');
        if ($this->searchTitle) { $query->where('title', 'like', '%'.$this->searchTitle.'%'); }
        if ($this->filterStatus !== 'all') { $query->where('status', $this->filterStatus); }

        if ($this->searchTitle) {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderByRaw('(sort_order IS NULL) ASC, sort_order ASC, created_at DESC');
        }

        return view('livewire.admin.blog-posts', [
            'posts' => $query->paginate(10),
            'categories' => Category::all()
        ]);
    }
}