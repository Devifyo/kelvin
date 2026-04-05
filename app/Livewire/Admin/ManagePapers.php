<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManagePapers extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $paperId = null;

    // Form Fields
    public $title, $category_id, $new_category_name, $sub_category, $description;
    public $file, $existing_file;
    public $is_active = true, $sort_order = 0;
    public $filterCategory = '';
    public $filterStatus = '';

    public function setStatusFilter(string $value): void
    {
        $this->filterStatus = $value;
        $this->resetPage();
    }
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'new_category_name' => 'required_if:category_id,new|nullable|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'required|string|max:2000',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:30240', // 10MB
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
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
        $this->reset(['paperId', 'title', 'category_id', 'new_category_name', 'sub_category', 'description', 'file', 'existing_file']);
        $this->is_active = true;
        $this->sort_order = Paper::max('sort_order') + 1;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $paper = Paper::findOrFail($id);
        $this->paperId = $paper->id;
        $this->title = $paper->title;
        $this->category_id = $paper->category_id;
        $this->sub_category = $paper->sub_category;
        $this->description = $paper->description;
        $this->existing_file = $paper->file_url;
        $this->is_active = (bool) $paper->is_active;
        $this->sort_order = $paper->sort_order;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
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

        $data = [
            'title' => $this->title,
            'category_id' => $finalCategoryId,
            'sub_category' => $this->sub_category,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->file) {
            
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('papers', $originalName, 'public');
        }

        Paper::updateOrCreate(['id' => $this->paperId], $data);

        $this->dispatch('notify', message: 'Document saved successfully.', type: 'success');
        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $p = Paper::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
    }

    public function deletePaper($id)
    {
        Paper::destroy($id);
    }

    public function render()
    {
        $papers = Paper::with('category')
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterCategory, function($q) {
                $q->where('category_id', $this->filterCategory);
            })
            ->when($this->filterStatus !== '', function($q) {
                $q->where('is_active', (bool) $this->filterStatus);
            })
            ->orderBy('sort_order')->paginate(15);

        $categories = Category::where('type', 'paper')->orderBy('name')->get();

        return view('livewire.admin.manage-papers', compact('papers', 'categories'));
    }
}