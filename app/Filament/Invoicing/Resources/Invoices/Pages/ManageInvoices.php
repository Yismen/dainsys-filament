<?php

namespace App\Filament\Invoicing\Resources\Invoices\Pages;

use App\Filament\Invoicing\Resources\Invoices\InvoiceResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoices extends ManageRecords
{
    protected static string $resource = InvoiceResource::class;
}
