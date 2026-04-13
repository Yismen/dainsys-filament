<?php

namespace App\Filament\Invoicing\Resources\InvoicePayments;

use App\Filament\Invoicing\Resources\InvoicePayments\Pages\ManageInvoicePayments;
use App\Filament\Invoicing\Resources\InvoicePayments\Schemas\InvoicePaymentForm;
use App\Filament\Invoicing\Resources\InvoicePayments\Schemas\InvoicePaymentInfolist;
use App\Filament\Invoicing\Resources\InvoicePayments\Tables\InvoicePaymentsTable;
use App\Models\InvoicePayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicePaymentResource extends Resource
{
    protected static ?string $model = InvoicePayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'reference';

    protected static ?int $navigationSort = 15;

    protected static string|\UnitEnum|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return InvoicePaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvoicePaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoicePaymentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInvoicePayments::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
