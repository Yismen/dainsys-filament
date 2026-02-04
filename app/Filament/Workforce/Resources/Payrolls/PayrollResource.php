<?php

namespace App\Filament\Workforce\Resources\Payrolls;

use App\Filament\Imports\PayrollImporter;
use App\Filament\Workforce\Resources\Payrolls\Pages\ManagePayrolls;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'payable_date';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('payable_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label('Employee')
                    ->placeholder('-'),
                TextEntry::make('gross_income')
                    ->numeric(),
                TextEntry::make('taxable_payroll')
                    ->numeric(),
                TextEntry::make('hourly_rate')
                    ->numeric(),
                TextEntry::make('regular_hours')
                    ->numeric(),
                TextEntry::make('overtime_hours')
                    ->numeric(),
                TextEntry::make('holiday_hours')
                    ->numeric(),
                TextEntry::make('night_shift_hours')
                    ->numeric(),
                TextEntry::make('additional_incentives_1')
                    ->numeric(),
                TextEntry::make('additional_incentives_2')
                    ->numeric(),
                TextEntry::make('deduction_afp')
                    ->numeric(),
                TextEntry::make('deduction_ars')
                    ->numeric(),
                TextEntry::make('other_deductions')
                    ->numeric(),
                TextEntry::make('net_payroll')
                    ->numeric(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Payroll $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->headerActions([
                ImportAction::make()
                    ->importer(PayrollImporter::class)
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
                TextColumn::make('gross_income')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('taxable_payroll')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('regular_hours')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('overtime_hours')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('net_payroll')
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('payable_date')
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label('Payable date from'),
                        DatePicker::make('payable_date_until')
                            ->label('Payable date until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payable_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payable_date', '>=', $date),
                            )
                            ->when(
                                $data['payable_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payable_date', '<=', $date),
                            );
                    }),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayrolls::route('/'),
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
