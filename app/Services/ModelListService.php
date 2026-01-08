<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * Service for retrieving lists of models with  and caching.
 *
 * Usage:
 * - Call ModelListService::get() with a model class or query builder, key and value fields.
 * - Returns an associative array of key-value pairs from the model, cached for performance.
 *
 * Example:
 *   ModelListService::get(User::class, 'id', 'name', [['active', '=', 1]]);
 */
class ModelListService
{
    private static self $instance;

    private string|Builder $model;

    private string $key_field;

    private string $value_field;

    public static function get(
        string|Builder $model,
        string $key_field = 'id',
        string $value_field = 'name',
    ): array
    {
        self::$instance ??= new self;
        self::$instance->key_field = $key_field;
        self::$instance->value_field = $value_field;

        self::$instance->model = $model instanceof Builder ? $model : $model::query();

        $query = self::parseQuery();

        return Cache::rememberForever(
            self::getCacheKey($query),
            fn () => $query
                ->pluck(self::$instance->value_field, self::$instance->key_field)
                ->toArray()
        );
    }

    public static function make(
        string|Builder $model,
        string $key_field = 'id',
        string $value_field = 'name',
    ): array
    {
        return self::get(
            $model,
            $key_field,
            $value_field,
        );
    }

    protected static function parseQuery()
    {
        return self::$instance->model
            ->select([self::$instance->value_field, self::$instance->key_field])
            ->orderBy(self::$instance->value_field, 'asc');
    }

    private static function getCacheKey($query): string
    {
        $key = implode('_', [
            'model_list',
            str($query->toRawSql())->snake(),
        ]);

        return $key;
    }
}
