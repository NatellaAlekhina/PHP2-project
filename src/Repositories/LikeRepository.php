<?php

namespace App\Repositories;

use App\Blog\Like;
use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Exceptions\LikeNotFoundException;
use PDO;

class LikeRepository implements LikeRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ConnectorInterface $connector)
    {
        //$this->connector = $connector ?? new SqliteConnector();
        $this->connection = $this->connector->getConnector();
    }
/*
    public function save(Like $like): void
    {
        $arrayIdUsers = new UserRepository();
        $arrayIdUsers = $arrayIdUsers->getAllUsersId();
        $arrayIdArticles = new ArticleRepository();
        $arrayIdArticles = $arrayIdArticles->getAllArticleId();


        if (in_array($like->getUserId(), $arrayIdUsers) &&
            in_array($like->getArticleId(), $arrayIdArticles)) {
            //проверка существует ли id user и id article для like

            $statement = $this->connection->prepare(
                '
                insert into like (user_id, article_id)
                values (:user_id, :article_id)
                '
            );

            $statement->execute(
                [
                    ':user_id' => $like->getUserId(),
                    ':article_id' => $like->getArticleId()
                ]
            );
        } else {
            throw new LikeNotFoundException
            ("User with id:{$like->getUserId()} or {$like->getArticleId()} not found,
                 can't save the like!" . PHP_EOL);
        };
    }*/

    public function get(int $id): Like
    {
        $statement = $this->connection->prepare(
            "select * from like where id = :likeId"
        );

        $statement->execute([
            'likeId' => $id
        ]);

        $likeObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$likeObj) {
            throw new LikeNotFoundException("Like with id:$id not found");
        }

        $like = new Like($likeObj->user_id, $likeObj->article_id);

        $like
            ->setId($likeObj->id)
            ->setUserId($likeObj->user_id)
            ->setArticleId($likeObj->article_id);

        return $like;
    }
}


