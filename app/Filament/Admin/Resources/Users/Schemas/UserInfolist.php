<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('filament.name')),
                TextEntry::make('email')
                    ->copyable()
                    ->label(__('filament.email')),
                IconEntry::make('is_active')
                    ->label(__('filament.is_active')),
                TextEntry::make('email_verified_at')
                    ->label(__('filament.email_verified'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('roles.name')
                    ->label(__('filament.roles'))
                    ->badge()
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (User $record): bool => $record->trashed()),
            ]);
    }
}
