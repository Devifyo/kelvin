<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin', ['title' => 'App Settings'])]
class AppSettings extends Component
{
    use WithFileUploads;

    #[Url]
    public string $tab = 'general';

    // ── General ───────────────────────────────────────────────────────────
    public string $appName = '';

    // ── Colors ────────────────────────────────────────────────────────────
    public string $colorCopper = '';
    public string $colorSlate  = '';

    // ── Icons ─────────────────────────────────────────────────────────────
    #[Validate('nullable|image|mimes:png,jpg,jpeg,webp|max:2048')]
    public $newAppIcon = null;

    #[Validate('nullable|image|mimes:png,jpg,jpeg|max:1024')]
    public $newFavicon = null;

    public ?string $currentIconUrl    = null;
    public ?string $currentFaviconUrl = null;

    // ── Lifecycle ─────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->appName     = AppSetting::get('app_name',     AppSetting::DEFAULTS['app_name']);
        $this->colorCopper = AppSetting::get('color_copper', AppSetting::DEFAULTS['color_copper']);
        $this->colorSlate  = AppSetting::get('color_slate',  AppSetting::DEFAULTS['color_slate']);

        $this->currentIconUrl    = $this->resolveStorageUrl(AppSetting::get('app_icon'));
        $this->currentFaviconUrl = $this->resolveStorageUrl(AppSetting::get('favicon'));
    }

    // ── Actions ───────────────────────────────────────────────────────────

    public function saveGeneral(): void
    {
        $this->validate([
            'appName' => 'required|string|max:80',
        ]);

        AppSetting::set('app_name', trim($this->appName));
        $this->dispatch('notify', message: 'App name updated.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function saveColors(): void
    {
        $this->validate([
            'colorCopper' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'colorSlate'  => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        AppSetting::set('color_copper', $this->colorCopper);
        AppSetting::set('color_slate',  $this->colorSlate);

        $this->dispatch('notify', message: 'Colors saved.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function resetColors(): void
    {
        $this->colorCopper = AppSetting::DEFAULTS['color_copper'];
        $this->colorSlate  = AppSetting::DEFAULTS['color_slate'];

        AppSetting::set('color_copper', $this->colorCopper);
        AppSetting::set('color_slate',  $this->colorSlate);

        $this->dispatch('notify', message: 'Colors reset to defaults.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function saveIcons(): void
    {
        $this->validateOnly('newAppIcon');
        $this->validateOnly('newFavicon');

        if ($this->newAppIcon) {
            $old = AppSetting::get('app_icon');
            if ($old) Storage::disk('public')->delete($old);

            $path = $this->newAppIcon->storeAs('app-settings', 'icon.'.$this->newAppIcon->getClientOriginalExtension(), 'public');
            AppSetting::set('app_icon', $path);
            $this->currentIconUrl = asset('storage/' . $path);
            $this->newAppIcon = null;
        }

        if ($this->newFavicon) {
            $old = AppSetting::get('favicon');
            if ($old) Storage::disk('public')->delete($old);

            $path = $this->newFavicon->storeAs('app-settings', 'favicon.'.$this->newFavicon->getClientOriginalExtension(), 'public');
            AppSetting::set('favicon', $path);
            $this->currentFaviconUrl = asset('storage/' . $path);
            $this->newFavicon = null;
        }

        $this->dispatch('notify', message: 'Icons updated successfully.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function removeAppIcon(): void
    {
        $path = AppSetting::get('app_icon');
        if ($path) Storage::disk('public')->delete($path);
        AppSetting::set('app_icon', null);
        $this->currentIconUrl = null;
        $this->dispatch('notify', message: 'App icon removed.', type: 'success');
    }

    public function removeFavicon(): void
    {
        $path = AppSetting::get('favicon');
        if ($path) Storage::disk('public')->delete($path);
        AppSetting::set('favicon', null);
        $this->currentFaviconUrl = null;
        $this->dispatch('notify', message: 'Favicon removed.', type: 'success');
    }

    public function resetIcons(): void
    {
        foreach (['app_icon', 'favicon'] as $key) {
            $path = AppSetting::get($key);
            if ($path) Storage::disk('public')->delete($path);
            AppSetting::set($key, null);
        }

        $this->currentIconUrl    = null;
        $this->currentFaviconUrl = null;
        $this->newAppIcon        = null;
        $this->newFavicon        = null;

        $this->dispatch('notify', message: 'Icons reset to defaults.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function resolveStorageUrl(?string $path): ?string
    {
        if (! $path) return null;
        return Storage::disk('public')->exists($path)
            ? asset('storage/' . $path)
            : null;
    }

    // ── Render ────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.app-settings', [
            'previewColors' => AppSetting::resolvedColors(),
        ]);
    }
}
