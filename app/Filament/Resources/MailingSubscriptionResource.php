<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\MailingSubscriptionResource\Pages\ListMailingSubscriptions;
use App\Filament\Resources\MailingSubscriptionResource\Pages\CreateMailingSubscription;
use App\Filament\Resources\MailingSubscriptionResource\Pages\ViewMailingSubscription;
use App\Filament\Resources\MailingSubscriptionResource\Pages\EditMailingSubscription;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\MailingService;
use Filament\Resources\Resource;
use App\Models\MailingSubscription;
use Filament\Tables\Enums\FiltersLayout;
use function Laravel\Prompts\multiselect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Resources\MailingSubscriptionResource\Pages;
use App\Filament\Resources\MailingSubscriptionResource\RelationManagers;

class MailingSubscriptionResource extends Resource
{
    protected static ?string $model = MailingSubscription::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->autofocus()
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('mailable')
                            ->required()
                            ->options(MailingService::toArray()),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mailable')
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
                SelectFilter::make('mailable')
                    ->options(MailingService::toArray()),
                SelectFilter::make('user_id')
                    ->label('User')
                    ->options(User::query()->orderBy('name')->pluck('name', 'id')),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
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
            'index' => ListMailingSubscriptions::route('/'),
            'create' => CreateMailingSubscription::route('/create'),
            'view' => ViewMailingSubscription::route('/{record}'),
            'edit' => EditMailingSubscription::route('/{record}/edit'),
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
