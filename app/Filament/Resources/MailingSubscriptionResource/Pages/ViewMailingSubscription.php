<?php

namespace App\Filament\Resources\MailingSubscriptionResource\Pages;

use App\Filament\Resources\MailingSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMailingSubscription extends ViewRecord
{
    protected static string $resource = MailingSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
