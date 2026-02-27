<?php

namespace App\Filament\Blog\Resources\ArticleResource\Pages;

use App\Filament\Blog\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
}
