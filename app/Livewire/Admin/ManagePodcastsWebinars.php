<?php

namespace App\Livewire\Admin;

use App\Models\PodcastWebinar;
use Illuminate\Support\Facades\Storage;
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

    // Video source toggle: 'url' or 'upload'
    public string $videoSource = 'url';
    public $videoFile = null;
    public ?string $existing_video = null;

    protected function rules()
    {
        return [
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:podcast,webinar,interview',
            'platform'        => 'nullable|string|max:255',
            'url'             => $this->videoSource === 'url' ? 'required|url|max:500' : 'nullable',
            'videoFile'       => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:512000',
            'description'     => 'nullable|string|max:1000',
            'thumbnail_image' => 'nullable|image|max:2048',
            'published_date'  => 'nullable|date',
            'is_active'       => 'boolean',
        ];
    }

    public function create()
    {
        $this->reset(['mediaId', 'title', 'type', 'platform', 'url', 'videoFile', 'existing_video', 'description', 'thumbnail_image', 'existing_thumbnail', 'published_date']);
        $this->is_active = true;
        $this->type = 'podcast';
        $this->videoSource = 'url';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $media = PodcastWebinar::findOrFail($id);
        $this->mediaId          = $media->id;
        $this->title            = $media->title;
        $this->type             = $media->type;
        $this->platform         = $media->platform;
        $this->url              = $media->url;
        $this->description      = $media->description;
        $this->existing_thumbnail = $media->thumbnail_url;
        $this->published_date   = $media->published_date ? $media->published_date->format('Y-m-d') : null;
        $this->is_active        = (bool) $media->is_active;

        if ($media->video_path) {
            $this->videoSource  = 'upload';
            $this->existing_video = $media->video_url;
        } else {
            $this->videoSource  = 'url';
            $this->existing_video = null;
        }
        $this->videoFile = null;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->videoSource === 'upload' && !$this->videoFile && !$this->existing_video) {
            $this->addError('videoFile', 'Please upload a video file.');
            return;
        }

        $data = [
            'title'          => $this->title,
            'type'           => $this->type,
            'platform'       => $this->platform,
            'description'    => $this->description,
            'published_date' => $this->published_date,
            'is_active'      => $this->is_active,
        ];

        if ($this->videoSource === 'url') {
            $data['url']        = $this->url;
            $data['video_path'] = null;
            // Delete old uploaded video if switching from upload to url
            if ($this->mediaId) {
                $old = PodcastWebinar::find($this->mediaId);
                if ($old?->video_path) {
                    Storage::disk('public')->delete($old->video_path);
                }
            }
        } else {
            $data['url'] = null;
            if ($this->videoFile) {
                if ($this->mediaId) {
                    $old = PodcastWebinar::find($this->mediaId);
                    if ($old?->video_path) {
                        Storage::disk('public')->delete($old->video_path);
                    }
                }
                $data['video_path'] = $this->videoFile->store('media/videos', 'public');
            }
            // No new file → keep existing video_path unchanged (omit from $data)
        }

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