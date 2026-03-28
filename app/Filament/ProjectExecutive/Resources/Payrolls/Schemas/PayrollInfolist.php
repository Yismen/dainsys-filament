<?php

namespace App\Filament\ProjectExecutive\Resources\Payrolls\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayrollInfolist
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
                TextEntry::make('salary_rate')
                    ->numeric(),
                TextEntry::make('total_hours')
                    ->numeric(),
                TextEntry::make('salary_income')
                    ->numeric(),
                TextEntry::make('medical_licence')
                    ->numeric(),
                TextEntry::make('gross_income')
                    ->numeric(),
                TextEntry::make('deduction_ars')
                    ->numeric(),
                TextEntry::make('deduction_afp')
                    ->numeric(),
                TextEntry::make('deductions_other')
                    ->label('Deductions Other')
                    ->numeric(),
                TextEntry::make('total_deductions')
                    ->numeric(),
                TextEntry::make('nightly_incomes')
                    ->numeric(),
                TextEntry::make('overtime_incomes')
                    ->numeric(),
                TextEntry::make('holiday_incomes')
                    ->numeric(),
                TextEntry::make('additional_incentives_1')
                    ->numeric(),
                TextEntry::make('additional_incentives_2')
                    ->numeric(),
                TextEntry::make('net_payroll')
                    ->numeric(),
                TextEntry::make('total_payroll')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
