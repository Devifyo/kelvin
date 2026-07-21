<?php

namespace App\Livewire\Admin;

use App\Models\PageHeader;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Admin → Page Headers.
 *
 * One screen that manages the hero copy of every page listed in
 * PageHeader::PAGES. Adding a page to that registry is all it takes to get a
 * fully working editor + live preview here.
 */
#[Layout('layouts.admin')]
class PageHeaders extends Component
{
    #[Url(as: 'page', keep: true)]
    public string $pageKey = '';

    // ── Editable fields ──────────────────────────────────────────────────
    public ?string $kicker = null;
    public ?string $title_regular = null;
    public ?string $title_em = null;
    public ?string $subtitle = null;

    /** Snapshot of the values as loaded, used to detect unsaved changes. */
    public array $original = [];

    public function mount(): void
    {
        $keys = array_keys(PageHeader::PAGES);

        if (! in_array($this->pageKey, $keys, true)) {
            $this->pageKey = $keys[0];
        }

        $this->loadPage($this->pageKey);
    }

    /**
     * Switch pages. If there are unsaved edits the switch is refused and a
     * `confirm-page-switch` event is dispatched instead — the view answers it
     * with a branded dialog and calls back with $force = true.
     *
     * The guard lives here rather than in JS so the dirty check has a single
     * source of truth and can never go stale against the form state.
     */
    public function selectPage(string $pageKey, bool $force = false): void
    {
        if (! array_key_exists($pageKey, PageHeader::PAGES) || $pageKey === $this->pageKey) {
            return;
        }

        if (! $force && $this->isDirty()) {
            $this->dispatch(
                'confirm-page-switch',
                pageKey: $pageKey,
                label: PageHeader::meta($pageKey)['label'] ?? $pageKey,
            );

            return;
        }

        $this->resetValidation();
        $this->pageKey = $pageKey;
        $this->loadPage($pageKey);
    }

    /** Pull the stored copy (or the registry defaults) into the form. */
    private function loadPage(string $pageKey): void
    {
        $header = PageHeader::where('page_key', $pageKey)->first()
            ?? PageHeader::fromDefaults($pageKey);

        $this->fillForm([
            'kicker'        => $header->kicker,
            'title_regular' => $header->title_regular,
            'title_em'      => $header->title_em,
            'subtitle'      => $header->subtitle,
        ]);

        $this->original = $this->formValues();
    }

    /** Restore the original hard-coded copy into the form (not yet saved). */
    public function restoreDefaults(): void
    {
        $this->resetValidation();
        $this->fillForm(PageHeader::PAGES[$this->pageKey]['defaults'] ?? []);
    }

    /** Discard edits and reload the saved copy. */
    public function discardChanges(): void
    {
        $this->loadPage($this->pageKey);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'kicker'        => 'nullable|string|max:120',
            'title_regular' => 'required|string|max:120',
            'title_em'      => 'nullable|string|max:120',
            'subtitle'      => 'nullable|string|max:500',
        ], [
            'title_regular.required' => 'The main heading is required — every page needs an H1.',
        ]);

        PageHeader::updateOrCreate(['page_key' => $this->pageKey], $validated);

        $this->original = $this->formValues();

        session()->flash('success', $this->currentMeta()['label'] . ' header updated successfully.');
    }

    // ── Helpers used by the view ─────────────────────────────────────────

    public function isDirty(): bool
    {
        return $this->formValues() !== $this->original;
    }

    public function currentMeta(): array
    {
        return PageHeader::meta($this->pageKey);
    }

    public function currentUrl(): string
    {
        $route = $this->currentMeta()['route'] ?? null;

        return $route ? route($route) : url('/');
    }

    private function formValues(): array
    {
        return [
            'kicker'        => $this->kicker,
            'title_regular' => $this->title_regular,
            'title_em'      => $this->title_em,
            'subtitle'      => $this->subtitle,
        ];
    }

    private function fillForm(array $values): void
    {
        $this->kicker        = $values['kicker'] ?? null;
        $this->title_regular = $values['title_regular'] ?? null;
        $this->title_em      = $values['title_em'] ?? null;
        $this->subtitle      = $values['subtitle'] ?? null;
    }

    public function render()
    {
        return view('livewire.admin.page-headers', [
            'pages' => PageHeader::PAGES,
        ])->title('Page Headers');
    }
}
