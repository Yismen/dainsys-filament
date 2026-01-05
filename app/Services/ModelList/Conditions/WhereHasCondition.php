<?php

namespace App\Services\ModelList\Conditions;

class WhereHasCondition
{
    public function __construct(
        public string $field,
        public $value,
        public string $operator = '=',
    ) {
        //
    }
}
