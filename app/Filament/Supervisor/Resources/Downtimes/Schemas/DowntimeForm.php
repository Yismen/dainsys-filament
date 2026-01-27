<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Schemas;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->columnSpanFull()
                    ->visibleOn('edit'),
                DatePicker::make('date')
                    ->default(now())
                    ->required(),
                Select::make('employee_id')
                    ->options(ModelListService::make(
                        model: Employee::query()
                            ->whereHas('supervisor', function ($query) {
                                $query->where('id', auth()->user()->supervisor?->id);
                            }),
                        value_field: 'full_name')
                    )
                    ->searchable()
                    ->required(),
                Select::make('campaign_id')
                    ->options(ModelListService::get(model: Campaign::query()->where('revenue_type', RevenueTypes::Downtime)))
                    ->searchable()
                    ->required(),
                Select::make('downtime_reason_id')
                    ->options(ModelListService::get(model: DowntimeReason::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('total_time')
                    ->required()
                    ->numeric(),
                Textarea::make('request_comment')
                    ->label('Request comment')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
