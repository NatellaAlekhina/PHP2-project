<?php

namespace App\Handlers;

use App\Request\Request;
use APP\Response\AbstractResponse;

interface CommentSearchHandlerInterface
{
    public function handle(Request $request): AbstractResponse;
}