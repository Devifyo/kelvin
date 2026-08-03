<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Edit surface for the public post-submission confirmation page
 * (/contact-us/thank-you). The page is intentionally noindex; it exists so a
 * contact submission lands on a distinct URL that ad/analytics goals can count.
 *
 * Copy lives in the cached AppSetting store; the public view reads the same
 * keys with these defaults as fallbacks.
 */
#[Layout('layouts.admin', ['title' => 'Thank You Page'])]
class ThankYouPageSettings extends Component
{
    public $ty_kicker;

    public $ty_heading;

    public $ty_body;

    public $ty_button_text;

    public $ty_button_link;

    /** AppSetting-backed fields: property => [setting key, default]. */
    public const SETTINGS = [
        'ty_kicker'      => ['ty_kicker', 'Message Received'],
        'ty_heading'     => ['ty_heading', 'Thank you for your inquiry.'],
        'ty_body'        => ['ty_body', "We've received your message and will respond within one working day."],
        'ty_button_text' => ['ty_button_text', 'Back to Home'],
        'ty_button_link' => ['ty_button_link', '/'],
    ];

    public function mount(): void
    {
        foreach (self::SETTINGS as $prop => [$key, $default]) {
            $this->{$prop} = AppSetting::get($key, $default);
        }
    }

    public function save(): void
    {
        $this->validate([
            'ty_kicker'      => 'nullable|string|max:120',
            'ty_heading'     => 'required|string|max:180',
            'ty_body'        => 'nullable|string|max:600',
            'ty_button_text' => 'nullable|string|max:60',
            'ty_button_link' => 'nullable|string|max:500',
        ], [
            'ty_heading.required' => 'The heading is required — this page needs an H1.',
        ]);

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            AppSetting::set($key, $this->{$prop});
        }

        $this->dispatch('notify', message: 'Thank-you page updated.', type: 'success');
    }

    public function resetSection(): void
    {
        foreach (self::SETTINGS as $prop => [$key, $default]) {
            $this->{$prop} = $default;
        }

        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.thank-you-page-settings');
    }
}
