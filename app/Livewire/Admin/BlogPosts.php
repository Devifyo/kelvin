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
            'meta_description' => 'nullable|string|max:1000', 
            'meta_keywords' => 'nullable|string|max:500', 
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

    public function render()
    {
        $query = Post::with('category', 'author');
        if ($this->searchTitle) { $query->where('title', 'like', '%'.$this->searchTitle.'%'); }
        if ($this->filterStatus !== 'all') { $query->where('status', $this->filterStatus); }

        return view('livewire.admin.blog-posts', [
            'posts' => $query->orderBy('created_at', 'desc')->paginate(10),
            'categories' => Category::all()
        ]);
    }
}