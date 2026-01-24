<?php

namespace App\Filament\MailingSubscription\Resources\MyMailingSubscriptions;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use App\Models\MailingSubscription;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use App\Models\MyMailingSubscription;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\MailingSubscription\Resources\MyMailingSubscriptions\Pages\ManageMyMailingSubscriptions;

class MyMailingSubscriptionResource extends Resource
{
    protected static ?string $model = MailingSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'mailable';

    protected static ?string $modelLabel = 'My Mailing Subscription';

    protected static ?string $pluralModelLabel = 'My Mailing Subscriptions';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mailable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('mailable')
            ->columns([
                TextColumn::make('mailable')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                DeleteAction::make('Unsubscribe')
                    ->modalHeading(fn (MailingSubscription $record): string => "Unsubscribe from {$record->mailable}")
                    ->label('Unsubscribe'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon(Heroicon::OutlinedTrash)
                    ->label('Unsubscribe Selected'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMyMailingSubscriptions::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->where('user_id', auth()->id());

        return $query;
    }
}
