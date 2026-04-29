<?php

namespace App\Filament\Recruitment\Resources\ApplicationStageEvents\Pages;

use App\Filament\Recruitment\Resources\ApplicationStageEvents\ApplicationStageEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicationStageEvents extends ListRecords
{
    protected static string $resource = ApplicationStageEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('filament.create'))
                ->stickyModalHeader()
                ->stickyModalFooter()
                ->closeModalByClickingAway(false),
        ];
    }
}
