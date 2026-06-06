<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use App\Models\BlockedEmail;
use Livewire\Component;
use Livewire\WithPagination;

class ContactInquiries extends Component
{
    use WithPagination;

    public $selectedInquiry = null;
    public $showModal = false;

    // Block Modal State
    public $showBlockModal = false;
    public $blockTargetId = null;
    public $blockTargetEmail = '';
    public $blockDuration = 'forever';

    // Search and Filter Properties
    public $searchEmail = '';
    public $filterStatus = 'all'; // 'all', 'unread', 'read', 'blocked'

    public $durationOptions = [
        '1_day'   => '1 Day',
        '2_days'  => '2 Days',
        '1_week'  => '1 Week',
        '1_month' => '1 Month',
        '1_year'  => '1 Year',
        'forever' => 'Until I unblock',
    ];

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

    /**
     * Open the styled block modal for the given inquiry.
     */
    public function openBlockModal($id)
    {
        $inquiry = ContactMessage::findOrFail($id);

        $this->blockTargetId = $inquiry->id;
        $this->blockTargetEmail = $inquiry->email;
        $this->blockDuration = 'forever';
        $this->showBlockModal = true;
    }

    public function closeBlockModal()
    {
        $this->showBlockModal = false;
        $this->blockTargetId = null;
        $this->blockTargetEmail = '';
        $this->blockDuration = 'forever';
    }

    /**
     * Persist the block using the duration chosen in the modal.
     */
    public function confirmBlock()
    {
        $inquiry = ContactMessage::findOrFail($this->blockTargetId);

        $blockedUntil = match ($this->blockDuration) {
            '1_day'   => now()->addDay(),
            '2_days'  => now()->addDays(2),
            '1_week'  => now()->addWeek(),
            '1_month' => now()->addMonth(),
            '1_year'  => now()->addYear(),
            default   => null, // 'forever' — until an admin unblocks
        };

        BlockedEmail::updateOrCreate(
            ['email' => $inquiry->email],
            ['blocked_until' => $blockedUntil]
        );

        $until = $blockedUntil ? 'until ' . $blockedUntil->format('M d, Y') : 'indefinitely';
        $this->closeBlockModal();
        $this->dispatch('notify', message: "{$inquiry->email} blocked {$until}.", type: 'success');
    }

    public function unblockEmail($id)
    {
        $inquiry = ContactMessage::findOrFail($id);
        BlockedEmail::where('email', $inquiry->email)->delete();

        $this->dispatch('notify', message: "{$inquiry->email} has been unblocked.", type: 'success');
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
        } elseif ($this->filterStatus === 'blocked') {
            // Only inquiries whose email currently has an active block.
            $query->whereIn('email', BlockedEmail::active()->pluck('email'));
        }

        $inquiries = $query->latest()->paginate(10);

        // Resolve the active blocks for the emails shown on this page, keyed by
        // email. Each record carries created_at ("blocked since") and
        // blocked_until (null = indefinite) for badge/tooltip display.
        $blockedEmails = BlockedEmail::active()
            ->whereIn('email', $inquiries->pluck('email')->unique())
            ->get()
            ->keyBy('email');

        return view('livewire.admin.contact-inquiries', [
            'inquiries'      => $inquiries,
            'blockedEmails'  => $blockedEmails,
        ])->layout('layouts.admin', ['title' => 'Contact Inquiries']);
    }
}