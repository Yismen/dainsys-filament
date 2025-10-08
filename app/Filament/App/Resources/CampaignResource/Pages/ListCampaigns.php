<?php

namespace App\Filament\App\Resources\CampaignResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampaigns extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
