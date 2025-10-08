<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\TerminationResource\Pages\ListTerminations;
use App\Filament\App\Resources\TerminationResource\Pages\CreateTermination;
use App\Filament\App\Resources\TerminationResource\Pages\ViewTermination;
use App\Filament\App\Resources\TerminationResource\Pages\EditTermination;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Termination;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HumanResourceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Forms\TerminationTypeSchema;
use App\Filament\Support\Forms\TerminationReasonSchema;
use App\Filament\App\Resources\TerminationResource\Pages;
use App\Filament\App\Resources\TerminationResource\RelationManagers;

class TerminationResource extends Resource
{
    use HumanResourceSupportMenu;

    protected static ?string $model = Termination::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->autofocus()
                            ->searchable()
                            ->required(),
                        Select::make('termination_type_id')
                            ->relationship('terminationType', 'name')
                            ->createOptionForm(TerminationTypeSchema::toArray())
                            ->createOptionModalHeading('Create Termination Type')
                            ->required(),
                        Select::make('termination_reason_id')
                            ->relationship('terminationReason', 'name')
                            ->createOptionForm(TerminationReasonSchema::toArray())
                            ->createOptionModalHeading('Create Termination Reason')
                            ->required(),
                        DatePicker::make('date')
                            ->native(false)
                            ->default(now())
                            ->required(),
                        Toggle::make('rehireable')
                            ->default(true)
                            ->required(),
                        Textarea::make('comments')
                            ->maxLength(65535)
                            ->columnSpanFull(),
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
                TextColumn::make('terminationType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('terminationReason.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                IconColumn::make('rehireable')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
                ViewAction::make(),
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
            'index' => ListTerminations::route('/'),
            'create' => CreateTermination::route('/create'),
            'view' => ViewTermination::route('/{record}'),
            'edit' => EditTermination::route('/{record}/edit'),
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
