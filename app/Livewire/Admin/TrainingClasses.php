<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class TrainingClasses extends Component
{
    use WithPagination;

    public $searchTitle = '';
    public $filterStatus = 'all'; 

    public $showModal = false;
    public $serviceId = null;
    
    // Core Fields
    public $title, $slug, $short_description, $content;
    
    // Training Specific Fields
    public $learning_objectives, $audience, $prerequisites, $length;
    
    // Topics Builder (Dynamic Array)
    public $topicGroups = []; 

    // SEO & Publishing
    public $meta_title, $meta_description, $meta_keywords; 
    public $is_active = true;
    public $sort_order = 0;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $this->serviceId,
            'short_description' => 'required|string|max:1000',
            'content' => 'required|string',
            'learning_objectives' => 'nullable|string',
            'audience' => 'nullable|string|max:500',
            'prerequisites' => 'nullable|string|max:500',
            'length' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->serviceId) { $this->slug = Str::slug($value); }
    }

    // --- Dynamic Topics Builder Methods ---
    public function addTopicGroup()
    {
        $this->topicGroups[] = ['name' => '', 'items' => ['']];
    }

    public function removeTopicGroup($index)
    {
        unset($this->topicGroups[$index]);
        $this->topicGroups = array_values($this->topicGroups); // re-index
    }

    public function addTopicItem($groupIndex)
    {
        $this->topicGroups[$groupIndex]['items'][] = '';
    }

    public function removeTopicItem($groupIndex, $itemIndex)
    {
        unset($this->topicGroups[$groupIndex]['items'][$itemIndex]);
        $this->topicGroups[$groupIndex]['items'] = array_values($this->topicGroups[$groupIndex]['items']);
    }

    // --- Handle Drag and Drop Sorting ---
    public function updateSortOrder($orderedIds)
    {
        if (empty($orderedIds)) return;

        // Get the lowest sort_order of the current visible items to offset correctly (safeguards pagination logic)
        $minSortOrder = Service::whereIn('id', $orderedIds)->min('sort_order');

        // Apply the new order
        foreach ($orderedIds as $index => $id) {
            Service::where('id', $id)->update(['sort_order' => $minSortOrder + $index]);
        }
        
        // Let the front-end know it saved 
        $this->dispatch('notify', message: 'Order updated successfully.', type: 'success');
    }

    public function create()
    {
        $this->reset(['serviceId', 'title', 'slug', 'short_description', 'content', 'learning_objectives', 'audience', 'prerequisites', 'length', 'meta_title', 'meta_description', 'meta_keywords', 'topicGroups']);
        $this->is_active = true;
        $this->sort_order = Service::training()->max('sort_order') + 1;
        $this->topicGroups = [['name' => '', 'items' => ['']]]; // Start with one empty group
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->title = $service->title;
        $this->slug = $service->slug;
        $this->short_description = $service->short_description;
        $this->content = $service->content;
        $this->learning_objectives = $service->learning_objectives;
        $this->audience = $service->audience;
        $this->prerequisites = $service->prerequisites;
        $this->length = $service->length;
        $this->meta_title = $service->meta_title;
        $this->meta_description = $service->meta_description;
        $this->meta_keywords = $service->meta_keywords;
        $this->is_active = $service->is_active;
        $this->sort_order = $service->sort_order;

        // Map Associative DB Array to Indexed Livewire Array
        $this->topicGroups = [];
        if (is_array($service->topics)) {
            foreach ($service->topics as $groupName => $items) {
                $this->topicGroups[] = [
                    'name' => $groupName,
                    'items' => is_array($items) ? array_values($items) : []
                ];
            }
        }

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Map Indexed Livewire Array back to Associative DB Array
        $formattedTopics = [];
        foreach ($this->topicGroups as $group) {
            $groupName = trim($group['name']);
            if ($groupName !== '') {
                // Filter out empty topic items
                $items = array_values(array_filter($group['items'], fn($item) => trim($item) !== ''));
                $formattedTopics[$groupName] = $items;
            }
        }

        Service::updateOrCreate(
            ['id' => $this->serviceId],
            [
                'title' => $this->title,
                'slug' => $this->slug,
                'type' => 'training', // Always 'training' for this module
                'short_description' => $this->short_description,
                'content' => $this->content,
                'learning_objectives' => $this->learning_objectives,
                'audience' => $this->audience,
                'prerequisites' => $this->prerequisites,
                'length' => $this->length,
                'topics' => empty($formattedTopics) ? null : $formattedTopics,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]
        );

        $this->dispatch('notify', message: 'Training class saved successfully.', type: 'success');
        $this->closeModal();
    }

    public function closeModal() { $this->showModal = false; }
    public function setFilter($status) { $this->filterStatus = $status; $this->resetPage(); }
    public function toggleStatus($id) { $s = Service::findOrFail($id); $s->update(['is_active' => !$s->is_active]); }
    public function deleteService($id) { Service::destroy($id); }

    public function render()
    {   
        $query = Service::training();
        if ($this->searchTitle) { $query->where('title', 'like', '%'.$this->searchTitle.'%'); }
        if ($this->filterStatus === 'active') { $query->where('is_active', true); }
        elseif ($this->filterStatus === 'draft') { $query->where('is_active', false); }

        return view('livewire.admin.training-classes', [
            'services' => $query->orderBy('sort_order', 'asc')->paginate(10)
        ])->layout('layouts.admin', ['title' => 'Training Classes']);
    }
}