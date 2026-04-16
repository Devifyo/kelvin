<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\DashboardServices;
use App\Models\ContactMessage;
use App\Models\VisitorLog;

class DashboardOverview extends Component
{
    public $showModal = false;
    public $selectedInquiry = null;
    public $visitorPeriod = 'today';

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

    private function mapPeriod(): string
    {
        return match ($this->visitorPeriod) {
            '7days'  => 'week',
            '1month' => 'month',
            default  => 'today',
        };
    }

    public function render(DashboardServices $dashboardServices)
    {
        $period = $this->mapPeriod();

        return view('livewire.admin.dashboard-overview', [
            'stats'         => $dashboardServices->getDashboardData(),
            'inquiries'     => $dashboardServices->getContactMessagesData(10),
            'visitorStats'  => VisitorLog::stats($period),
            'topCountries'  => VisitorLog::topCountries($period),
        ]);
    }
}