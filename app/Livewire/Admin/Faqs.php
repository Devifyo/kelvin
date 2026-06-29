<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use App\Models\FaqSection;
use Livewire\Component;

class Faqs extends Component
{
    // ── Item (Q&A) modal ──
    public $showModal = false;

    public $faqId = null;

    public $faq_section_id = null;

    public $question;

    public $answer;

    public $item_is_active = true;

    // ── Section heading modal ──
    public $showSectionModal = false;

    public $sectionId = null;

    public $s_kicker;

    public $s_title;

    public $s_title_em;

    protected function rules(): array
    {
        return [
            'question' => 'required|string|max:300',
            'answer' => 'required|string|max:2000',
            'faq_section_id' => 'required|exists:faq_sections,id',
            'item_is_active' => 'boolean',
        ];
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

    // ── Section ──
    public function toggleSection($id): void
    {
        $s = FaqSection::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
        FaqSection::clearCache();
    }

    public function editSection($id): void
    {
        $s = FaqSection::findOrFail($id);
        $this->sectionId = $s->id;
        $this->s_kicker = $s->kicker;
        $this->s_title = $s->title;
        $this->s_title_em = $s->title_em;
        $this->showSectionModal = true;
    }

    public function saveSection(): void
    {
        $this->validate([
            's_kicker' => 'nullable|string|max:255',
            's_title' => 'nullable|string|max:255',
            's_title_em' => 'nullable|string|max:255',
        ]);

        FaqSection::where('id', $this->sectionId)->update([
            'kicker' => $this->s_kicker,
            'title' => $this->s_title,
            'title_em' => $this->s_title_em,
        ]);

        FaqSection::clearCache();
        $this->dispatch('notify', message: 'Section heading updated.', type: 'success');
        $this->showSectionModal = false;
    }

    public function render()
    {
        $sections = FaqSection::with(['faqs' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('page')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.faqs', compact('sections'))
            ->layout('layouts.admin', ['title' => 'FAQ Manager']);
    }
}
