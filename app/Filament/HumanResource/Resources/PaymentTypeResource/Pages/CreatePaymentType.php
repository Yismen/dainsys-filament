<?php

namespace App\Filament\HumanResource\Resources\PaymentTypeResource\Pages;

use App\Filament\HumanResource\Resources\PaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentType extends CreateRecord
{
    protected static string $resource = PaymentTypeResource::class;
}
