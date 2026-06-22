<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Client;
use App\Models\Paper;
use App\Models\PodcastWebinar;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class FrontendContentService
{
    public function getConsultingServices(): Collection
    {
        return Service::active()->consulting()->ordered()->get();
    }

    public function getTrainingClasses(): Collection
    {
        return Service::active()->training()->ordered()->get();
    }

    public function getTrainingClassBySlug(string $slug): Model
    {
        return Service::active()->training()->where('slug', $slug)->firstOrFail();
    }

    public function getPapersData(string $filter = 'all'): array
    {
        $categories = Category::where('type', 'paper')
            ->whereHas('papers', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $papers = Paper::with('category')
            ->where('is_active', true)
            ->when($filter !== 'all', fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $filter))
            )
            ->orderBy('sort_order')
            ->get();

        return compact('categories', 'papers');
    }

    public function getBlogPosts(?string $search, int $perPage = 9): LengthAwarePaginator
    {
        return Post::with('category')
            ->published()
            ->when($search, fn ($query, $searchTerm) => $query->where(fn ($q) => $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                ->orWhereHas('category', fn ($qc) => $qc->where('name', 'like', "%{$searchTerm}%"))
            )
            )
            ->orderByRaw('(sort_order IS NULL) ASC, sort_order ASC, published_at DESC')
            ->paginate($perPage);
    }

    public function getPostBySlug(string $slug): Model
    {
        return Post::with(['category', 'author'])->where('slug', $slug)->firstOrFail();
    }

    public function getPodcastsWebinars(?string $filter = 'all')
    {
        return PodcastWebinar::where('is_active', true)
            ->when($filter !== 'all', fn ($query) => $query->where('type', $filter))
            ->orderBy('published_date', 'desc')
            ->get();
    }

    // --- Clients / "Trusted By" ---

    /**
     * Active + featured clients for the homepage "Trusted By" strip.
     * Cached for an hour; invalidated whenever a client is saved (see Client::clearCache()).
     */
    public function getFeaturedClients(): Collection
    {
        return Cache::remember('clients.featured', 3600, fn () => Client::active()->featured()->ordered()->get()
        );
    }

    /**
     * Total number of active clients — drives the dynamic "70+ Clients Served" count.
     */
    public function getActiveClientsCount(): int
    {
        return Cache::remember('clients.active_count', 3600, fn () => Client::active()->count()
        );
    }

    /**
     * Full ordered list of active clients for the public /clients page.
     *
     * The whole set is returned (and cached) so the page can filter instantly
     * client-side. For the showcase's scale (tens–hundreds of logos) this is far
     * faster than round-tripping the server on every keystroke.
     */
    public function getActiveClients(): Collection
    {
        return Cache::remember('clients.all_active', 3600, fn () => Client::active()->ordered()->get());
    }
}
