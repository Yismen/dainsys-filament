<?php

namespace App\Livewire;

use App\Models\Article;
use App\Services\ArticleAccessService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.landing-page')]
class ArticleShow extends Component
{
    public Article $article;

    public function mount(Article $article): void
    {
        if ($article->status->value === 'draft') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        if (! ArticleAccessService::canUserAccessArticle($user, $article)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->article = $article;
    }

    public function render(): View
    {
        return view('livewire.article-show', [
            'article' => $this->article,
        ]);
    }
}
