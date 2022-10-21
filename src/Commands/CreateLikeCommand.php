<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Exceptions\CommandException;
use App\Exceptions\LikeNotFoundException;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use PDO;

class CreateLikeCommand implements CreateUserCommandInterface
{
    private PDO $connection;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ?ConnectorInterface $connector
    )
    {
        $this->connection = $this->connector->getConnector();
    }

    /**
     * @throws CommandException
     */
    public function handle(Argument $argument): void
    {
        $articleId = $argument->get('article_id');
        $userId = $argument->get('user_id');

        $arrayIdUsers = new UserRepository();
        $arrayIdUsers = $arrayIdUsers->getAllUsersId();
        $arrayIdArticles = new ArticleRepository();
        $arrayIdArticles = $arrayIdArticles->getAllArticleId();


        if (in_array($userId, $arrayIdUsers) &&
            in_array($articleId, $arrayIdArticles)) {
            //проверка существует ли id user и id article для like

            $statement = $this->connection->prepare(
                '
                insert into like (user_id, article_id) 
                values (:user_id, :article_id)
                '
            );

            $statement->execute(
                [
                    ':user_id' => $userId,
                    ':article_id' => $articleId
                ]
            );
        } else {
            throw new LikeNotFoundException
            ("User with id:{$userId} or {$articleId} not found,
                 can't save the like!" . PHP_EOL);
        };
    }
}
