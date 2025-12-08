<?php

namespace App\Filament\HumanResource\Resources\Employees;

use BackedEnum;
use App\Enums\Gender;
use App\Models\Employee;
use Filament\Tables\Table;
use App\Enums\MaritalStatus;
use Filament\Schemas\Schema;
use App\Enums\EmployeeStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Group;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Support\Forms\EmployeeSchema;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Resources\Employees\Pages\ManageEmployees;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(EmployeeSchema::toArray());
            // ->components([
            //     TextInput::make('first_name')
            //         ->required(),
            //     TextInput::make('second_first_name'),
            //     TextInput::make('last_name')
            //         ->required(),
            //     TextInput::make('second_last_name'),
            //     TextInput::make('personal_id')
            //         ->required(),
            //     DateTimePicker::make('hired_at')
            //         ->required(),
            //     DatePicker::make('date_of_birth')
            //         ->required(),
            //     TextInput::make('cellphone')
            //         ->tel()
            //         ->required(),
            //     Select::make('status')
            //         ->options(EmployeeStatus::class)
            //         ->default('Current')
            //         ->required(),
            //     Select::make('marriage')
            //         ->options(MaritalStatus::class)
            //         ->default('Single')
            //         ->required(),
            //     Select::make('gender')
            //         ->options(Gender::class)
            //         ->default('Male')
            //         ->required(),
            //     Toggle::make('kids')
            //         ->required(),
            //     TextInput::make('punch')
            //         ->required(),
            //     Select::make('site_id')
            //         ->relationship('site', 'name')
            //         ->required(),
            //     Select::make('project_id')
            //         ->relationship('project', 'name')
            //         ->required(),
            //     Select::make('position_id')
            //         ->relationship('position', 'name')
            //         ->required(),
            //     Select::make('citizenship_id')
            //         ->relationship('citizenship', 'name')
            //         ->required(),
            //     Select::make('supervisor_id')
            //         ->relationship('supervisor', 'name'),
            //     Select::make('afp_id')
            //         ->relationship('afp', 'name'),
            //     Select::make('ars_id')
            //         ->relationship('ars', 'name'),
            // ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('first_name'),
                TextEntry::make('second_first_name')
                    ->placeholder('-'),
                TextEntry::make('last_name'),
                TextEntry::make('second_last_name')
                    ->placeholder('-'),
                TextEntry::make('full_name')
                    ->placeholder('-'),
                TextEntry::make('personal_id'),
                TextEntry::make('hired_at')
                    ->dateTime(),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('cellphone'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('marriage')
                    ->badge(),
                TextEntry::make('gender')
                    ->badge(),
                IconEntry::make('kids')
                    ->boolean(),
                TextEntry::make('punch'),
                TextEntry::make('site.name')
                    ->label('Site'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('position.name')
                    ->label('Position'),
                TextEntry::make('citizenship.name')
                    ->label('Citizenship'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor')
                    ->placeholder('-'),
                TextEntry::make('afp.name')
                    ->label('Afp')
                    ->placeholder('-'),
                TextEntry::make('ars.name')
                    ->label('Ars')
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Employee $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Split::make([
                    ImageColumn::make('information.photo_url')
                        ->grow(false)
                        ->circular(),
                    TextColumn::make('full_name')
                        ->searchable(),
                    TextColumn::make('cellphone')
                        ->icon('heroicon-o-phone')
                        ->searchable(),
                    TextColumn::make('site.name')
                        ->sortable(),
                    TextColumn::make('project.name')
                        ->sortable(),
                    TextColumn::make('position.details')
                        ->sortable(),
                    TextColumn::make('status')
                        ->badge()
                        ->searchable(),
                ]),
                Panel::make([
                    TextColumn::make('personal_id')
                        ->formatStateUsing(fn ($state) => 'Personal ID: ' . $state)
                        ->searchable(),
                    TextColumn::make('hired_at')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Hired At: ' . $state)
                        ->sortable(),
                    TextColumn::make('date_of_birth')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Birthday: ' . $state->format('M-d'))
                        ->sortable(),
                    TextColumn::make('citizenship.name')
                        ->formatStateUsing(fn ($state) => 'Nationality: ' . $state)
                        ->sortable(),
                    TextColumn::make('supervisor.name')
                        ->formatStateUsing(fn ($state) => 'Supervisor: ' . $state)
                        ->sortable(),
                    TextColumn::make('afp.name')
                        ->formatStateUsing(fn ($state) => 'AFP: ' . $state)
                        ->sortable(),
                    TextColumn::make('ars.name')
                        ->formatStateUsing(fn ($state) => 'ARS: ' . $state)
                        ->sortable(),
                    TextColumn::make('marriage')
                        ->formatStateUsing(fn ($state) => 'Marital Status: ' . $state->value)
                        ->searchable(),
                    TextColumn::make('gender')
                        ->formatStateUsing(fn ($state) => 'Gender: ' . $state->value)
                        ->searchable(),
                    IconColumn::make('kids')
                        // ->formatStateUsing(fn ($state) => 'Has Kids: ' . $state)
                        ->boolean(),
                    TextColumn::make('punch')
                        ->formatStateUsing(fn ($state) => 'Punch: ' . $state)
                        ->searchable(),
                    TextColumn::make('created_at')
                        ->formatStateUsing(fn ($state) => 'Created Ar: ' . $state)
                        ->dateTime()
                        ->sortable(),
                    TextColumn::make('updated_at')
                        ->formatStateUsing(fn ($state) => 'Last Update: ' . $state)
                        ->dateTime()
                        ->sortable(),
                ])->collapsible()
                    ->collapsed(true)
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    // DeleteAction::make(),
                    // ForceDeleteAction::make(),
                    // RestoreAction::make(),
                ])
                ->icon(Heroicon::AdjustmentsHorizontal)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEmployees::route('/'),
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
