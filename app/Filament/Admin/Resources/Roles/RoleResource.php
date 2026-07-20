<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles;

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Roles\Pages\ViewRole;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use BezhanSalleh\PluginEssentials\Concerns\Resource as Essentials;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;

class RoleResource extends Resource
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasGlobalSearch;
    use Essentials\HasLabels;
    use Essentials\HasNavigation;
    use HasShieldFormComponents;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->visible(fn (Get $get): bool => ! filled($get('permission_panel')) && $get('permission_assignment') === 'all')
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->unique(
                                        ignoreRecord: true, /** @phpstan-ignore-next-line */
                                        modifyRuleUsing: fn (Unique $rule): Unique => Utils::isTenancyEnabled() ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id) : $rule
                                    )
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),

                                Select::make(config('permission.column_names.team_foreign_key'))
                                    ->label(__('filament-shield::filament-shield.field.team'))
                                    ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                                    /** @phpstan-ignore-next-line */
                                    ->default(Filament::getTenant()?->id)
                                    ->options(fn (): array => in_array(Utils::getTenantModel(), [null, '', '0'], true) ? [] : Utils::getTenantModel()::pluck('name', 'id')->toArray())
                                    ->visible(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled())
                                    ->dehydrated(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),
                                static::getSelectAllFormComponent(),

                            ])
                            ->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Grid::make()
                    ->schema([
                        Select::make('permission_panel')
                            ->label(__('filament.panel'))
                            ->options(static::getPermissionPanelOptions())
                            ->searchable()
                            ->placeholder(__('filament.all_panels'))
                            ->live()
                            ->reactive()
                            ->dehydrated(false),

                        Select::make('permission_assignment')
                            ->label(__('filament.permission_assignment'))
                            ->options([
                                'all' => __('filament.all_permissions'),
                                'assigned' => __('filament.assigned'),
                                'unassigned' => __('filament.unassigned'),
                            ])
                            ->default('all')
                            ->live()
                            ->reactive()
                            ->dehydrated(false),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 2,
                    ])
                    ->columnSpanFull(),
                static::getShieldFormComponents(),
            ]);
    }

    public static function getTabFormComponentForResources(): Component
    {
        return static::shield()->hasSimpleResourcePermissionView()
            ? static::getTabFormComponentForSimpleResourcePermissionsView()
            : Tab::make('resources')
                ->label(__('filament-shield::filament-shield.resources'))
                ->visible(fn (): bool => Utils::isResourceTabEnabled())
                ->badge(static::getResourceTabBadgeCount())
                ->schema([
                    Grid::make()
                        ->schema(static::getResourceEntitiesSchema())
                        ->columns(static::shield()->getGridColumns()),
                ]);
    }

    public static function getCheckboxListFormComponent(string $name, array $options, bool $searchable = true, array|int|string|null $columns = null, array|int|string|null $columnSpan = null): Component
    {
        return CheckboxList::make($name)
            ->label(fn () => str($name)->after('App\Filament\\'))
            ->options(fn (Get $get): array => static::filterCheckboxListOptions(
                $options,
                $get('permission_panel'),
                $get('permission_assignment'),
                $get($name)
            ))
            ->searchable($searchable)
            ->afterStateHydrated(function (Component $component, string $operation, ?Model $record, Set $set) use ($options): void {
                static::setPermissionStateForRecordPermissions(
                    component: $component,
                    operation: $operation,
                    permissions: $options,
                    record: $record
                );

                static::toggleSelectAllViaEntities($component->getLivewire(), $set);
            })
            ->afterStateUpdated(function (Livewire $livewire, Set $set): void {
                static::toggleSelectAllViaEntities($livewire, $set);
            })
            ->selectAllAction(fn (
                Action $action,
                Component $component,
                Livewire $livewire,
                Set $set
            ) => static::bulkToggleableAction(
                action: $action,
                component: $component,
                livewire: $livewire,
                set: $set
            ))
            ->deselectAllAction(fn (
                Action $action,
                Component $component,
                Livewire $livewire,
                Set $set
            ) => static::bulkToggleableAction(
                action: $action,
                component: $component,
                livewire: $livewire,
                set: $set,
                resetState: true
            ))
            ->dehydrated(fn ($state): bool => ! blank($state))
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns($columns ?? static::shield()->getCheckboxListColumns())
            ->columnSpan($columnSpan ?? static::shield()->getCheckboxListColumnSpan());
    }

    protected static function filterCheckboxListOptions(array $options, ?string $panel, ?string $assignment, string|array|null $state): array
    {
        $panel = filled($panel) ? $panel : null;
        $assignment = filled($assignment) ? $assignment : 'all';

        $options = $panel ? static::filterPermissionOptionsByPanel($options, $panel) : $options;

        if ($assignment === 'assigned') {
            return static::filterPermissionOptionsByAssignment($options, static::normalizeCheckboxState($state), true);
        }

        if ($assignment === 'unassigned') {
            return static::filterPermissionOptionsByAssignment($options, static::normalizeCheckboxState($state), false);
        }

        return $options;
    }

    protected static function normalizeCheckboxState(string|array|null $state): array
    {
        if (is_string($state)) {
            return [$state];
        }

        return array_filter((array) $state);
    }

    protected static function filterPermissionOptionsByAssignment(array $options, array $selectedPermissionKeys, bool $assigned): array
    {
        if ($selectedPermissionKeys === []) {
            return $assigned ? [] : $options;
        }

        return array_filter($options, function (mixed $label, string $permissionKey) use ($selectedPermissionKeys, $assigned): bool {
            return in_array($permissionKey, $selectedPermissionKeys, true) === $assigned;
        }, ARRAY_FILTER_USE_BOTH);
    }

    protected static function filterPermissionOptionsByPanel(array $options, string $panel): array
    {
        if (blank($panel)) {
            return $options;
        }

        $permissionKeys = static::getPermissionKeysForPanel($panel);

        return array_filter($options, function (mixed $label, string $permissionKey) use ($permissionKeys): bool {
            return in_array($permissionKey, $permissionKeys, true);
        }, ARRAY_FILTER_USE_BOTH);
    }

    protected static function getPermissionKeysForPanel(string $panel): array
    {
        $panel = Filament::getPanel($panel, false);

        if (! $panel) {
            return [];
        }

        $resourcePermissionKeys = collect($panel->getResources())
            ->flatMap(fn (string $resource): array => array_keys(FilamentShield::getResourcePermissionsWithLabels($resource) ?? []))
            ->toArray();

        $pagePermissionKeys = collect($panel->getPages())
            ->flatMap(fn (string $page): array => array_keys(FilamentShield::getDefaultPermissionKeys($page, Utils::getConfig()->pages->prefix)))
            ->toArray();

        $widgetPermissionKeys = collect($panel->getWidgets())
            ->flatMap(fn (string|WidgetConfiguration $widget): array => array_keys(FilamentShield::getDefaultPermissionKeys($widget, Utils::getConfig()->widgets->prefix)))
            ->toArray();

        return collect($resourcePermissionKeys)
            ->merge($pagePermissionKeys)
            ->merge($widgetPermissionKeys)
            ->unique()
            ->values()
            ->toArray();
    }

    public static function getPermissionPanelOptions(): array
    {
        return collect(Filament::getPanels())
            ->mapWithKeys(fn (Panel $panel): array => [$panel->getId() => str($panel->getId())->headline()])
            ->toArray();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::Medium)
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label(__('filament-shield::filament-shield.column.guard_name')),
                TextColumn::make('team.name')
                    ->default('Global')
                    ->badge()
                    ->color(fn (mixed $state): string => str($state)->contains('Global') ? 'gray' : 'primary')
                    ->label(__('filament-shield::filament-shield.column.team'))
                    ->searchable()
                    ->visible(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),
                TextColumn::make('permissions_count')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->color('primary'),
                TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('users.name')
                    ->wrap()
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return Utils::getResourceSlug();
    }

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster();
    }

    public static function getEssentialsPlugin(): ?FilamentShieldPlugin
    {
        return FilamentShieldPlugin::get();
    }
}
