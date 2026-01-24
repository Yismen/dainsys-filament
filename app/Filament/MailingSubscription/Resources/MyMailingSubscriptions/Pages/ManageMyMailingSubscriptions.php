<?php

namespace App\Filament\MailingSubscription\Resources\MyMailingSubscriptions\Pages;

use App\Filament\MailingSubscription\Resources\MyMailingSubscriptions\MyMailingSubscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMyMailingSubscriptions extends ManageRecords
{
    protected static string $resource = MyMailingSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
