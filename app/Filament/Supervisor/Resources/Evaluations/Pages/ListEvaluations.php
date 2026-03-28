<?php

namespace App\Filament\Supervisor\Resources\Evaluations\Pages;

use App\Filament\Supervisor\Resources\Evaluations\EvaluationResource;
use Filament\Resources\Pages\ListRecords;

class ListEvaluations extends ListRecords
{
    protected static string $resource = EvaluationResource::class;
}
