<?php

namespace App\Services\HC;

class ByKids extends AbstractEmployeesService
{
    protected function field(): string
    {
        return 'kids';
    }
}
