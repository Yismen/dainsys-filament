<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class UniqueCombination implements DataAwareRule, ValidationRule
{
    public function __construct(
        protected Model|string $model,
        protected array $fields,
        protected int|string|null $exceptId = null,
    ) {}

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
        $query = $model->newQuery();
        $values = \implode(', ', \array_values($this->fields));
        $fieldLabels = \implode(', ', \array_keys($this->fields));

        foreach ($this->fields as $field => $fieldValue) {
            $query->where($field, $fieldValue);
        }

        if ($this->exceptId) {
            $query->where('id', '!=', $this->exceptId);
        }

        if ($query->exists()) {
            $fail("The combination of values for the fields: {$fieldLabels} already exists. Please ensure that the values for the fields: {$fieldLabels} are unique.");
        }
    }
}
