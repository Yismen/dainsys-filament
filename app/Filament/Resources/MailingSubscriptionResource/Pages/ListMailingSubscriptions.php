<?php

namespace App\Filament\Resources\MailingSubscriptionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MailingSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailingSubscriptions extends ListRecords
{
    protected static string $resource = MailingSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
