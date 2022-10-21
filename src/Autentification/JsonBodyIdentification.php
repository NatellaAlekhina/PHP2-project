<?php

namespace App\Autentification;

use App\Exceptions\AuthException;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use App\User\User;
use HttpException;
use InvalidArgumentException;

class JsonBodyIdentification implements IdentificationInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function user(Request $request): User
    {
        try {
            $userId = $request->jsonBodyField('auth_user');
            return $user = $this->userRepository->get($userId);
        } catch (HttpException|InvalidArgumentException $exception)
        {
            throw new AuthException($exception->getMessage());
        }
    }
}