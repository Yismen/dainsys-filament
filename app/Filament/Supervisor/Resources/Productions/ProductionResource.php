<?php

namespace App\Filament\Supervisor\Resources\Productions;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\Productions\Pages\ListProductions;
use App\Filament\Supervisor\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $navigationLabel = 'Production';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return ProductionsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee', function ($query) use ($supervisor): void {
                $query->where('supervisor_id', $supervisor->id)
                    ->whereIn('status', [EmployeeStatuses::Hired, EmployeeStatuses::Suspended]);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductions::route('/'),
        ];
    }
}
