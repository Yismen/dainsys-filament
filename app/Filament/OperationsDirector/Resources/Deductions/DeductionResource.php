<?php

namespace App\Filament\OperationsDirector\Resources\Deductions;

use App\Filament\OperationsDirector\Resources\Deductions\Pages\ListDeductions;
use App\Filament\OperationsDirector\Resources\Deductions\Schemas\DeductionInfolist;
use App\Filament\OperationsDirector\Resources\Deductions\Tables\DeductionsTable;
use App\Models\Deduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMinusCircle;

    protected static ?int $navigationSort = 7;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll Information';

    public static function infolist(Schema $schema): Schema
    {
        return DeductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeductionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeductions::route('/'),
        ];
    }
}
