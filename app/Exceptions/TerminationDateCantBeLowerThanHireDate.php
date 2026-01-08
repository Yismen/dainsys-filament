<?php

namespace App\Exceptions;

use Exception;

class TerminationDateCantBeLowerThanHireDate extends Exception
{
    protected $code = 406;

    protected $message = 'Terminating an employee before it is hired is not allowed';
}
