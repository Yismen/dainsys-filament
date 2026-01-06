<?php

namespace App\Filament\HumanResource\Resources\Projects;

use BackedEnum;
use App\Models\Project;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Resources\Projects\Pages\EditProject;
use App\Filament\HumanResource\Resources\Projects\Pages\ViewProject;
use App\Filament\HumanResource\Resources\Projects\Pages\ListProjects;
use App\Filament\HumanResource\Resources\Projects\Pages\CreateProject;
use App\Filament\HumanResource\Resources\Projects\Schemas\ProjectForm;
use App\Filament\HumanResource\Resources\Projects\Tables\ProjectsTable;
use App\Filament\HumanResource\Clusters\HrManagement\HrManagementCluster;
use App\Filament\HumanResource\Resources\Projects\Schemas\ProjectInfolist;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = HrManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
