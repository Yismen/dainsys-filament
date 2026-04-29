<?php

namespace App\Filament\Recruitment\Resources\RecruitmentStages;

use App\Filament\Recruitment\Enums\RecruitmentNavigationEnum;
use App\Filament\Recruitment\Resources\RecruitmentStages\Pages\ListRecruitmentStages;
use App\Filament\Recruitment\Resources\RecruitmentStages\Schemas\RecruitmentStageForm;
use App\Filament\Recruitment\Resources\RecruitmentStages\Schemas\RecruitmentStageInfolist;
use App\Filament\Recruitment\Resources\RecruitmentStages\Tables\RecruitmentStagesTable;
use App\Models\RecruitmentStage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class RecruitmentStageResource extends Resource
{
    protected static ?string $model = RecruitmentStage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static UnitEnum|string|null $navigationGroup = RecruitmentNavigationEnum::Configuration;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(RecruitmentStageForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(RecruitmentStageInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return RecruitmentStagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecruitmentStages::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
