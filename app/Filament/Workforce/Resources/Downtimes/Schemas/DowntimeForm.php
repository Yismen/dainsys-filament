<?php

namespace App\Filament\Workforce\Resources\Downtimes\Schemas;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Rules\UniqueCombination;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DowntimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->inlineLabel()
                    ->badge()
                    ->columnSpanFull(),
                DatePicker::make('date')
                    ->default(now())
                    ->maxDate(now())
                    ->minDate(now()->subDays(20))
                    ->required(),
                Select::make('campaign_id')
                    ->options(ModelListService::get(model: Campaign::query()->where('revenue_type', RevenueTypes::Downtime)))
                    ->searchable()
                    ->required(),
                Select::make('downtime_reason_id')
                    ->options(ModelListService::get(model: DowntimeReason::query()))
                    ->searchable()
                    ->required(),
                Select::make('employee_id')
                    ->options(
                        ModelListService::get(
                            model: Employee::query()
                                ->activesOrRecentlyTerminated(),
                            value_field: 'full_name'
                        )
                    )
                    ->searchable()
                    ->required()
                    ->rules(fn (?Downtime $record): array => [
                        new UniqueCombination(
                            model: Downtime::class,
                            fields: ['employee_id', 'date', 'campaign_id'],
                            exceptId: $record?->id,
                        ),
                    ]),
                TextInput::make('total_time')
                    ->required()
                    ->numeric(),
            ]);
    }
}

