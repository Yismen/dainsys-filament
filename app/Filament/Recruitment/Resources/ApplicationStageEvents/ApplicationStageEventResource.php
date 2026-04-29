<?php

namespace App\Filament\Recruitment\Resources\ApplicationStageEvents;

use App\Filament\Recruitment\Enums\RecruitmentNavigationEnum;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\Pages\ListApplicationStageEvents;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\Schemas\ApplicationStageEventForm;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\Schemas\ApplicationStageEventInfolist;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\Tables\ApplicationStageEventsTable;
use App\Models\ApplicationStageEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ApplicationStageEventResource extends Resource
{
    protected static ?string $model = ApplicationStageEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static UnitEnum|string|null $navigationGroup = RecruitmentNavigationEnum::Recruitment;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ApplicationStageEventForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(ApplicationStageEventInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return ApplicationStageEventsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicationStageEvents::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['application.applicant', 'recruitmentStage'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
