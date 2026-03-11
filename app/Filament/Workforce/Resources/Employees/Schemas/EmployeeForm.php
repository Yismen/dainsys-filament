<?php

namespace App\Filament\Workforce\Resources\Employees\Schemas;

use App\Schemas\Filament\HumanResource\HireEmployeeSchema;
use App\Schemas\Filament\Workforce\EmployeeSchema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                [
                    Grid::make()
                        ->columnSpanFull()
                        ->columns(3)
                        ->schema([
                            Section::make('Employee information')
                                ->columnSpan(fn (string $operation) => $operation === 'create' ? 3 : 2)
                                ->columns(2)
                                ->schema(
                                    EmployeeSchema::make(),
                                ),
                                Section::make('Hiring information')
                                    ->columnSpan(1)
                                    ->visibleOn('edit')
                                    ->schema(HireEmployeeSchema::make()),
                            ])
                ]);
    }
}
