<?php

namespace App\Autentification;

use App\Request\Request;
use App\User\User;

interface IdentificationInterface
{
    public function user(Request $request): User;
}