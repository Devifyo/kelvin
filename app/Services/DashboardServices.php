<?php

namespace App\Services;
use App\Models\{Service, Post, Paper, Category, PodcastWebinar, ContactMessage};

class DashboardServices
{
    public function getDashboardData()
    {   
        // Fetching the counts directly from the database
        $trainingCount = Service::active()->training()->count() ?? 0;
        $consultingCount = Service::active()->consulting()->count() ?? 0;
        $papersCount = Paper::active()->count() ?? 0;
        $podcastWebinarsCount = PodcastWebinar::active()->count() ?? 0;
        $contactCount = ContactMessage::count() ?? 0;
        // Return or use the variables as needed
        return [
            'training' => $trainingCount,
            'consulting' => $consultingCount,
            'papers' => $papersCount,
            'podcast_webinars' => $podcastWebinarsCount,
            'contacts' => $contactCount,
        ];
    }

    public function getContactMessagesData($limit = 10)
    {
        // Fetch the latest contact inquiries
        return ContactMessage::latest()->take($limit)->get();
    }
}