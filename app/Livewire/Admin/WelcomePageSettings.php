<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WelcomePageContent;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class WelcomePageSettings extends Component
{
    public $contentId;
    public $tab = 'hero';
    
    // Hero Section
    public $hero_kicker;
    public $hero_h1_em;
    public $hero_h1_strong;
    public $hero_p1;
    public $hero_p2;
    public $hero_cta_primary_text;
    public $hero_cta_primary_link;
    public $hero_cta_secondary_text;
    public $hero_cta_secondary_link;

    // Pain List Section
    public $pain_title;
    public $pain_list = [];
    public $pain_list_string = ''; // for textarea editing
    public $pain_footer;

    // Principal Section
    public $principal_kicker;
    public $principal_h2_name;
    public $principal_h2_em;
    public $principal_p1;
    public $principal_p2;
    public $principal_p3;

    // Book info
    public $principal_book_image;
    public $principal_book_title;
    public $principal_book_desc;
    public $principal_book_url;

    // SEO Settings
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function mount()
    {
        $content = WelcomePageContent::first();
        if ($content) {
            $this->contentId = $content->id;
            $this->hero_kicker = $content->hero_kicker;
            $this->hero_h1_em = $content->hero_h1_em;
            $this->hero_h1_strong = $content->hero_h1_strong;
            $this->hero_p1 = $content->hero_p1;
            $this->hero_p2 = $content->hero_p2;
            $this->hero_cta_primary_text = $content->hero_cta_primary_text;
            $this->hero_cta_primary_link = $content->hero_cta_primary_link;
            $this->hero_cta_secondary_text = $content->hero_cta_secondary_text;
            $this->hero_cta_secondary_link = $content->hero_cta_secondary_link;

            $this->pain_title = $content->pain_title;
            $this->pain_list = $content->pain_list ?: [];
            $this->pain_list_string = implode("\n", $this->pain_list);
            $this->pain_footer = $content->pain_footer;

            $this->principal_kicker = $content->principal_kicker;
            $this->principal_h2_name = $content->principal_h2_name;
            $this->principal_h2_em = $content->principal_h2_em;
            $this->principal_p1 = $content->principal_p1;
            $this->principal_p2 = $content->principal_p2;
            $this->principal_p3 = $content->principal_p3;

            $this->principal_book_image = $content->principal_book_image;
            $this->principal_book_title = $content->principal_book_title;
            $this->principal_book_desc = $content->principal_book_desc;
            $this->principal_book_url = $content->principal_book_url;

            $this->seo_title = $content->seo_title;
            $this->seo_description = $content->seo_description;
            $this->seo_keywords = $content->seo_keywords;
        }
    }

    public function save()
    {
            $this->validate([
            'hero_kicker' => 'nullable|string|max:255',
            'hero_h1_em' => 'nullable|string|max:255',
            'hero_h1_strong' => 'nullable|string|max:255',
            'hero_p1' => 'nullable|string',
            'hero_p2' => 'nullable|string',
            'hero_cta_primary_text' => 'nullable|string|max:255',
            'hero_cta_primary_link' => 'nullable|string|max:255',
            'hero_cta_secondary_text' => 'nullable|string|max:255',
            'hero_cta_secondary_link' => 'nullable|string|max:255',
            'pain_title' => 'nullable|string|max:255',
            'pain_footer' => 'nullable|string|max:255',
            'principal_kicker' => 'nullable|string|max:255',
            'principal_h2_name' => 'nullable|string|max:255',
            'principal_h2_em' => 'nullable|string|max:255',
            'principal_p1' => 'nullable|string',
            'principal_p2' => 'nullable|string',
            'principal_p3' => 'nullable|string',
            'principal_book_image' => 'nullable|string|max:1024',
            'principal_book_title' => 'nullable|string|max:255',
            'principal_book_desc' => 'nullable|string',
            'principal_book_url' => 'nullable|string|max:1024',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $painListArray = array_filter(array_map('trim', explode("\n", $this->pain_list_string)));

        $data = [
            'hero_kicker' => $this->hero_kicker,
            'hero_h1_em' => $this->hero_h1_em,
            'hero_h1_strong' => $this->hero_h1_strong,
            'hero_p1' => $this->hero_p1,
            'hero_p2' => $this->hero_p2,
            'hero_cta_primary_text' => $this->hero_cta_primary_text,
            'hero_cta_primary_link' => $this->hero_cta_primary_link,
            'hero_cta_secondary_text' => $this->hero_cta_secondary_text,
            'hero_cta_secondary_link' => $this->hero_cta_secondary_link,
            'pain_title' => $this->pain_title,
            'pain_list' => array_values($painListArray),
            'pain_footer' => $this->pain_footer,
            'principal_kicker' => $this->principal_kicker,
            'principal_h2_name' => $this->principal_h2_name,
            'principal_h2_em' => $this->principal_h2_em,
            'principal_p1' => $this->principal_p1,
            'principal_p2' => $this->principal_p2,
            'principal_p3' => $this->principal_p3,
            'principal_book_image' => $this->principal_book_image,
            'principal_book_title' => $this->principal_book_title,
            'principal_book_desc' => $this->principal_book_desc,
            'principal_book_url' => $this->principal_book_url,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ];

        if ($this->contentId) {
            WelcomePageContent::where('id', $this->contentId)->update($data);
        } else {
            $content = WelcomePageContent::create($data);
            $this->contentId = $content->id;
        }

        session()->flash('success', 'Welcome page settings updated successfully.');
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
    }

    public function render()
    {
        return view('livewire.admin.welcome-page-settings')->title('Welcome Page Settings');
    }
}
