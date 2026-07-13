<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Support\Str;
use Livewire\Component;

class Faqs extends Component
{
    /** Which public page is currently being managed. null = show the page list. */
    public ?string $selectedPage = null;

    // ── Item (Q&A) modal ──
    public $showModal = false;

    public $faqId = null;

    public $faq_section_id = null;

    public $question;

    public $answer;

    public $item_is_active = true;

    // ── Section modal ──
    public $showSectionModal = false;

    public $sectionId = null;

    public $s_name;

    public $s_kicker;

    public $s_title;

    public $s_title_em;

    protected function rules(): array
    {
        return [
            'question' => 'required|string|max:300',
            'answer' => 'required|string|max:60000',
            'faq_section_id' => 'required|exists:faq_sections,id',
            'item_is_active' => 'boolean',
        ];
    }

    // ── Page navigation ──
    public function selectPage(string $page): void
    {
        $this->selectedPage = $page;
    }

    public function backToPages(): void
    {
        $this->selectedPage = null;
    }

    // ── Q&A items ──
    public function createItem($sectionId): void
    {
        $this->reset(['faqId', 'question', 'answer']);
        $this->faq_section_id = $sectionId;
        $this->item_is_active = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function editItem($id): void
    {
        $faq = Faq::findOrFail($id);
        $this->faqId = $faq->id;
        $this->faq_section_id = $faq->faq_section_id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->item_is_active = $faq->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function saveItem(): void
    {
        $this->validate();

        if (! $this->faqId) {
            $nextOrder = (Faq::where('faq_section_id', $this->faq_section_id)->max('sort_order') ?? 0) + 1;
        }

        Faq::updateOrCreate(['id' => $this->faqId], [
            'faq_section_id' => $this->faq_section_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'is_active' => $this->item_is_active,
            'sort_order' => $this->faqId ? Faq::find($this->faqId)->sort_order : $nextOrder,
        ]);

        FaqSection::clearCache();
        $this->dispatch('notify', message: 'FAQ saved.', type: 'success');
        $this->showModal = false;
    }

    public function deleteItem($id): void
    {
        Faq::destroy($id);
        FaqSection::clearCache();
    }

    public function toggleItem($id): void
    {
        $f = Faq::findOrFail($id);
        $f->update(['is_active' => ! $f->is_active]);
        FaqSection::clearCache();
    }

    public function updateItemOrder($orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Faq::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        FaqSection::clearCache();
        $this->dispatch('notify', message: 'Order updated.', type: 'success');
    }

    // ── Sections ──
    public function toggleSection($id): void
    {
        $s = FaqSection::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
        FaqSection::clearCache();
    }

    public function createSection(): void
    {
        $this->reset(['sectionId', 's_name', 's_kicker', 's_title', 's_title_em']);
        $this->resetValidation();
        $this->showSectionModal = true;
    }

    public function editSection($id): void
    {
        $s = FaqSection::findOrFail($id);
        $this->sectionId = $s->id;
        $this->s_name = $s->name;
        $this->s_kicker = $s->kicker;
        $this->s_title = $s->title;
        $this->s_title_em = $s->title_em;
        $this->resetValidation();
        $this->showSectionModal = true;
    }

    public function saveSection(): void
    {
        $this->validate([
            's_name' => 'required|string|max:255',
            's_kicker' => 'nullable|string|max:255',
            's_title' => 'nullable|string|max:255',
            's_title_em' => 'nullable|string|max:255',
        ]);

        if ($this->sectionId) {
            FaqSection::where('id', $this->sectionId)->update([
                'name' => $this->s_name,
                'kicker' => $this->s_kicker,
                'title' => $this->s_title,
                'title_em' => $this->s_title_em,
            ]);
            $message = 'Section updated.';
        } else {
            // New section — appends to the end of the current page (after existing sections).
            $page = $this->selectedPage ?? 'faq';
            $order = (FaqSection::where('page', $page)->max('sort_order') ?? 0) + 1;

            FaqSection::create([
                'key' => $this->uniqueKey($page.'-'.$this->s_name),
                'page' => $page,
                'name' => $this->s_name,
                'kicker' => $this->s_kicker,
                'title' => $this->s_title,
                'title_em' => $this->s_title_em,
                'is_active' => true,
                'sort_order' => $order,
            ]);
            $message = 'Section added.';
        }

        FaqSection::clearCache();
        $this->dispatch('notify', message: $message, type: 'success');
        $this->showSectionModal = false;
    }

    public function deleteSection($id): void
    {
        $s = FaqSection::find($id);
        if ($s) {
            $s->faqs()->delete();
            $s->delete();
        }
        FaqSection::clearCache();
        $this->dispatch('notify', message: 'Section deleted.', type: 'success');
    }

    /** Build a unique faq_sections.key from a base string. */
    protected function uniqueKey(string $base): string
    {
        $slug = Str::slug($base) ?: 'section';
        $key = $slug;
        $i = 2;
        while (FaqSection::where('key', $key)->exists()) {
            $key = $slug.'-'.$i;
            $i++;
        }

        return $key;
    }

    public function render()
    {
        // Detail view: manage one page's sections + questions.
        if ($this->selectedPage) {
            $sections = FaqSection::with(['faqs' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
                ->where('page', $this->selectedPage)
                ->orderBy('sort_order')
                ->get();

            return view('livewire.admin.faqs', ['sections' => $sections, 'pages' => null])
                ->layout('layouts.admin', ['title' => 'FAQ Manager']);
        }

        // Listing view: the pages that have FAQ sections, grouped.
        // 'training' is excluded — the /agile-training-classes landing page was
        // retired (redirects to /agile-consulting-services), so its FAQs no longer render.
        $pages = FaqSection::withCount('faqs')
            ->where('page', '!=', 'training')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('page');

        return view('livewire.admin.faqs', ['sections' => null, 'pages' => $pages])
            ->layout('layouts.admin', ['title' => 'FAQ Manager']);
    }
}
