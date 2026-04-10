<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AboutPageContent;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
class AboutPageSettings extends Component
{
    use WithFileUploads;

    public $contentId;
    public $tab = 'header';
    
    // Header
    public $header_kicker;
    public $header_h1_regular;
    public $header_h1_em;
    
    // Sidebar
    public $profile_image;
    public $new_profile_image;
    public $sidebar_kicker;
    public $education_list = [];
    
    // Content
    public $intro_text;
    public $section_1_h2_regular;
    public $section_1_h2_em;
    public $section_1_p1;
    public $section_1_p2;
    public $highlight_quote;
    public $section_1_p3;
    
    public $section_2_h2_regular;
    public $section_2_h2_em;
    public $section_2_p1;
    public $section_2_p2;
    public $section_2_p3;

    // SEO Settings
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function mount()
    {
        $content = AboutPageContent::first();
        if ($content) {
            $this->contentId = $content->id;
            
            $this->header_kicker = $content->header_kicker;
            $this->header_h1_regular = $content->header_h1_regular;
            $this->header_h1_em = $content->header_h1_em;

            $this->profile_image = $content->profile_image;
            $this->sidebar_kicker = $content->sidebar_kicker;
            $this->education_list = $content->education_list ?: [];

            $this->intro_text = $content->intro_text;
            $this->section_1_h2_regular = $content->section_1_h2_regular;
            $this->section_1_h2_em = $content->section_1_h2_em;
            $this->section_1_p1 = $content->section_1_p1;
            $this->section_1_p2 = $content->section_1_p2;
            $this->highlight_quote = $content->highlight_quote;
            $this->section_1_p3 = $content->section_1_p3;

            $this->section_2_h2_regular = $content->section_2_h2_regular;
            $this->section_2_h2_em = $content->section_2_h2_em;
            $this->section_2_p1 = $content->section_2_p1;
            $this->section_2_p2 = $content->section_2_p2;
            $this->section_2_p3 = $content->section_2_p3;

            $this->seo_title = $content->seo_title;
            $this->seo_description = $content->seo_description;
            $this->seo_keywords = $content->seo_keywords;
        }
    }

    public function updatedNewProfileImage()
    {
        $this->validateOnly('new_profile_image', [
            'new_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);
    }

    public function save()
    {
        $this->validate([
            'header_kicker' => 'nullable|string|max:255',
            'header_h1_regular' => 'nullable|string|max:255',
            'header_h1_em' => 'nullable|string|max:255',
            'new_profile_image' => 'nullable|image|max:5120', // allow up to 5MB image
            'profile_image' => 'nullable|string|max:1024',
            'sidebar_kicker' => 'nullable|string|max:255',
            'intro_text' => 'nullable|string',
            'section_1_h2_regular' => 'nullable|string|max:255',
            'section_1_h2_em' => 'nullable|string|max:255',
            'section_1_p1' => 'nullable|string',
            'section_1_p2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'section_1_p3' => 'nullable|string',
            'section_2_h2_regular' => 'nullable|string|max:255',
            'section_2_h2_em' => 'nullable|string|max:255',
            'section_2_p1' => 'nullable|string',
            'section_2_p2' => 'nullable|string',
            'section_2_p3' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'education_list' => 'nullable|array',
        ]);

        if ($this->new_profile_image) {
            $path = $this->new_profile_image->store('about/profile-images', 'public');
            $this->profile_image = Storage::url($path);
        }

        $data = [
            'header_kicker' => $this->header_kicker,
            'header_h1_regular' => $this->header_h1_regular,
            'header_h1_em' => $this->header_h1_em,
            'profile_image' => $this->profile_image,
            'sidebar_kicker' => $this->sidebar_kicker,
            'education_list' => $this->education_list,
            'intro_text' => $this->intro_text,
            'section_1_h2_regular' => $this->section_1_h2_regular,
            'section_1_h2_em' => $this->section_1_h2_em,
            'section_1_p1' => $this->section_1_p1,
            'section_1_p2' => $this->section_1_p2,
            'highlight_quote' => $this->highlight_quote,
            'section_1_p3' => $this->section_1_p3,
            'section_2_h2_regular' => $this->section_2_h2_regular,
            'section_2_h2_em' => $this->section_2_h2_em,
            'section_2_p1' => $this->section_2_p1,
            'section_2_p2' => $this->section_2_p2,
            'section_2_p3' => $this->section_2_p3,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ];

        if ($this->contentId) {
            AboutPageContent::where('id', $this->contentId)->update($data);
        } else {
            $content = AboutPageContent::create($data);
            $this->contentId = $content->id;
        }

        session()->flash('success', 'About page settings updated successfully.');
    }

    public function addEducationItem()
    {
        $this->education_list[] = ['title' => '', 'details' => ''];
    }

    public function removeEducationItem($index)
    {
        unset($this->education_list[$index]);
        $this->education_list = array_values($this->education_list);
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
    }

    public function render()
    {
        return view('livewire.admin.about-page-settings')->title('About Page Settings');
    }
}
