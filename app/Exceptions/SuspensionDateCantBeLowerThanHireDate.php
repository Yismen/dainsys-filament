<?php

namespace App\Exceptions;

use Exception;

class SuspensionDateCantBeLowerThanHireDate extends Exception
{
    protected $code = 406;

    protected $message = 'Suspending an employee before it is hired is not allowed';
}
