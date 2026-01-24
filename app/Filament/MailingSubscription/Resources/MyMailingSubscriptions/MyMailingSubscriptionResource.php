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
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\MailingSubscription\Resources\MyMailingSubscriptions\Pages\ManageMyMailingSubscriptions;
use App\Models\MailableUser;

class MyMailingSubscriptionResource extends Resource
{
    protected static ?string $model = MailableUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $recordTitleAttribute = 'mailable';

    protected static ?string $modelLabel = 'My Email Subscription';

    protected static ?string $pluralModelLabel = 'My Email Subscriptions';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return true;
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('mailable')
            ->columns([
                TextColumn::make('mailable.name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mailable.description')
                    ->label(__('Description'))
                    ->searchable(),
            ])
            ->filters([
            ])
            ->recordActions([
                DeleteAction::make('Unsubscribe')
                    ->modalHeading(fn (MailableUser $record): string => "Unsubscribe from {$record->mailable->name}")
                    // ->modalHeading(fn (MailableUser $record): string => "Unsubscribe from {$record->mailable}")
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
            // ->withoutGlobalScopes([
            //     SoftDeletingScope::class,
            // ]);
            ;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->where('user_id', auth()->id());

        return $query;
    }
}
