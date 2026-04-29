<?php

namespace App\Filament\Recruitment\Resources\Applicants\Pages;

use App\Filament\Recruitment\Resources\Applicants\ApplicantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicants extends ListRecords
{
    protected static string $resource = ApplicantResource::class;

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
