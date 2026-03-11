<?php

namespace App\Filament\HumanResource\Widgets;

use App\Models\Employee;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class UpcomingEmployeeBirthdays extends TableWidget
{
    protected static ?string $heading = 'Upcoming Employee Birthdays';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->wrap()
                    ->wrapHeader()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->label('Birthday')
                    ->date()
                    ->wrap()
                    ->wrapHeader()
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format('F j').' ('.Carbon::parse($state)->age.' years)'),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('cellphone')
                    ->label('Phone'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modal()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('profile_photo')
                                    ->label('Photo')
                                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                                    ->circular(),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('id')
                                    ->label('ID'),
                                TextEntry::make('first_name'),
                                TextEntry::make('second_first_name')
                                    ->placeholder('-'),
                                TextEntry::make('last_name'),
                                TextEntry::make('second_last_name')
                                    ->placeholder('-'),
                                TextEntry::make('full_name')
                                    ->placeholder('-'),
                                TextEntry::make('personal_id_type'),
                                TextEntry::make('personal_id'),
                                TextEntry::make('date_of_birth')
                                    ->date(),
                                TextEntry::make('cellphone'),
                                TextEntry::make('gender')
                                    ->badge(),
                                IconEntry::make('has_kids')
                                    ->boolean(),
                                TextEntry::make('citizenship.name')
                                    ->label('Citizenship'),
                                TextEntry::make('internal_id'),
                                Section::make('Job Information')
                                    ->columns(5)
                                    ->columnSpanFull()
                                    ->components([
                                        TextEntry::make('hired_at')
                                            ->date(),
                                        TextEntry::make('site.name')
                                            ->label('Site'),
                                        TextEntry::make('project.name')
                                            ->label('Project'),
                                        TextEntry::make('position.name')
                                            ->label('Position'),
                                        TextEntry::make('supervisor.name')
                                            ->label('Supervisor'),
                                    ]),
                                TextEntry::make('deleted_at')
                                    ->dateTime()
                                    ->visible(fn (Employee $record): bool => $record->trashed()),
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ]),
                    ]),
            ])
            ->defaultSort('date_of_birth', 'asc');
    }

    protected function getTableQuery(): Builder
    {
        $filters = $this->filters ?? [];

        $today = Carbon::now()->startOfDay();
        $until = Carbon::now()->addDays(10)->endOfDay();

        return Employee::query()
            ->active()
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', '>=', $today->day)
            ->whereDay('date_of_birth', '<=', $until->day)
            ->orderByRaw("DATE_FORMAT(date_of_birth, '%m-%d') asc");

        if (isset($filters['site']) && ! empty($filters['site'])) {
            $query->whereHas('site', function ($q) use ($filters): void {
                $q->whereIn('id', $filters['site']);
            });
        }

        if (isset($filters['project']) && ! empty($filters['project'])) {
            $query->whereHas('project', function ($q) use ($filters): void {
                $q->whereIn('id', $filters['project']);
            });
        }

        if (isset($filters['supervisor']) && ! empty($filters['supervisor'])) {
            $query->whereHas('supervisor', function ($q) use ($filters): void {
                $q->whereIn('id', $filters['supervisor']);
            });
        }

        return $query;
    }

    /**
     * Get database-specific syntax for extracting month and day from date.
     */
    protected function getDateExtractionSyntax(string $driver): array
    {
        return match ($driver) {
            'mysql' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            'pgsql' => ['EXTRACT(MONTH FROM date_of_birth)', 'EXTRACT(DAY FROM date_of_birth)'],
            'sqlite' => ['CAST(strftime("%m", date_of_birth) AS INTEGER)', 'CAST(strftime("%d", date_of_birth) AS INTEGER)'],
            'sqlsrv' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            default => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'], // Default to MySQL syntax
        };
    }
}
