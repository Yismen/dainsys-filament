<?php

namespace App\Exceptions;

use Exception;

class InvalidDowntimeCampaign extends Exception
{
    protected $code = 406;

    protected $message = 'Only campaigns with revenue type of downtime are allowed';
}
