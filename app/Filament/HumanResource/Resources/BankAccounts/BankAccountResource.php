<?php

namespace App\Filament\HumanResource\Resources\BankAccounts;

use App\Filament\HumanResource\Enums\HRNavigationEnum;
use App\Filament\HumanResource\Resources\BankAccounts\Pages\ManageBankAccounts;
use App\Filament\HumanResource\Resources\Banks\Schemas\BankForm;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'account';

    protected static string|UnitEnum|null $navigationGroup = HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(
                        ModelListService::make(
                            model: Employee::query(),
                            value_field: 'full_name',
                        )
                    )
                    ->searchable()
                    ->required(),
                Select::make('bank_id')
                    ->relationship('bank', 'name')
                    ->options(
                        ModelListService::make(Bank::query())
                    )
                    ->searchable()
                    ->createOptionForm([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true, table: (new Bank)->getTable())
                                    ->autofocus(),
                                TextInput::make('person_of_contact'),
                                TextInput::make('phone')
                                    ->tel(),
                                TextInput::make('email')
                                    ->email(),
                                Textarea::make('description')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->required(),
                TextInput::make('account')
                    ->required()
                    ->minLength(5)
                    ->maxLength(50)
                    ->trim()
                    ->unique(ignoreRecord: true, table: (new BankAccount)->getTable()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('account')
            ->defaultSort('employee.full_name', 'asc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bank.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('account')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBankAccounts::route('/'),
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
