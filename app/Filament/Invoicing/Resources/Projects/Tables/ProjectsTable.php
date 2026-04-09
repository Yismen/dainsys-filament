<?php

namespace App\Filament\Invoicing\Resources\Projects\Tables;

use App\Models\Client;
use App\Models\User;
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
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label(__('Client'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('invoice_net_days')
                    ->label(__('Invoice net days'))
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('address')
                    ->label(__('Address'))
                    ->html()
                    ->limit(40),
                TextColumn::make('invoice_notes')
                    ->label(__('Invoice Notes'))
                    ->html()
                    ->limit(40),
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
                TrashedFilter::make()
                    ->label(__('Trashed')),
                SelectFilter::make('client_id')
                    ->label(__('Client'))
                    ->options(ModelListService::get(Client::class))
                    ->searchable(),
                SelectFilter::make('manager_id')
                    ->label(__('Manager'))
                    ->options(ModelListService::make(
                        User::query()->whereHas('roles', function (Builder $query): void {
                            $query->whereIn('name', [
                                'Project Executive Manager',
                                'Project Executive Agent',
                            ]);
                        })
                    ))
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create Project'))
                    ->modalHeading(__('Create Project')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Project')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Project')),
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
