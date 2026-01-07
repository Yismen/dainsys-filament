<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('first_name'),
                TextEntry::make('second_first_name')
                    ->placeholder('-'),
                TextEntry::make('last_name'),
                TextEntry::make('second_last_name')
                    ->placeholder('-'),
                TextEntry::make('full_name')
                    ->placeholder('-'),
                TextEntry::make('personal_id_type'),
                TextEntry::make('personal_id'),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('cellphone'),
                TextEntry::make('gender')
                    ->badge(),
                IconEntry::make('has_kids')
                    ->boolean(),
                TextEntry::make('citizenship.name')
                    ->label('Citizenship'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Employee $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
