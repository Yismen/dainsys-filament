<?php

namespace App\Filament\OperationsDirector\Resources\Incentives;

use App\Filament\OperationsDirector\Resources\Incentives\Pages\ListIncentives;
use App\Filament\OperationsDirector\Resources\Incentives\Schemas\IncentiveInfolist;
use App\Filament\OperationsDirector\Resources\Incentives\Tables\IncentivesTable;
use App\Models\Incentive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IncentiveResource extends Resource
{
    protected static ?string $model = Incentive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'payable_date';

    protected static ?int $navigationSort = 10;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll Information';

    public static function infolist(Schema $schema): Schema
    {
        return IncentiveInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncentivesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncentives::route('/'),
        ];
    }
}
