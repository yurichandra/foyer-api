<?php

namespace App\Exceptions;

class ServiceCreationException extends \Exception
{
    protected $message = 'ERROR! Failed to register service';
}
