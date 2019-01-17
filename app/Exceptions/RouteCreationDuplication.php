<?php

namespace App\Exceptions;

class RouteCreationDuplication extends \Exception
{
    protected $message = 'ERROR! Failed to register route';
}
