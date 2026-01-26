<?php

namespace App\Filament\Supervisor\Resources;

use BackedEnum;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Enums\HRActivityTypes;
use Filament\Resources\Resource;
use App\Models\HRActivityRequest;
use App\Services\ModelListService;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Enums\HRActivityRequestStatuses;
use Filament\Schemas\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Supervisor\Resources\Pages\ViewHRActivityRequest;
use App\Filament\Supervisor\Resources\Pages\ListHRActivityRequests;

class HRActivityRequestResource extends Resource
{
    protected static ?string $model = HRActivityRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'My HR Requests';

    protected static ?string $modelLabel = 'HR Request';

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        return parent::getEloquentQuery()
            ->where('supervisor_id', $supervisor?->id);
    }

    public static function canAccess(): bool
    {
        $supervisor = Auth::user()?->supervisor;

        return $supervisor?->is_active === true;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
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
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Pending'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(HRActivityRequestStatuses::class)
                    ->multiple(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->schema([
                        Select::make('employee_id')
                            ->options(
                                ModelListService::make(
                                    value_field: 'full_name',
                                    model: Employee::query()
                                        ->active()
                                        ->whereHas('hires', function (Builder $query): void {
                                        $query->where('supervisor_id', Auth::user()?->supervisor?->id);
                                    })
                                )
                            )
                            ->required()
                            ->searchable()
                            ->label('Employee'),
                        Select::make('activity_type')
                            ->options(HRActivityTypes::class)
                            ->required()
                            ->label('Activity Type'),
                        Textarea::make('description')
                            ->rows(3)
                            ->label('Description')
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
                    ->successNotificationTitle('HR Activity Request created successfully'),
            ])
            ->defaultSort('requested_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Information')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID'),
                        TextEntry::make('employee.full_name')
                            ->label('Employee'),
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
                Section::make('Details')
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
