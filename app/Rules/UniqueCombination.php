<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Database\Eloquent\Model;

class UniqueCombination implements ValidationRule, DataAwareRule
{
    public function __construct(
        protected Model|string $model,
        protected array $fields,
        protected int|string|null $exceptId = null,
        protected ?array $data = null,
    ) {
    }

    /** @var array<string, mixed> */
    protected array $validatorData = [];

    public function setData(array $data): static
    {
        $this->validatorData = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = is_string($this->model) ? new $this->model : $this->model;
        $conditions = [];

        // Support nested attributes like 'data.employee_id' (Livewire context)
        $fieldName = str_contains($attribute, '.')
            ? substr($attribute, strrpos($attribute, '.') + 1)
            : $attribute;

        $parentPath = str_contains($attribute, '.')
            ? substr($attribute, 0, strrpos($attribute, '.'))
            : null;

        foreach ($this->fields as $field) {
            $fieldValue = $field === $fieldName
                ? $value
                : $this->resolveFieldValue($field, $parentPath);

            if ($fieldValue === null || $fieldValue === '') {
                return;
            }

            $conditions[$field] = $fieldValue;
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

    protected function resolveFieldValue(string $field, ?string $parentPath = null): mixed
    {
        if ($this->data !== null && array_key_exists($field, $this->data)) {
            return $this->data[$field];
        }

        if ($parentPath !== null) {
            $nestedValue = data_get($this->validatorData, $parentPath.'.'.$field);

            if ($nestedValue !== null && $nestedValue !== '') {
                return $nestedValue;
            }
        }

        if (array_key_exists($field, $this->validatorData)) {
            return $this->validatorData[$field];
        }

        return request()->input($field, request()->input(str_replace('_id', '', $field)));
    }
}
