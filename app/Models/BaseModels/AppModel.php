<?php

namespace App\Models\BaseModels;

use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class AppModel extends Model
{
    use HasFactory;
    use HasUuids;
    use InteractsWithModelCaching;
    use SoftDeletes;

    protected $primaryKey = 'id'; // or 'uuid' if you use that
public $incrementing = false;
protected $keyType = 'string';
public function getRouteKeyName(): string { return 'id'; }


    /**
     * Resolve the route binding (bypass Str::isUuid checks).
     *
     * Called by Laravel when implicit Route Model Binding runs.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field = $field ?? $this->getRouteKeyName();

        return $this->where($field, $value)->first();
    }

    /**
     * Ensure route binding queries use the raw field as-is.
     *
     * Filament may call this variant.
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();

        return $query->where($field, $value);
    }
}
