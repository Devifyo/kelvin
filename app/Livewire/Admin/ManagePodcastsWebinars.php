<?php

namespace App\Livewire\Admin;

use App\Models\PodcastWebinar;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManagePodcastsWebinars extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $mediaId = null;

    // Form Fields
    public $title, $type = 'podcast', $platform, $url, $description;
    public $thumbnail_image, $existing_thumbnail;
    public $published_date, $is_active = true;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:podcast,webinar,interview',
            'platform' => 'nullable|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string|max:1000',
            'thumbnail_image' => 'nullable|image|max:2048', 
            'published_date' => 'nullable|date',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->reset(['mediaId', 'title', 'type', 'platform', 'url', 'description', 'thumbnail_image', 'existing_thumbnail', 'published_date']);
        $this->is_active = true;
        $this->type = 'podcast';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $media = PodcastWebinar::findOrFail($id);
        $this->mediaId = $media->id;
        $this->title = $media->title;
        $this->type = $media->type;
        $this->platform = $media->platform;
        $this->url = $media->url;
        $this->description = $media->description;
        $this->existing_thumbnail = $media->thumbnail_url;
        $this->published_date = $media->published_date ? $media->published_date->format('Y-m-d') : null;
        $this->is_active = (bool) $media->is_active;
        
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'platform' => $this->platform,
            'url' => $this->url,
            'description' => $this->description,
            'published_date' => $this->published_date,
            'is_active' => $this->is_active,
        ];

        if ($this->thumbnail_image) {
            $data['thumbnail_image'] = $this->thumbnail_image->store('media/thumbnails', 'public');
        }

        PodcastWebinar::updateOrCreate(['id' => $this->mediaId], $data);

        $this->dispatch('notify', message: 'Saved successfully.', type: 'success');
        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $media = PodcastWebinar::findOrFail($id);
        $media->update(['is_active' => !$media->is_active]);
    }

    public function deleteMedia($id)
    {
        PodcastWebinar::destroy($id);
    }

    public function render()
    {
        $mediaItems = PodcastWebinar::when($this->search, function($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->orderBy('published_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.manage-podcasts-webinars', compact('mediaItems'));
    }
}