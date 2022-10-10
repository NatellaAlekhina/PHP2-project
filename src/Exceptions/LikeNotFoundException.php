<?php

namespace App\Exceptions;

use Exception;

class LikeNotFoundException extends Exception
{
    protected $message = 'Like not found!'.PHP_EOL;
}