<?php

namespace App\Filament\OperationsDirector\Resources\Absences;

use App\Filament\OperationsDirector\Resources\Absences\Pages\ListAbsences;
use App\Filament\OperationsDirector\Resources\Absences\Tables\AbsencesTable;
use App\Models\Absence;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AbsenceResource extends Resource
{
    protected static ?string $model = Absence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return AbsencesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAbsences::route('/'),
        ];
    }
}
