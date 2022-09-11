<?php

namespace App\Repositories;

use App\Blog\Article;
use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\UserNotFoundException;
use PDO;
use App\Repositories\UserRepository;

class ArticleRepository implements ArticleRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqliteConnector();
        $this->connection = $this->connector->getConnector();
    }

    public function save(Article $article): void
    {
        $arrayIdUsers = new UserRepository();
        $arrayIdUsers = $arrayIdUsers->getAllUsersId();

        if(in_array($article->getUserId(), $arrayIdUsers)) { //проверка существует ли id user для article

        $statement = $this->connection->prepare(
            '
                insert into article (user_id, heading, text) 
                values (:user_id, :heading, :text)
                '
        );

        $statement->execute(
            [
                ':user_id'=>$article->getUserId(),
                ':heading'=>$article->getHeading(),
                ':text'=>$article->getText(),
            ]
        );
        } else {
            throw new UserNotFoundException("User with id:{$article->getUserId()} not found, 
    can't save the article!");
        };
    }

    public function get(int $id): Article
    {
        $statement = $this->connection->prepare(
            "select * from article where id = :articleId"
        );

        $statement->execute([
            'articleId' => $id
        ]);

        $articleObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$articleObj) {
            throw new ArticleNotFoundException("Article with id:$id not found".PHP_EOL);
        }

        $article = new Article($articleObj->user_id, $articleObj->heading, $articleObj->text);

        $article
            ->setId($articleObj->id)
            ->setUserId($articleObj->user_id)
            ->setHeading($articleObj->heading)
            ->setText($articleObj->text);

        return $article;

    }

    public function getAllArticleId(): array
    {
        $statement = $this->connection->query('SELECT * FROM article');

        while($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $arrayIdArticles[] = $row->id;
            //var_dump($arrayIdArticles);
        }
        return $arrayIdArticles;
    }

    }