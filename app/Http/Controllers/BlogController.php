<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleAccessService;
use App\Services\CategoryService;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /**
     * Show list of articles accessible to the user
     */
    public function index()
    {
        $this->authorize('view', Article::class);

        $query = ArticleAccessService::getAccessibleArticles(auth()->user())
            ->published()
            ->with('author', 'categories');

        // apply text search when provided
        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // filter by category slug if requested
        if ($slug = request('category')) {
            $query->whereHas('categories', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $articles = $query->paginate(15)->withQueryString();

        // categories the current user can view (for sidebar links)
        $categories = CategoryService::accessibleFor(auth()->user());

        return view('blog.index', compact('articles', 'categories'));
    }

    /**
     * Show a single article
     */
    public function show(Article $article)
    {
        if ($article->status->value === 'draft') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        if (! ArticleAccessService::canUserAccessArticle($user, $article)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $article->load('author', 'categories');

        return view('blog.show', compact('article'));
    }
}
