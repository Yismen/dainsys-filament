<?php

namespace App\Filament\HumanResource\Resources\PaymentTypeResource\Pages;

use App\Filament\HumanResource\Resources\PaymentTypeResource;
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
