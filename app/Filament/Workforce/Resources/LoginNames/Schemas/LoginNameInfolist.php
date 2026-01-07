<?php

namespace App\Filament\Workforce\Resources\LoginNames\Schemas;

use App\Models\LoginName;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LoginNameInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('login_name'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (LoginName $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
