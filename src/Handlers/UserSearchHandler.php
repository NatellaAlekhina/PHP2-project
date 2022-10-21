<?php

namespace App\Handlers;


use App\Date\DateTime;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use APP\Response\AbstractResponse;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Exception;
use Psr\Log\LoggerInterface;

class UserSearchHandler implements UserSearchHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    private LoggerInterface $logger
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        $this->logger->debug('Search user search '.new DateTime() );

        try {
            $email = $request->query('email');
        } catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage().new DateTime() );
            return new ErrorResponse($exception->getMessage());
        }

        try {

            $user = $this->userRepository->findUserByEmail($email);

        } catch (UserNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('User found: ' . $user->getId());
        $this->logger->debug('Finish user search '.new DateTime() );

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getName() . ' ' . $user->getSurname()
            ]
        );

    }
}