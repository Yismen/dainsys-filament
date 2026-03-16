<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class UniqueCombination implements ValidationRule
{
    public function __construct(
        protected Model|string $model,
        protected array $fields,
        protected ?int $exceptId = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = is_string($this->model) ? new $this->model : $this->model;
        $table = $model->getTable();

        $conditions = [];

        foreach ($this->fields as $field) {
            if ($field === $attribute) {
                $conditions[$field] = $value;
            } else {
                $fieldValue = request()->input(str_replace('_id', '', $field));
                if ($fieldValue) {
                    $conditions[$field] = $fieldValue;
                }
            }
        }

        if (empty($conditions)) {
            return;
        }

        $query = $model->newQuery()->where($conditions);

        if ($this->exceptId) {
            $query->where('id', '!=', $this->exceptId);
        }

        if ($query->exists()) {
            $fieldLabels = array_map(fn ($f) => str_replace('_id', '', str_replace('_', ' ', $f)), $this->fields);
            $fail("The selected {$attribute} already has a record with the given " . implode(', ', $fieldLabels) . ".");
        }
    }
}