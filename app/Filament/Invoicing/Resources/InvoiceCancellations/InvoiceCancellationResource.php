<?php

namespace App\Filament\Invoicing\Resources\InvoiceCancellations;

use App\Filament\Invoicing\Resources\InvoiceCancellations\Pages\ManageInvoiceCancellations;
use App\Filament\Invoicing\Resources\InvoiceCancellations\Schemas\InvoiceCancellationForm;
use App\Filament\Invoicing\Resources\InvoiceCancellations\Schemas\InvoiceCancellationInfolist;
use App\Filament\Invoicing\Resources\InvoiceCancellations\Tables\InvoiceCancellationsTable;
use App\Models\InvoiceCancellation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceCancellationResource extends Resource
{
    protected static ?string $model = InvoiceCancellation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedXCircle;

    protected static ?string $recordTitleAttribute = 'reason';

    protected static ?int $navigationSort = 16;

    protected static string|\UnitEnum|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return InvoiceCancellationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvoiceCancellationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoiceCancellationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInvoiceCancellations::route('/'),
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
