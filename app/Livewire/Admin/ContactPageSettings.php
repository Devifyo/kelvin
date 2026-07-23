<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\PageHeader;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Single edit surface for the public /contact-us page.
 *
 *   • Header (hero)  — kicker + H1 + subtitle, stored in the PageHeader
 *     'contact' row and rendered by <x-page-header page="contact">. It is
 *     intentionally hidden from the shared Page Headers screen (admin_hidden)
 *     so this stays the only place it is edited.
 *   • "Get in Touch" section copy, stored in the cached AppSetting store.
 */
#[Layout('layouts.admin', ['title' => 'Contact Page'])]
class ContactPageSettings extends Component
{
    private const HERO_KEY = 'contact';

    // ── Header / hero (stored in PageHeader 'contact') ────────────────────
    public $hero_kicker;

    public $hero_title;

    public $hero_title_em;

    public $hero_subtitle;

    // ── "Get in Touch" section copy (AppSetting) ──────────────────────────
    public $contact_kicker;

    public $contact_title;

    public $contact_title_em;

    public $contact_body;

    public $contact_note;

    public $contact_submit_text;

    /** AppSetting-backed fields: property => [setting key, default]. */
    public const SETTINGS = [
        'contact_kicker'      => ['contact_kicker', 'Get in Touch'],
        'contact_title'       => ['contact_title', 'Start the'],
        'contact_title_em'    => ['contact_title_em', 'Conversation'],
        'contact_body'        => ['contact_body', "Reach out to discuss your organization's needs. All consulting and training services are provided on-site at client locations, tailored specifically to your product context."],
        'contact_note'        => ['contact_note', 'Please fill out the form with your details and a brief message regarding the challenges your team is facing. We will review your inquiry and respond promptly to schedule an initial consultation.'],
        'contact_submit_text' => ['contact_submit_text', 'Send Message'],
    ];

    public function mount(): void
    {
        $header = PageHeader::for(self::HERO_KEY);
        $this->hero_kicker   = $header->kicker;
        $this->hero_title    = $header->title_regular;
        $this->hero_title_em = $header->title_em;
        $this->hero_subtitle = $header->subtitle;

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            $this->{$prop} = AppSetting::get($key, $default);
        }
    }

    public function save(): void
    {
        $this->validate([
            // Header — H1 required so the page can never publish without one.
            'hero_kicker'   => 'nullable|string|max:120',
            'hero_title'    => 'required|string|max:120',
            'hero_title_em' => 'nullable|string|max:120',
            'hero_subtitle' => 'nullable|string|max:500',
            // Copy
            'contact_kicker'      => 'nullable|string|max:255',
            'contact_title'       => 'nullable|string|max:255',
            'contact_title_em'    => 'nullable|string|max:255',
            'contact_body'        => 'nullable|string|max:2000',
            'contact_note'        => 'nullable|string|max:2000',
            'contact_submit_text' => 'nullable|string|max:60',
        ], [
            'hero_title.required' => 'The page heading is required — every page needs an H1.',
        ]);

        PageHeader::updateOrCreate(['page_key' => self::HERO_KEY], [
            'kicker'        => $this->hero_kicker,
            'title_regular' => $this->hero_title,
            'title_em'      => $this->hero_title_em,
            'subtitle'      => $this->hero_subtitle,
        ]);

        foreach (self::SETTINGS as $prop => [$key, $default]) {
            AppSetting::set($key, $this->{$prop});
        }

        $this->dispatch('notify', message: 'Contact page updated.', type: 'success');
    }

    /** Restore one section ('header' or 'copy') to its original wording and save. */
    public function resetSection(string $section): void
    {
        if ($section === 'header') {
            $defaults = PageHeader::PAGES[self::HERO_KEY]['defaults'] ?? [];
            $this->hero_kicker   = $defaults['kicker'] ?? null;
            $this->hero_title    = $defaults['title_regular'] ?? null;
            $this->hero_title_em = $defaults['title_em'] ?? null;
            $this->hero_subtitle = $defaults['subtitle'] ?? null;
        } elseif ($section === 'copy') {
            foreach (self::SETTINGS as $prop => [$key, $default]) {
                $this->{$prop} = $default;
            }
        }

        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.contact-page-settings');
    }
}
