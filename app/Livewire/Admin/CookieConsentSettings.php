<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Site-wide cookie consent banner (Google Consent Mode v2).
 *
 * The banner text/labels and on/off state live in the cached AppSetting store.
 * The public partial (layouts.partials.frontend.cookie-consent) reads the same
 * keys with these defaults; the layout head sets consent defaults to "denied"
 * until the visitor accepts.
 */
#[Layout('layouts.admin', ['title' => 'Cookie Banner'])]
class CookieConsentSettings extends Component
{
    public bool $cc_enabled = true;

    public $cc_heading;

    public $cc_message;

    public $cc_accept_text;

    public $cc_decline_text;

    public $cc_link_text;

    public $cc_link_url;

    /** Text fields: property => [setting key, default]. */
    public const SETTINGS = [
        'cc_heading'      => ['cc_heading', 'We value your privacy'],
        'cc_message'      => ['cc_message', 'We use cookies to run this site, analyse traffic, and measure our advertising. You can accept all cookies or decline non-essential ones. See our policy for details.'],
        'cc_accept_text'  => ['cc_accept_text', 'Accept all'],
        'cc_decline_text' => ['cc_decline_text', 'Decline'],
        'cc_link_text'    => ['cc_link_text', 'Privacy Policy'],
        'cc_link_url'     => ['cc_link_url', ''],
    ];

    public function mount(): void
    {
        $this->cc_enabled = AppSetting::get('cc_enabled', '1') === '1';

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            $this->{$prop} = AppSetting::get($key, $default);
        }
    }

    public function save(): void
    {
        $this->validate([
            'cc_heading'      => 'nullable|string|max:120',
            'cc_message'      => 'nullable|string|max:600',
            'cc_accept_text'  => 'nullable|string|max:40',
            'cc_decline_text' => 'nullable|string|max:40',
            'cc_link_text'    => 'nullable|string|max:60',
            'cc_link_url'     => 'nullable|string|max:500',
        ]);

        AppSetting::set('cc_enabled', $this->cc_enabled ? '1' : '0');

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            AppSetting::set($key, $this->{$prop});
        }

        $this->dispatch('notify', message: 'Cookie banner updated.', type: 'success');
    }

    public function resetSection(): void
    {
        $this->cc_enabled = true;

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            $this->{$prop} = $default;
        }

        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.cookie-consent-settings', [
            'privacyUrl' => route('privacy'),
        ]);
    }
}
