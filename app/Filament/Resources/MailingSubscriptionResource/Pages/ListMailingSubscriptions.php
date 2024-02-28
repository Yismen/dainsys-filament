<?php

namespace App\Filament\Resources\MailingSubscriptionResource\Pages;

use App\Filament\Resources\MailingSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailingSubscriptions extends ListRecords
{
    protected static string $resource = MailingSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
