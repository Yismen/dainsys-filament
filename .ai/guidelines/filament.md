## Filament

- Use `filters` instead of `tableFilters` to define table filters.
- Avoid using the `->preload()` method. Instead use `->options()` and pass `ModelListService` with a reference to the related model.
