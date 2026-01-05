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
                    ->label('ID'),
                TextEntry::make('employee.id')
                    ->label('Employee'),
                TextEntry::make('ars.name')
                    ->label('Ars'),
                TextEntry::make('afp.name')
                    ->label('Afp'),
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
