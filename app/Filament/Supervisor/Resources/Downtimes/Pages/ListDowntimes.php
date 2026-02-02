<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Pages;

use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\Employee;
use App\Enums\RevenueTypes;
use App\Filament\Actions\CreateMultipleDowntimesAction;
use Filament\Actions\Action;
use App\Models\DowntimeReason;
use App\Services\ModelListService;
use Filament\Actions\CreateAction;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use Filament\Notifications\Notification;

class ListDowntimes extends ListRecords
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            CreateMultipleDowntimesAction::make(),
        ];
    }
}
