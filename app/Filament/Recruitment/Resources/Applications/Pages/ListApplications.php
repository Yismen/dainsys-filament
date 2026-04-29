<?php

namespace App\Filament\Recruitment\Resources\Applications\Pages;

use App\Filament\Recruitment\Resources\Applications\ApplicationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

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
