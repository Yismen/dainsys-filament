<?php

namespace App\Filament\App\Resources\TerminationReasonResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\TerminationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminationReasons extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = TerminationReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
