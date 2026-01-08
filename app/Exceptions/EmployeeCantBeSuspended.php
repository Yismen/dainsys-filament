<?php

namespace App\Exceptions;

use Exception;

class EmployeeCantBeSuspended extends Exception
{
    protected $code = 406;

    protected $message = 'Only employees with status Hired can be suspended';
}
