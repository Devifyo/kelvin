<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class VisaController extends Controller
{
    public function showEasterSunday(Request $request)
    {
        $agent = new Agent();
        $ip = $request->ip();
        $location = 'Unknown';
        
        // 1. Fetch IP Location directly in the controller
        if ($ip !== '127.0.0.1' && $ip !== '::1') {
            try {
                // Using ip-api.com (Free, no API key needed)
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                if ($response->successful() && $response->json('status') === 'success') {
                    $data = $response->json();
                    $location = $data['city'] . ', ' . $data['regionName'] . ', ' . $data['country'];
                }
            } catch (\Exception $e) {
                // If API fails, fail silently so the page still loads
            }
        } else {
            $location = 'Localhost (Testing)';
        }

        // 2. Gather Data (Time forced to Indian Standard Time)
        $deviceInfo = [
            'Time (IST)' => now()->timezone('Asia/Kolkata')->format('d-M-Y g:i:s A'),
            'IP Address' => $ip,
            'Location'   => $location,
            'Is Mobile'  => $agent->isMobile() ? 'Yes' : 'No',
            'Device/Model' => $agent->device() ?: 'Unknown',
            'OS' => $agent->platform() ?: 'Unknown',
            'Browser' => $agent->browser() ?: 'Unknown',
            'Full User Agent' => $request->userAgent()
        ];

        // 3. Log to a SEPARATE custom file (storage/logs/easter.log)
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/easter.log'),
        ])->info('Bulbula Opened The Easter Letter!', $deviceInfo);

        // Return the HTML file
        return view('visa.eater-sunday');
    }
}