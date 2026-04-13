<?php

namespace App\Filament\Invoicing\Resources\InvoiceCancellations\Pages;

use App\Filament\Invoicing\Resources\InvoiceCancellations\InvoiceCancellationResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoiceCancellations extends ManageRecords
{
    protected static string $resource = InvoiceCancellationResource::class;
}
