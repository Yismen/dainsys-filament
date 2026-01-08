<?php

namespace App\Exceptions;

use Exception;

class EmployeeCantBeHired extends Exception
{
    protected $code = 406;

    protected $message = 'Only employees with status Created or Terminated with the condition of rehireable can be Hired';
}
