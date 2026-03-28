<?php

namespace App\Filament\ProjectExecutive\Resources\Deductions;

use App\Filament\ProjectExecutive\Resources\Deductions\Pages\ListDeductions;
use App\Filament\ProjectExecutive\Resources\Deductions\Schemas\DeductionInfolist;
use App\Filament\ProjectExecutive\Resources\Deductions\Tables\DeductionsTable;
use App\Models\Deduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMinusCircle;

    protected static ?int $navigationSort = 7;

    public static function infolist(Schema $schema): Schema
    {
        return DeductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeductionsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee.project', function (Builder $query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeductions::route('/'),
        ];
    }
}
