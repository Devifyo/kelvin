<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\TermsPageContent;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class TermsPageSettings extends Component
{
    public $contentId;
    public $tab = 'header';

    public $header_kicker;
    public $header_h1_regular;
    public $header_h1_em;
    public $last_updated;

    public $content;

    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function mount()
    {
        $record = TermsPageContent::first();
        if ($record) {
            $this->contentId         = $record->id;
            $this->header_kicker     = $record->header_kicker;
            $this->header_h1_regular = $record->header_h1_regular;
            $this->header_h1_em      = $record->header_h1_em;
            $this->last_updated      = $record->last_updated;
            $this->content           = $record->content;
            $this->seo_title         = $record->seo_title;
            $this->seo_description   = $record->seo_description;
            $this->seo_keywords      = $record->seo_keywords;
        }
    }

    public function save()
    {
        $this->validate([
            'header_kicker'     => 'nullable|string|max:255',
            'header_h1_regular' => 'nullable|string|max:255',
            'header_h1_em'      => 'nullable|string|max:255',
            'last_updated'      => 'nullable|string|max:255',
            'content'           => 'nullable|string',
            'seo_title'         => 'nullable|string|max:255',
            'seo_description'   => 'nullable|string',
            'seo_keywords'      => 'nullable|string|max:255',
        ]);

        $data = [
            'header_kicker'     => $this->header_kicker,
            'header_h1_regular' => $this->header_h1_regular,
            'header_h1_em'      => $this->header_h1_em,
            'last_updated'      => $this->last_updated,
            'content'           => $this->content,
            'seo_title'         => $this->seo_title,
            'seo_description'   => $this->seo_description,
            'seo_keywords'      => $this->seo_keywords,
        ];

        if ($this->contentId) {
            TermsPageContent::where('id', $this->contentId)->update($data);
        } else {
            $record = TermsPageContent::create($data);
            $this->contentId = $record->id;
        }

        session()->flash('success', 'Terms & Conditions page updated successfully.');
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
    }

    public function render()
    {
        return view('livewire.admin.terms-page-settings')->title('Terms & Conditions Page');
    }
}
