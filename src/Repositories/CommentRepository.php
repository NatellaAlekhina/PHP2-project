<?php

namespace App\Repositories;

use App\Blog\Article;
use App\Blog\Comment;
use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\CommentNotFoundException;
use App\Exceptions\UserNotFoundException;
use PDO;
use App\Repositories\UserRepository;
use App\Repositories\ArticleRepository;

class CommentRepository implements CommentRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ConnectorInterface $connector)
    {
        //$this->connector = $connector ?? new SqliteConnector();
        $this->connection = $this->connector->getConnector();
    }

    public function save(Comment $comment): void
    {
        $arrayIdUsers = new UserRepository();
        $arrayIdUsers = $arrayIdUsers->getAllUsersId();
        $arrayIdArticles = new ArticleRepository();
        $arrayIdArticles = $arrayIdArticles->getAllArticleId();


        if(in_array($comment->getUserId(), $arrayIdUsers) &&
            in_array($comment->getArticleId(), $arrayIdArticles)) {
            //проверка существует ли id user и id article для comment

            $statement = $this->connection->prepare(
                '
                insert into comment (user_id, article_id, text) 
                values (:user_id, :article_id, :text)
                '
            );

            $statement->execute(
                [
                    ':user_id' => $comment->getUserId(),
                    ':article_id' => $comment->getArticleId(),
                    ':text' => $comment->getText(),
                ]
            );
        } else {
                throw new UserNotFoundException
                ("User with id:{$comment->getUserId()} or {$comment->getArticleId()} not found,
                 can't save the comment!" .PHP_EOL);
};
    }

    public function get(int $id): Comment
    {
        $statement = $this->connection->prepare(
            "select * from comment where id = :commentId"
        );

        $statement->execute([
            'commentId' => $id
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$commentObj) {
            throw new CommentNotFoundException("Comment with id:$id not found");
        }

        $comment = new Comment($commentObj->user_id, $commentObj->article_id, $commentObj->text);

        $comment
            ->setId($commentObj->id)
            ->setUserId($commentObj->user_id)
            ->setArticleId($commentObj->article_id)
            ->setText($commentObj->text);

        return $comment;
    }

}