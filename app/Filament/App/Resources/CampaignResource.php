<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Campaign;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\RevenueTypes;
use App\Enums\CampaignSources;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\CampaignResource\Pages;
use App\Filament\App\Resources\CampaignResource\RelationManagers;

class CampaignResource extends Resource
{
    use WorkforceSupportMenu;

    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('project_id')
                            ->searchable()
                            ->preload()
                            ->relationship('project', 'name')
                            ->required(),
                        Forms\Components\Select::make('source')
                            ->options(CampaignSources::toArray())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('revenue_type')
                            ->options(RevenueTypes::toArray())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('goal')
                            ->required()
                            ->minValue(0)
                            ->step(0.10)
                            ->numeric(),
                        Forms\Components\TextInput::make('rate')
                            ->required()
                            ->minValue(0)
                            ->step(0.10)
                            ->numeric(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('revenue_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('goal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
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
