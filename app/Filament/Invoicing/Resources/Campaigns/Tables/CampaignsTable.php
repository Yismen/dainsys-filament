<?php

namespace App\Filament\Invoicing\Resources\Campaigns\Tables;

use App\Enums\RevenueTypes;
use App\Models\Project;
use App\Models\Source;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('invoiceAgent.name')
                    ->label(__('Agent'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->placeholder(__('Unassigned')),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
                SelectFilter::make('source_id')
                    ->label(__('Source'))
                    ->options(ModelListService::make(Source::query()))
                    ->searchable(),
                SelectFilter::make('revenue_type')
                    ->label(__('Revenue type'))
                    ->options(RevenueTypes::class),
                TrashedFilter::make()
                    ->label(__('Trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create Campaign'))
                    ->modalHeading(__('Create Campaign')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Campaign')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Campaign')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('Force delete')),
                    RestoreBulkAction::make()
                        ->label(__('Restore')),
                ])->label(__('Bulk actions')),
            ]);
    }
}
