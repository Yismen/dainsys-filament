<?php

namespace App\Filament\App\Resources\PaymentTypeResource\Pages;

use App\Filament\App\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentType extends CreateRecord
{
    protected static string $resource = PaymentTypeResource::class;
}
