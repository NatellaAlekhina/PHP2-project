<?php

namespace App\Handlers;

use App\Argument\Argument;
use App\Autentification\IdentificationInterface;
use App\Commands\CreateUserCommandInterface;
use App\Date\DateTime;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use APP\Response\AbstractResponse;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Exception;
use Psr\Log\LoggerInterface;

class UserCreateHandler implements UserCreateHandlerInterface
{
    public function __construct(
        private CreateUserCommandInterface $createUserCommand,
        private IdentificationInterface $identification,
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        $email = $request->jsonBodyField('email');

        try {
            $argument = new Argument([
                'id' => $request->jsonBodyField('id'),
                'email' => $request->jsonBodyField('email'),
            'name' => $request->jsonBodyField('name'),
            'surname' => $request->jsonBodyField('surname'),
                //'author_id' => $this->identification->user($request)
                'author_id' => $request->jsonBodyField('author_id')
            ]);
        $this->createUserCommand->handle($argument);

        } catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage().new DateTime() );
            return new ErrorResponse($exception->getMessage());
        }

        try {

            $user = $this->userRepository->findUserByEmail($email);

        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('User created: ' . $user->getId() );

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getName() . ' ' . $user->getSurname()
            ]
        );

    }
}