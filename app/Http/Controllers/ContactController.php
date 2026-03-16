<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\ContactInquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{   

    /**
     * Display the contact form page.
     */
    public function show()
    {
        return view('landing-pages.contact');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        // Fetching the email from config instead of hardcoding
        $adminEmail = config('mail.admin_address');

        Mail::to($adminEmail)->send(new ContactInquiryMail($validated));

        return back()->with('success', 'Thank you for your message. We will get back to you shortly.');
    }
}