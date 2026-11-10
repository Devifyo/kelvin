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

    // --- Ordering helpers ---------------------------------------------------
    // Every write goes through applyOrder(), which renumbers the WHOLE list to a
    // dense 1..n sequence. That keeps Display Order free of the duplicates and
    // gaps that used to accumulate (and then broke the per-page drag math).

    /** Every client id in current display order (same ordering the admin list uses). */
    private function orderedIds(): array
    {
        return Client::orderBy('sort_order')->orderBy('name')->orderBy('id')
            ->pluck('id')->map(fn ($i) => (int) $i)->all();
    }

    /** Persist a full ordering as dense 1..n Display Order values. */
    private function applyOrder(array $ids): void
    {
        foreach (array_values($ids) as $i => $id) {
            Client::where('id', $id)->update(['sort_order' => $i + 1]);
        }
    }

    /** Insert one client at a 1-based position, shifting the rest. */
    private function moveToPosition(int $id, int $position): void
    {
        $ids = array_values(array_filter($this->orderedIds(), fn ($x) => $x !== $id));
        $pos = $position > 0 ? min($position, count($ids) + 1) : count($ids) + 1;
        array_splice($ids, $pos - 1, 0, [$id]);
        $this->applyOrder($ids);
    }

    // --- Drag & drop ordering ---
    public function updateSortOrder($orderedIds): void
    {
        $orderedIds = array_values(array_map('intval', (array) $orderedIds));
        if (empty($orderedIds)) {
            return;
        }

        // The dragged page's items keep their slots in the overall sequence but
        // take the new order among themselves; everything is then renumbered
        // densely, so items on other pages can never collide.
        $all   = $this->orderedIds();
        $queue = $orderedIds;
        $slots = array_flip($orderedIds);
        foreach ($all as $i => $id) {
            if (isset($slots[$id])) {
                $all[$i] = array_shift($queue);
            }
        }
        $this->applyOrder($all);

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

        $client = Client::updateOrCreate(['id' => $this->clientId], $data);

        // Display Order is a POSITION: place the client there and renumber the
        // whole list 1..n so no duplicates or gaps can be introduced by hand.
        $this->moveToPosition($client->id, (int) $this->sort_order);

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
