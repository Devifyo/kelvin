<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Save to the Database
        $contactMessage = ContactMessage::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // 3. Dispatch an email to the client 
        // Using Mail::raw for simplicity, but you can convert this to a Mailable later
        $emailContent = "You have received a new website inquiry.\n\n"
                      . "Name: {$contactMessage->name}\n"
                      . "Email: {$contactMessage->email}\n"
                      . "Subject: {$contactMessage->subject}\n"
                      . "Message:\n{$contactMessage->message}";

        Mail::raw($emailContent, function ($mail) use ($contactMessage) {
            $mail->to('kevin@kevinthompsonphd.com')
                 ->subject('New Website Inquiry: ' . $contactMessage->subject)
                 ->replyTo($contactMessage->email); // Allows Kevin to hit "Reply" directly to the sender
        });

        // 4. Redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you shortly.');
    }
}