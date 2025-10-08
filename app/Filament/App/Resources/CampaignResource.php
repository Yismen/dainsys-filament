<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\CampaignResource\Pages\ListCampaigns;
use App\Filament\App\Resources\CampaignResource\Pages\CreateCampaign;
use App\Filament\App\Resources\CampaignResource\Pages\EditCampaign;
use Filament\Forms;
use Filament\Tables;
use App\Models\Campaign;
use Filament\Tables\Table;
use App\Enums\RevenueTypes;
use App\Enums\CampaignSources;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\CampaignResource\Pages;
use App\Filament\App\Resources\CampaignResource\RelationManagers;

class CampaignResource extends Resource
{
    use WorkforceSupportMenu;

    protected static ?string $model = Campaign::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('project_id')
                            ->searchable()
                            ->preload()
                            ->relationship('project', 'name')
                            ->required(),
                        Select::make('source')
                            ->options(CampaignSources::toArray())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('revenue_type')
                            ->options(RevenueTypes::toArray())
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('goal')
                            ->required()
                            ->minValue(0)
                            ->step(0.10)
                            ->numeric(),
                        TextInput::make('rate')
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
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('source')
                    ->searchable(),
                TextColumn::make('revenue_type')
                    ->searchable(),
                TextColumn::make('goal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListCampaigns::route('/'),
            'create' => CreateCampaign::route('/create'),
            'edit' => EditCampaign::route('/{record}/edit'),
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
