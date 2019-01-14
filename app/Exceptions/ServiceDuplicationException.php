<?php

namespace App\Exceptions;

class ServiceDuplicationException extends \Exception
{
    protected $message = 'ERROR! Service already registered';
}
