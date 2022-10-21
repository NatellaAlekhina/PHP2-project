<?php

namespace App\Handlers;

use App\Request\Request;
use APP\Response\AbstractResponse;

interface UserSearchHandlerInterface
{
    public function handle(Request $request): AbstractResponse;
}