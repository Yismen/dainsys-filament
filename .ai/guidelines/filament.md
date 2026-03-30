## Filament

- Always use filament v5 to avoid deprecations
- Use `filters` instead of `tableFilters` to define table filters.
- Avoid using the `->preload()` method. Instead use `->options()` and pass `ModelListService` with a reference to the related model.
- Always add a ->label() to form, table, infolist and action. make them translatable using convention `->label(__(''))`

## Livewire

- method `assertTableActionVisible` is deprecated
- method `assertTableActionHidden` is deprecated

## Testing

- When creating a new feature, all make a pest test or update the existing one.
- When testing filament resources or routed controllers, create tests for authentication, authorization, fields validation. Make sure we test all CRUD operations, including soft deletes if applicable.
