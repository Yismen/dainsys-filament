<?php

namespace App\Livewire;

use App\Models\Article;
use App\Services\ArticleAccessService;
use App\Services\CategoryService;
use Illuminate\Contracts\Pagination\Paginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.landing-page')]
class BlogIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    public int $perPage = 15;

    public function mount(): void
    {
        $this->authorize('view', Article::class);
    }

    #[Computed]
    public function articles(): Paginator
    {
        $user = auth()->user();

        $query = ArticleAccessService::getAccessibleArticles($user)
            ->published()
            ->with('author', 'categories');

        if (filled($this->search)) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if (filled($this->category)) {
            $query->whereHas('categories', function ($q): void {
                $q->where('slug', $this->category);
            });
        }

        return $query->paginate($this->perPage);
    }

    public function getCategories()
    {
        $user = auth()->user();

        return CategoryService::accessibleFor($user);
    }

    public function filterByCategory(string $slug): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->resetPage();
    }

    /**
     * Clear only the current search term (used by inline button).
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.blog-index');
    }
}
