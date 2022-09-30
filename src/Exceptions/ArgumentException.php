<?php

namespace App\Exceptions;

use RuntimeException;

class ArgumentException extends RuntimeException
{
    protected $message = 'No such argument!'.PHP_EOL;
}