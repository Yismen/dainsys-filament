<?php

namespace App\Filament\Recruitment\Resources\RecruitmentStages\Pages;

use App\Filament\Recruitment\Resources\RecruitmentStages\RecruitmentStageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecruitmentStages extends ListRecords
{
    protected static string $resource = RecruitmentStageResource::class;

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
