<?php

namespace App\Filament\Blog\Resources\CategoryResource\Pages;

use App\Filament\Blog\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
