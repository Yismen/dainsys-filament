## Filament

- Use `filters` instead of `tableFilters` to define table filters.
- Avoid using the `->preload()` method. Instead use `->options()` and pass `ModelListService` with a reference to the related model.
- In the forms, Filament 4 prefer `schema` instead of `form`

## Livewire

- method `assertTableActionVisible` is deprecated
- method `assertTableActionHidden` is deprecated
