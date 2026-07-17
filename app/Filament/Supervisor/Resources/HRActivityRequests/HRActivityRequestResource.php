<?php

namespace App\Filament\Supervisor\Resources\HRActivityRequests;

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Filament\Supervisor\Resources\HRActivityRequests\Pages\ListHRActivityRequests;
use App\Filament\Supervisor\Resources\HRActivityRequests\Pages\ViewHRActivityRequest;
use App\Models\Employee;
use App\Models\HRActivityRequest;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class HRActivityRequestResource extends Resource
{
    protected static ?string $model = HRActivityRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'HR Requests';

    protected static ?string $modelLabel = 'HR Request';

    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'Team Management';

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        return parent::getEloquentQuery()
            ->where('supervisor_id', $supervisor?->id);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activity_type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requested_at')
                    ->label(__('filament.requested'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label(__('filament.completed'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Pending'),
            ])
            ->filters([
                Filter::make('created_at')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label(__('filament.date_from')),
                        DatePicker::make('date_until')
                            ->label(__('filament.date_until')),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(
                        ModelListService::make(
                            value_field: 'full_name',
                            model: Employee::query()
                                ->active()
                                ->whereHas('supervisor', function (Builder $query): void {
                                    $query->where('id', Auth::user()?->supervisor?->id);
                                })
                        )
                    )
                    ->searchable(),
                SelectFilter::make('activity_type')
                    ->label(__('filament.activity_type'))
                    ->options(HRActivityTypes::class),
                SelectFilter::make('status')
                    ->options(HRActivityRequestStatuses::class)
                    ->multiple(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (HRActivityRequest $record): bool => $record->status === HRActivityRequestStatuses::Requested)
                    ->successNotificationTitle(__('filament.hr_activity_request_updated'))
                    ->schema([
                        Select::make('employee_id')
                            ->options(
                                ModelListService::make(
                                    value_field: 'full_name',
                                    model: Employee::query()
                                        ->active()
                                        ->whereHas('supervisor', function (Builder $query): void {
                                            $query->where('id', Auth::user()?->supervisor?->id);
                                        })
                                )
                            )
                            ->required()
                            ->searchable()
                            ->label(__('filament.employee')),
                        Select::make('activity_type')
                            ->options(HRActivityTypes::class)
                            ->required()
                            ->label(__('filament.activity_type')),
                        Textarea::make('description')
                            ->rows(3)
                            ->label(__('filament.description'))
                            ->placeholder('Provide additional details about this request...')
                            ->required()
                            ->minLength(5),
                    ]),
                DeleteAction::make()
                    ->visible(fn (HRActivityRequest $record): bool => $record->status === HRActivityRequestStatuses::Requested),
            ])
            ->recordActionsAlignment('left')
            ->toolbarActions([
                CreateAction::make()
                    ->schema([
                        Select::make('employee_id')
                            ->options(
                                ModelListService::make(
                                    value_field: 'full_name',
                                    model: Employee::query()
                                        ->active()
                                        ->whereHas('supervisor', function (Builder $query): void {
                                            $query->where('id', Auth::user()?->supervisor?->id);
                                        })
                                )
                            )
                            ->required()
                            ->searchable()
                            ->label(__('filament.employee')),
                        Select::make('activity_type')
                            ->options(HRActivityTypes::class)
                            ->required()
                            ->label(__('filament.activity_type')),
                        Textarea::make('description')
                            ->rows(3)
                            ->label(__('filament.description'))
                            ->placeholder('Provide additional details about this request...')
                            ->required()
                            ->minLength(5),
                    ])
                    ->mutateDataUsing(function (array $data): array {
                        $data['supervisor_id'] = Auth::user()?->supervisor?->id;
                        $data['requested_at'] = now();
                        $data['status'] = HRActivityRequestStatuses::Requested;

                        return $data;
                    })
                    ->successNotificationTitle(__('filament.hr_activity_request_created')),
            ])
            ->defaultSort('requested_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.request_information'))
                    ->schema([
                        TextEntry::make('id')
                            ->label(__('filament.id')),
                        TextEntry::make('employee.full_name')
                            ->label(__('filament.employee')),
                        TextEntry::make('activity_type')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('requested_at')
                            ->dateTime(),
                        TextEntry::make('completed_at')
                            ->dateTime()
                            ->placeholder('Not completed yet'),
                    ])
                    ->columns(2),
                Section::make(__('filament.details'))
                    ->schema([
                        TextEntry::make('description')
                            ->placeholder('No description provided')
                            ->columnSpanFull(),
                        TextEntry::make('completion_comment')
                            ->placeholder('Not completed yet')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->completion_comment !== null),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHRActivityRequests::route('/'),
            'view' => ViewHRActivityRequest::route('/{record}'),
        ];
    }
}
