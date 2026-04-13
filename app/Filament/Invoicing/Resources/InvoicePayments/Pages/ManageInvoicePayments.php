<?php

namespace App\Filament\Invoicing\Resources\InvoicePayments\Pages;

use App\Filament\Invoicing\Resources\InvoicePayments\InvoicePaymentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoicePayments extends ManageRecords
{
    protected static string $resource = InvoicePaymentResource::class;
}
