<?php

namespace App\Filament\ProjectExecutive\Resources\Payrolls;

use App\Filament\ProjectExecutive\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\ProjectExecutive\Resources\Payrolls\Schemas\PayrollInfolist;
use App\Filament\ProjectExecutive\Resources\Payrolls\Tables\PayrollsTable;
use App\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?int $navigationSort = 4;

    public static function infolist(Schema $schema): Schema
    {
        return PayrollInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
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
            'index' => ListPayrolls::route('/'),
        ];
    }
}
