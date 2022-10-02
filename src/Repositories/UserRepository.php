<?php

namespace App\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Date\DateTime;
use App\Exceptions\UserNotFoundException;
use App\User\User;
use mysql_xdevapi\Statement;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    //private array $users = [];
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqliteConnector();
        $this->connection = $this->connector->getConnector();
    }

   /* public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            '
                insert into user (name, surname, created_at) 
                values (:name, :surname, :created_at)
                '
        );


        $statement->execute(
            [
                ':name'=>$user->getName(),
                ':surname'=>$user->getSurname(),
                ':created_at'=>$user->getCreatedAt(),
            ]
        );
        //$this->users[] = $user;
    }
*/
    public function get(int $id): User
    {
        $statement = $this->connection->prepare(
            "select * from user where id = :userId"
        );

        $statement->execute([
            'userId' => $id
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with id:$id not found");
        }

        return $this->mapUser($userObj);
/*
        $user = new User($userObj->name, $userObj->surname);

        $user
            ->setId($userObj->id)
            ->setActive($userObj->active)
            ->setCreatedAt(new DateTime($userObj->created_at))
            ->setUpdatedAt(($updatedAt = $userObj->updated_at) ? new DateTime($updatedAt) : null)
            ->setDeletedAt(($deletedAt = $userObj->deleted_at) ? new DateTime($deletedAt) : null);

        return $user;
*/


      //  /** @var User$user */
        /*
        foreach ($this->users as $user)
        {
            if($user->getId() === $id)
            {
                return $user;
            }
        }
        throw new UserNotFoundException();*/
    }

    public function getAllUsersId(): array
    {
        $statement = $this->connection->query('SELECT * FROM user');

        while($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $arrayIdUsers[] = $row->id;
            //var_dump($arrayIdUsers);
        }
        return $arrayIdUsers;
    }

    public function findUserByEmail(string $email): User
    {
        $statement = $this->connection->prepare(
            "select * from user where email = :userEmail"
        );

        $statement->execute([
            'userEmail' => $email
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with email:$email not found");
        }

        return $this->mapUser($userObj);
    }

    public function mapUser(object $userObj)
    {
        $user = new User(
            $userObj->email,
            $userObj->name,
            $userObj->surname
        );

        $user
            ->setId($userObj->id)
            ->setActive($userObj->active)
            ->setCreatedAt(new DateTime($userObj->created_at))
            ->setUpdatedAt(($updatedAt = $userObj->updated_at) ? new DateTime($updatedAt) : null)
            ->setDeletedAt(($deletedAt = $userObj->deleted_at) ? new DateTime($deletedAt) : null);

        return $user;

    }



}