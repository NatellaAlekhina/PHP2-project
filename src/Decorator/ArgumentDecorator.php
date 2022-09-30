<?php

namespace App\Decorator;

class ArgumentDecorator
{
    public function __construct( public string $argument, public string $value)
    {}
}