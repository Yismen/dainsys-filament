<?php

namespace App\Services\HC;

class ByGender extends AbstractEmployeesService
{
    protected function field(): string
    {
        return 'gender';
    }
}
