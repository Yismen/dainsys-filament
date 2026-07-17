<?php

namespace App\Filament\Supervisor\Resources\Absences\Pages;

use App\Filament\Supervisor\Resources\Absences\AbsenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ManageAbsences extends ListRecords
{
    protected static string $resource = AbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('filament.report_absence'))
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();

                    return $data;
                }),
        ];
    }
}
