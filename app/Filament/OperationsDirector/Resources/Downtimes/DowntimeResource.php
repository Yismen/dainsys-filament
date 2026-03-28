<?php

namespace App\Filament\OperationsDirector\Resources\Downtimes;

use App\Filament\OperationsDirector\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\OperationsDirector\Resources\Downtimes\Tables\DowntimesTable;
use App\Models\Downtime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DowntimeResource extends Resource
{
    protected static ?string $model = Downtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        return DowntimesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDowntimes::route('/'),
        ];
    }
}
