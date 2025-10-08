<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\BankAccountResource\Pages\ListBankAccounts;
use App\Filament\App\Resources\BankAccountResource\Pages\CreateBankAccount;
use App\Filament\App\Resources\BankAccountResource\Pages\EditBankAccount;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\BankAccount;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HumanResourceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\BankAccountResource\Pages;
use App\Filament\App\Resources\BankAccountResource\RelationManagers;

class BankAccountResource extends Resource
{
    use HumanResourceSupportMenu;

    protected static ?string $model = BankAccount::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(3)
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->autofocus()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Select::make('bank_id')
                            ->relationship('bank', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('account')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bank.name')
                    ->numeric()
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankAccounts::route('/'),
            'create' => CreateBankAccount::route('/create'),
            'edit' => EditBankAccount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
