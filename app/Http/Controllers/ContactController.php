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

    /**
     * Post-submission confirmation page. Has its own indexable-free URL so ad
     * platforms / analytics can record a reached-this-URL conversion.
     *
     * Only reachable immediately after an actual submission (a one-time session
     * flag set by store()), so bookmarks, refreshes and bots can't inflate the
     * conversion count. Logged-in admins may open it directly for testing.
     */
    public function thankYou()
    {
        if (! session('contact_submitted') && ! auth()->check()) {
            return redirect()->route('contact');
        }

        return view('landing-pages.thank-you');
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

        // Redirect to the dedicated thank-you URL so the submission registers as
        // a distinct pageview conversion (ad goals / analytics). The one-time flag
        // gates that page so only a real submission can reach it.
        //
        // For Google Enhanced Conversions for Leads we also pass the lead's email
        // hashed (SHA-256, normalised) — never the raw address — so the thank-you
        // page can attach it to the conversion. Google matches offline lead
        // outcomes back to the ad click via this hash.
        $emailHash = hash('sha256', strtolower(trim($validated['email'])));

        return redirect()->route('contact.thankyou')
            ->with('contact_submitted', true)
            ->with('ec_email_hash', $emailHash);
    }
}