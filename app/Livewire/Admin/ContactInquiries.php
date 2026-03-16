<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class ContactInquiries extends Component
{
    use WithPagination;

    public $selectedInquiry = null;
    public $showModal = false;

    // Search and Filter Properties
    public $searchEmail = '';
    public $filterStatus = 'all'; // 'all', 'unread', 'read'

    // Reset pagination when searching or filtering
    public function updatedSearchEmail()
    {
        $this->resetPage();
    }

    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function viewInquiry($id)
    {
        $this->selectedInquiry = ContactMessage::findOrFail($id);
        
        if (!$this->selectedInquiry->is_read) {
            $this->selectedInquiry->update(['is_read' => true]);
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedInquiry = null;
    }

    public function deleteInquiry($id)
    {
        ContactMessage::destroy($id);
        $this->dispatch('notify', message: 'Inquiry removed permanently.', type: 'success');
    }

    public function render()
    {
        $query = ContactMessage::query();

        // Apply Email Search
        if (!empty($this->searchEmail)) {
            $query->where('email', 'like', '%' . $this->searchEmail . '%');
        }

        // Apply Status Filter
        if ($this->filterStatus === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filterStatus === 'read') {
            $query->where('is_read', true);
        }

        return view('livewire.admin.contact-inquiries', [
            'inquiries' => $query->latest()->paginate(10)
        ])->layout('layouts.admin', ['title' => 'Contact Inquiries']);
    }
}