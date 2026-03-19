<?php

namespace App\Services;

use App\Models\{Service, Post, Paper, Category};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
            ->whereHas('papers', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $papers = Paper::with('category')
            ->where('is_active', true)
            ->when($filter !== 'all', fn($query) => 
                $query->whereHas('category', fn($q) => $q->where('slug', $filter))
            )
            ->orderBy('sort_order')
            ->get();

        return compact('categories', 'papers');
    }

    public function getBlogPosts(?string $search, int $perPage = 9): LengthAwarePaginator
    {
        return Post::with('category')
            ->published()
            ->when($search, fn($query, $searchTerm) => 
                $query->where(fn($q) => 
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                      ->orWhereHas('category', fn($qc) => $qc->where('name', 'like', "%{$searchTerm}%"))
                )
            )
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getPostBySlug(string $slug): Model
    {
        return Post::with(['category', 'author'])->where('slug', $slug)->firstOrFail();
    }
}