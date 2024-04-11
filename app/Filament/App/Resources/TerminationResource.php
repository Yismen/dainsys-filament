<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Termination;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->autofocus()
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('termination_type_id')
                            ->relationship('terminationType', 'name')
                            ->createOptionForm(TerminationTypeSchema::toArray())
                            ->createOptionModalHeading('Create Termination Type')
                            ->required(),
                        Forms\Components\Select::make('termination_reason_id')
                            ->relationship('terminationReason', 'name')
                            ->createOptionForm(TerminationReasonSchema::toArray())
                            ->createOptionModalHeading('Create Termination Reason')
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->native(false)
                            ->default(now())
                            ->required(),
                        Forms\Components\Toggle::make('rehireable')
                            ->default(true)
                            ->required(),
                        Forms\Components\Textarea::make('comments')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('terminationType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('terminationReason.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('rehireable')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTerminations::route('/'),
            'create' => Pages\CreateTermination::route('/create'),
            'view' => Pages\ViewTermination::route('/{record}'),
            'edit' => Pages\EditTermination::route('/{record}/edit'),
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
