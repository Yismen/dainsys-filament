<?php

namespace App\Services\HC;

class ByMarriage extends AbstractEmployeesService
{
    protected function field(): string
    {
        return 'marriage';
    }
}
