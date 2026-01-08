<?php

namespace App\Exceptions;

use Exception;

class EmployeeCantBeTerminated extends Exception
{
    protected $code = 406;

    protected $message = 'Only employees with status Hired can be terminated';
}
