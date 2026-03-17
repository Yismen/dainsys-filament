<?php

namespace App\Filament\Supervisor\Resources\Deductions;

use App\Filament\Supervisor\Resources\Deductions\Pages\ListDeductions;
use App\Filament\Supervisor\Resources\Deductions\Tables\DeductionsTable;
use App\Models\Deduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-minus-circle';

    protected static ?string $navigationLabel = 'Deductions';

    protected static ?string $pluralModelLabel = 'Team Deductions';

    protected static ?int $navigationSort = 7;

    protected static string|UnitEnum|null $navigationGroup = 'Team Insights';

    public static function table(Table $table): Table
    {
        return DeductionsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee', function ($query) use ($supervisor): void {
                $query
                    ->where('supervisor_id', $supervisor->id)
                    ->active();
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeductions::route('/'),
        ];
    }
}
