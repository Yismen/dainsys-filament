<?php

namespace App\Filament\HumanResource\Resources\PaymentTypes\Pages;

use App\Filament\HumanResource\Resources\PaymentTypes\PaymentTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentTypes extends ManageRecords
{
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
