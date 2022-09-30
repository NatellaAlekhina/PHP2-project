<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Date\DateTime;
use App\Exceptions\CommandException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use PDO;

class CreateUserCommand implements CreateUserCommandInterface
{
    private PDO $connection;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ?ConnectorInterface $connector = null
    )
    {
        $this->connector = $connector ?? new SqliteConnector();
        $this->connection = $this->connector->getConnector();
    }

    /**
     * @throws CommandException
     */
    public function handle(Argument $argument): void
    {
        $name = $argument->get('name');
        $surname = $argument->get('surname');
        $email = $argument->get('email');

        if($this->userExist($email))
        {
            throw new CommandException("User already exist: $email".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                insert into user (email, name, surname, created_at) 
                values (:email, :name, :surname, :created_at)
                '
        );


        $statement->execute(
            [
                ':email'=>$email,
                ':name'=>$name,
                ':surname'=>$surname,
                ':created_at'=>new DateTime()
            ]
        );

    }
/*
    private function parseRawInput(array $rawInput): array
    {
        $input = [];

        foreach ($rawInput as $argument)
        {
            $parts = explode('=', $argument);
            if(count($parts) !== 2)
            {
                continue;
            }

            $input[$parts[0]] = $parts[1];
        }

        foreach (['email', 'name', 'surname'] as $argument)
        {
            if(!array_key_exists($argument, $input))
            {
                throw new CommandException(
                    "No required argument provided: $argument".PHP_EOL
                );
            }
        }

        if(empty($input[$argument]))
        {
          throw new CommandException(
              "Empty argument provided: $argument".PHP_EOL
          );
        }

        return $input;

    }*/

    private function userExist(string $email): bool
    {
        try{
            $this->userRepository->findUserByEmail($email);
        }catch(UserNotFoundException $exception)
        {
            return false;
        }
        return true;
    }
}