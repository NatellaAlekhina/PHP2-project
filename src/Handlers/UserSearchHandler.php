<?php

namespace App\Handlers;


use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use APP\Response\AbstractResponse;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Exception;

class UserSearchHandler implements UserSearchHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $email = $request->query('email');
        } catch (Exception $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        try {

            $user = $this->userRepository->findUserByEmail($email);

        } catch (UserNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getName() . ' ' . $user->getSurname()
            ]
        );

    }
}