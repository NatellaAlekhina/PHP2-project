<?php

namespace Test\Blog;

use App\Blog\Comment;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\CommentRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

class CommentTest extends TestCase
{
    public function testItThrowExceptionWhenUserOrArticleNotFoundForComment(): void
    {
        //Podgotovka
        $comment = new Comment(100,100, 'some_text');
        $commentRepository = new CommentRepository();

        //Type ogidaemogo iskluycheniya
        $this->expectException(UserNotFoundException::class);

        //Sobitie
        $this->expectExceptionMessage("User with id:100 or 100 not found,
                 can't save the comment!");
        //deistvie k polucheniu oshibki
        $commentRepository->save($comment);
    }

    public function testGetCommentByIdException(): void
    {
        $commentId = 200;

        $commentRepository = new CommentRepository();

        $this->expectException(ArticleNotFoundException::class);

        $this->expectExceptionMessage("Comment with id:$commentId not found".PHP_EOL);

        $commentRepository->get($commentId);
    }

    public function testGetCommentByIdObject(): void
    {
        $commentRepository = new CommentRepository();
        $comment = new Comment(2, 2, 'find_me_test_comment');
        $commentRepository->save($comment);
        $comment->setId(9);
        $value = $commentRepository->get(9);

        $this->assertEquals($comment, $value);

    }
}