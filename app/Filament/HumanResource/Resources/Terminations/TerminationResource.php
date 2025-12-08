<?php

namespace App\Filament\HumanResource\Resources\Terminations;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Termination;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Forms\TerminationTypeSchema;
use App\Filament\Support\Forms\TerminationReasonSchema;
use App\Filament\HumanResource\Resources\Terminations\Pages\ManageTerminations;

class TerminationResource extends Resource
{
    protected static ?string $model = Termination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'full_name')
                    ->autofocus()
                    ->searchable()
                    ->required(),
                Select::make('termination_type_id')
                    ->relationship('terminationType', 'name')
                    ->createOptionForm(TerminationTypeSchema::toArray())
                    ->createOptionModalHeading('Create Termination Type')
                    ->required(),
                Select::make('termination_reason_id')
                    ->relationship('terminationReason', 'name')
                    ->createOptionForm(TerminationReasonSchema::toArray())
                    ->createOptionModalHeading('Create Termination Reason')
                    ->required(),
                DatePicker::make('date')
                    ->native(false)
                    ->default(now())
                    ->required(),
                Toggle::make('rehireable')
                    ->default(true)
                    ->required(),
                Textarea::make('comments')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('employee.id')
                    ->label('Employee'),
                TextEntry::make('terminationType.name')
                    ->label('Termination type'),
                TextEntry::make('terminationReason.name')
                    ->label('Termination reason'),
                IconEntry::make('rehireable')
                    ->boolean(),
                TextEntry::make('comments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Termination $record): bool => $record->trashed()),
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
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.id')
                    ->searchable(),
                TextColumn::make('terminationType.name')
                    ->searchable(),
                TextColumn::make('terminationReason.name')
                    ->searchable(),
                IconColumn::make('rehireable')
                    ->boolean(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTerminations::route('/'),
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
