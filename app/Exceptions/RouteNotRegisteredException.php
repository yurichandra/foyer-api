<?php

namespace App\Exceptions;

class RouteNotRegisteredException extends \Exception
{
    protected $message = 'route_has_not_registered';
}
