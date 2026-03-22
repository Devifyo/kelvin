<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\DashboardServices;
use App\Models\ContactMessage;

class DashboardOverview extends Component
{
    public $showModal = false;
    public $selectedInquiry = null;

    public function viewMessage($id)
    {
        // Find the specific message
        $message = ContactMessage::find($id);
        
        if ($message) {
            // Mark as read if it isn't already
            if (!$message->is_read) {
                $message->update(['is_read' => true]);
            }
            
            // Set the data and open the modal
            $this->selectedInquiry = $message;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedInquiry = null;
    }

    public function render(DashboardServices $dashboardServices)
    {
        return view('livewire.admin.dashboard-overview', [
            'stats' => $dashboardServices->getDashboardData(),
            'inquiries' => $dashboardServices->getContactMessagesData(10)
        ]);
    }
}