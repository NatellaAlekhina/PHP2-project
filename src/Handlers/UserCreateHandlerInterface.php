<?php

namespace App\Handlers;

use App\Request\Request;
use APP\Response\AbstractResponse;

interface UserCreateHandlerInterface
{
    public function handle(Request $request): AbstractResponse;
}