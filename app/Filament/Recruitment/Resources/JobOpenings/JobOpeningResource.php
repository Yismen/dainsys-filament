<?php

namespace App\Filament\Recruitment\Resources\JobOpenings;

use App\Filament\Recruitment\Enums\RecruitmentNavigationEnum;
use App\Filament\Recruitment\Resources\JobOpenings\Pages\ListJobOpenings;
use App\Filament\Recruitment\Resources\JobOpenings\Schemas\JobOpeningForm;
use App\Filament\Recruitment\Resources\JobOpenings\Schemas\JobOpeningInfolist;
use App\Filament\Recruitment\Resources\JobOpenings\Tables\JobOpeningsTable;
use App\Models\JobOpening;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JobOpeningResource extends Resource
{
    protected static ?string $model = JobOpening::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static UnitEnum|string|null $navigationGroup = RecruitmentNavigationEnum::Recruitment;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(JobOpeningForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(JobOpeningInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return JobOpeningsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobOpenings::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['position', 'department', 'site'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
