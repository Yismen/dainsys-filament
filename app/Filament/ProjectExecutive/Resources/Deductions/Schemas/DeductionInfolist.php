<?php

namespace App\Filament\ProjectExecutive\Resources\Deductions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DeductionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('payable_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label('Employee')
                    ->placeholder('-'),
                TextEntry::make('amount')
                    ->numeric(decimalPlaces: 2),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
