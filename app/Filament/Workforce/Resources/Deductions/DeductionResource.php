<?php

namespace App\Filament\Workforce\Resources\Deductions;

use App\Filament\Workforce\Resources\Deductions\Pages\ManageDeductions;
use App\Imports\Filament\DeductionImporter;
use App\Models\Deduction;
use App\Models\Employee;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMinusCircle;

    protected static ?string $recordTitleAttribute = 'payable_date';

    protected static ?int $navigationSort = 7;

    protected static string|UnitEnum|null $navigationGroup = 'Imports';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->headerActions([
                ImportAction::make()
                    ->importer(DeductionImporter::class)
                    ->color(Color::Indigo)
                    ->icon(Heroicon::ArrowUpTray),
            ])
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->filters([
                SelectFilter::make('payable_date')
                    ->label('Payable date')
                    ->options(fn (): array => Deduction::query()
                        ->distinct()
                        ->orderBy('payable_date', 'desc')
                        ->pluck('payable_date', 'payable_date')
                        ->toArray())
                    ->searchable(),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDeductions::route('/'),
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
