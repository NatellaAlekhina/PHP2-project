<?php

namespace App\Exceptions;

use Exception;

class CommandException extends Exception
{
    protected $message = 'No required argument!'.PHP_EOL;
}