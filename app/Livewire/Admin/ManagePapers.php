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

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'new_category_name' => 'required_if:category_id,new|nullable|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'required|string|max:2000',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:10240', // 10MB
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
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
        $this->is_active = $paper->is_active;
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
            $data['file_path'] = $this->file->store('papers', 'public');
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
        // Fetch papers and eager load categories
        $papers = Paper::with('category')
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })->orderBy('sort_order')->paginate(15);

        // Pass only 'paper' type categories to the view
        $categories = Category::where('type', 'paper')->orderBy('name')->get();

        return view('livewire.admin.manage-papers', compact('papers', 'categories'));
    }
}