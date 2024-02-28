<?php

namespace App\Filament\Resources\MailingSubscriptionResource\Pages;

use Filament\Actions;
use App\Models\MailingSubscription;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MailingSubscriptionResource;

class CreateMailingSubscription extends CreateRecord
{
    protected static string $resource = MailingSubscriptionResource::class;



    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (MailingSubscription::where('user_id', $data['user_id'])->where('mailable', $data['mailable'])->count()) {
            Notification::make()
                ->danger()
                ->title('This subscriptions exists already')
                ->send();

            $this->halt();
        }

        return $data;
    }
}
