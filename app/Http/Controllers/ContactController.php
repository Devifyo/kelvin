<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\BlockedEmail;
use App\Mail\ContactInquiryMail;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

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
            'captcha' => ['required', 'string', new Captcha],
        ]);

        // Reject submissions from emails an admin has blocked.
        if (BlockedEmail::isBlocked($validated['email'])) {
            throw ValidationException::withMessages([
                'email' => 'This email address is not permitted to submit the contact form. Please reach out to us directly if you believe this is a mistake.',
            ]);
        }

        // The captcha answer is only a gate — never persist or mail it.
        $data = collect($validated)->only(['name', 'email', 'subject', 'message'])->all();

        ContactMessage::create($data);

        // Fetching the email from config instead of hardcoding
        $adminEmail = config('mail.admin_address');

        Mail::to($adminEmail)->send(new ContactInquiryMail($data));

        return back()->with('success', 'Thank you for your message. We will get back to you shortly.');
    }
}