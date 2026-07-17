<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Schemas;

use App\Models\SocialSecurity;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SocialSecurityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('employee.id')
                    ->label(__('filament.employee')),
                TextEntry::make('ars.name')
                    ->label(__('filament.ars')),
                TextEntry::make('afp.name')
                    ->label(__('filament.afp')),
                TextEntry::make('number'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (SocialSecurity $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
