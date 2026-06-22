<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithFileUploads, WithPagination;

    public $searchName = '';

    public $filterStatus = 'all'; // all | active | inactive | featured

    public $showModal = false;

    public $clientId = null;

    // Form fields
    public $name;

    public $website_url;

    public $description;

    public $is_featured = false;

    public $sort_order = 0;

    public $status = 'active';

    // Logo upload
    public $logo_file;          // Temporary uploaded file

    public $existing_logo_path; // Stored DB path when editing

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // Logo is required when creating, optional (keep existing) when editing.
            'logo_file' => ($this->clientId ? 'nullable' : 'required').'|image|max:2048',
            'website_url' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'status' => 'required|in:active,inactive',
        ];
    }

    // --- Drag & drop ordering ---
    public function updateSortOrder($orderedIds): void
    {
        if (empty($orderedIds)) {
            return;
        }

        $minSortOrder = Client::whereIn('id', $orderedIds)->min('sort_order') ?? 0;

        foreach ($orderedIds as $index => $id) {
            Client::where('id', $id)->update(['sort_order' => $minSortOrder + $index]);
        }

        Client::clearCache();
        $this->dispatch('notify', message: 'Order updated successfully.', type: 'success');
    }

    public function create(): void
    {
        $this->reset([
            'clientId', 'name', 'website_url', 'description',
            'logo_file', 'existing_logo_path',
        ]);
        $this->is_featured = false;
        $this->status = 'active';
        $this->sort_order = (Client::max('sort_order') ?? 0) + 1;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->website_url = $client->website_url;
        $this->description = $client->description;
        $this->is_featured = $client->is_featured;
        $this->sort_order = $client->sort_order;
        $this->status = $client->status;
        $this->existing_logo_path = $client->logo;
        $this->logo_file = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'website_url' => $this->website_url,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];

        if ($this->logo_file) {
            // Remove the previous logo when replacing it.
            if ($this->existing_logo_path) {
                Storage::disk('public')->delete($this->existing_logo_path);
            }

            $path = $this->logo_file->store('clients', 'public');
            ImageOptimizer::optimize($path); // Resize + compress the freshly uploaded logo.
            $data['logo'] = $path;
        }

        Client::updateOrCreate(['id' => $this->clientId], $data);
        Client::clearCache();

        $this->dispatch('notify', message: 'Client saved successfully.', type: 'success');
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['logo_file', 'existing_logo_path']);
    }

    public function setFilter($status): void
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function toggleStatus($id): void
    {
        $client = Client::findOrFail($id);
        $client->update(['status' => $client->status === 'active' ? 'inactive' : 'active']);
        Client::clearCache();
    }

    public function toggleFeatured($id): void
    {
        $client = Client::findOrFail($id);
        $client->update(['is_featured' => ! $client->is_featured]);
        Client::clearCache();
    }

    public function deleteClient($id): void
    {
        $client = Client::find($id);
        if ($client) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
            $client->delete();
            Client::clearCache();
        }
    }

    public function render()
    {
        $query = Client::query();

        if ($this->searchName) {
            $query->where('name', 'like', '%'.$this->searchName.'%');
        }

        if ($this->filterStatus === 'active') {
            $query->where('status', 'active');
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($this->filterStatus === 'featured') {
            $query->where('is_featured', true);
        }

        return view('livewire.admin.clients', [
            // onEachSide(1) keeps huge page counts compact: « 1 … 5 6 7 … 20 »
            'clients' => $query->ordered()->paginate(12)->onEachSide(1),
        ])->layout('layouts.admin', ['title' => 'Client Showcase']);
    }
}
