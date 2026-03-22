<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardServices;
class DashboardController extends Controller
{
    public $dashboardServices;
    public function __construct(DashboardServices $dashboardServices)
    {
        $this->dashboardServices = $dashboardServices;
    }

    public function index()
    {   
        $stats = $this->dashboardServices->getDashboardData();
        $inquiries = $this->dashboardServices->getContactMessagesData();
        return view('admin.dashboard', compact('stats', 'inquiries'));
    }
}
