<?php

namespace App\Filament\App\Resources\PaymentTypeResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentTypes extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
