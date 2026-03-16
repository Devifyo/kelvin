<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ConsultingServices extends Component
{
    use WithPagination, WithFileUploads;

    public $searchTitle = '';
    public $filterStatus = 'all'; 

    public $showModal = false;
    public $serviceId = null;
    
    // Form Fields
    public $title, $slug, $icon, $short_description, $content;
    public $meta_title, $meta_description, $meta_keywords; 
    public $is_active = true;
    public $sort_order = 0;
    
    // File Upload for Icon/Image
    public $icon_file; 
    public $existing_icon_path; 

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $this->serviceId,
            'icon' => 'nullable|string', 
            'icon_file' => 'nullable|image|max:2048', // Increased limit to 2MB to prevent silent failures
            'short_description' => 'required|string|max:1000',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000', 
            'meta_keywords' => 'nullable|string|max:500', 
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->serviceId) { 
            $this->slug = Str::slug($value); 
        }
    }

    // Automatically wipe the SVG code box if a user decides to upload an image file instead
    public function updatedIconFile()
    {
        $this->icon = null;
    }

    public function create()
    {
        $this->reset(['serviceId', 'title', 'slug', 'icon', 'icon_file', 'existing_icon_path', 'short_description', 'content', 'meta_title', 'meta_description', 'meta_keywords']);
        $this->is_active = true;
        // Set default sort order to end of list
        $this->sort_order = Service::where('type', 'consulting')->max('sort_order') + 1;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->title = $service->title;
        $this->slug = $service->slug;
        $this->icon = $service->icon;
        $this->existing_icon_path = $service->featured_image; // This populates the DB path
        $this->short_description = $service->short_description;
        $this->content = $service->content;
        $this->meta_title = $service->meta_title;
        $this->meta_description = $service->meta_description;
        $this->meta_keywords = $service->meta_keywords;
        $this->is_active = $service->is_active;
        $this->sort_order = $service->sort_order;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => 'consulting',
            'icon' => $this->icon,
            'short_description' => $this->short_description,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        // Handle Image Upload logic
        if ($this->icon_file) {
            // Delete old file if updating
            if ($this->existing_icon_path) { 
                Storage::disk('public')->delete($this->existing_icon_path); 
            }
            $data['featured_image'] = $this->icon_file->store('services', 'public');
        }

        Service::updateOrCreate(['id' => $this->serviceId], $data);

        $this->dispatch('notify', message: 'Service saved successfully.', type: 'success');
        $this->closeModal();
    }

    public function closeModal() 
    { 
        $this->showModal = false; 
        $this->reset(['icon_file', 'existing_icon_path']); 
    }

    public function setFilter($status) { $this->filterStatus = $status; $this->resetPage(); }
    public function toggleStatus($id) { 
        $s = Service::findOrFail($id); 
        $s->update(['is_active' => !$s->is_active]); 
    }
    public function deleteService($id) { Service::destroy($id); }

    public function render()
    {
        $query = Service::consulting();
        if ($this->searchTitle) { $query->where('title', 'like', '%'.$this->searchTitle.'%'); }
        if ($this->filterStatus === 'active') { $query->where('is_active', true); }
        elseif ($this->filterStatus === 'draft') { $query->where('is_active', false); }

        return view('livewire.admin.consulting-services', [
            'services' => $query->orderBy('sort_order', 'asc')->paginate(10)
        ])->layout('layouts.admin', ['title' => 'Consulting Services']);
    }
}