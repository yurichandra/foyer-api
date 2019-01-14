<?php

namespace App\Exceptions;

class ServiceUpdateException extends \Exception
{
    protected $message = 'ERROR! Service failed to update';
}
