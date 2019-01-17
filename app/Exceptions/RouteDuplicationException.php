<?php

namespace App\Exceptions;

class RouteDuplicationException extends \Exception
{
    protected $message = 'ERROR! Route Slug already registered';
}
