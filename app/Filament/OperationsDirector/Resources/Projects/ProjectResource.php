<?php

namespace App\Filament\OperationsDirector\Resources\Projects;

use App\Filament\OperationsDirector\Resources\Projects\Pages\ListProjects;
use App\Filament\OperationsDirector\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\OperationsDirector\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Projects';

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
        ];
    }
}
