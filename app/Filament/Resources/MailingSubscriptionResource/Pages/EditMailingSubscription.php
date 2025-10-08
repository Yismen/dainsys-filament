<?php

namespace App\Filament\Resources\MailingSubscriptionResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions;
use App\Models\MailingSubscription;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MailingSubscriptionResource;

class EditMailingSubscription extends EditRecord
{
    protected static string $resource = MailingSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }



    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
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
