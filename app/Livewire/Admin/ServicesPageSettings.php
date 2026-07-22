<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Editable section copy for the public /agile-consulting-services page.
 *
 * The page's hero H1 is managed separately in Page Headers (x-page-header).
 * This module owns the two mid-page section headers below the hero:
 *   • "Consulting Services"      (above the consulting list)
 *   • "Training Classes"         (above the training list)
 *
 * Values are stored in the cached AppSetting key/value store, so no dedicated
 * table is needed. The public view reads the same keys with these defaults.
 */
#[Layout('layouts.admin', ['title' => 'Services Page'])]
class ServicesPageSettings extends Component
{
    // ── Consulting section header ─────────────────────────────────────────
    public $consulting_kicker;

    public $consulting_title;

    public $consulting_title_em;

    public $consulting_body;

    // ── Training Classes section header ───────────────────────────────────
    public $training_kicker;

    public $training_title;

    public $training_title_em;

    public $training_body;

    /** Original hard-coded copy — used as defaults and by "Reset". */
    public const DEFAULTS = [
        'services_consulting_kicker'   => 'Strategic Guidance',
        'services_consulting_title'    => 'Consulting',
        'services_consulting_title_em' => 'Services',
        'services_consulting_body'     => 'We work directly with your organization to diagnose delivery issues, coach teams, and drive lasting improvement. Every engagement is scoped and tailored to the specific needs of the client.',

        'services_training_kicker'   => 'Education & Growth',
        'services_training_title'    => 'Training',
        'services_training_title_em' => 'Classes',
        'services_training_body'     => 'The following classes and presentations are available. Each is designed to address specific needs within your organization — from executive briefings to deep-dive team frameworks.',
    ];

    public function mount(): void
    {
        $this->consulting_kicker   = AppSetting::get('services_consulting_kicker', self::DEFAULTS['services_consulting_kicker']);
        $this->consulting_title    = AppSetting::get('services_consulting_title', self::DEFAULTS['services_consulting_title']);
        $this->consulting_title_em = AppSetting::get('services_consulting_title_em', self::DEFAULTS['services_consulting_title_em']);
        $this->consulting_body     = AppSetting::get('services_consulting_body', self::DEFAULTS['services_consulting_body']);

        $this->training_kicker   = AppSetting::get('services_training_kicker', self::DEFAULTS['services_training_kicker']);
        $this->training_title    = AppSetting::get('services_training_title', self::DEFAULTS['services_training_title']);
        $this->training_title_em = AppSetting::get('services_training_title_em', self::DEFAULTS['services_training_title_em']);
        $this->training_body     = AppSetting::get('services_training_body', self::DEFAULTS['services_training_body']);
    }

    public function save(): void
    {
        $this->validate([
            'consulting_kicker'   => 'nullable|string|max:255',
            'consulting_title'    => 'nullable|string|max:255',
            'consulting_title_em' => 'nullable|string|max:255',
            'consulting_body'     => 'nullable|string|max:2000',
            'training_kicker'     => 'nullable|string|max:255',
            'training_title'      => 'nullable|string|max:255',
            'training_title_em'   => 'nullable|string|max:255',
            'training_body'       => 'nullable|string|max:2000',
        ]);

        AppSetting::set('services_consulting_kicker', $this->consulting_kicker);
        AppSetting::set('services_consulting_title', $this->consulting_title);
        AppSetting::set('services_consulting_title_em', $this->consulting_title_em);
        AppSetting::set('services_consulting_body', $this->consulting_body);

        AppSetting::set('services_training_kicker', $this->training_kicker);
        AppSetting::set('services_training_title', $this->training_title);
        AppSetting::set('services_training_title_em', $this->training_title_em);
        AppSetting::set('services_training_body', $this->training_body);

        $this->dispatch('notify', message: 'Services page updated.', type: 'success');
    }

    /** Restore one section ('consulting' or 'training') to its original wording and save. */
    public function resetSection(string $section): void
    {
        if ($section === 'consulting') {
            $this->consulting_kicker   = self::DEFAULTS['services_consulting_kicker'];
            $this->consulting_title    = self::DEFAULTS['services_consulting_title'];
            $this->consulting_title_em = self::DEFAULTS['services_consulting_title_em'];
            $this->consulting_body     = self::DEFAULTS['services_consulting_body'];
        } elseif ($section === 'training') {
            $this->training_kicker   = self::DEFAULTS['services_training_kicker'];
            $this->training_title    = self::DEFAULTS['services_training_title'];
            $this->training_title_em = self::DEFAULTS['services_training_title_em'];
            $this->training_body     = self::DEFAULTS['services_training_body'];
        }

        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.services-page-settings');
    }
}
