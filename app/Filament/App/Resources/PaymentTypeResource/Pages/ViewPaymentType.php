<?php

namespace App\Filament\App\Resources\PaymentTypeResource\Pages;

use App\Filament\App\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentType extends ViewRecord
{
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
