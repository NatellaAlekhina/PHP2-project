<?php

namespace Test\Handlers;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

use App\Blog\Comment;
use App\Exceptions\CommentNotFoundException;
use App\Handlers\CommentSearchHandler;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Request\Request;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use PHPUnit\Framework\TestCase;

class FindByCommentIdTest extends TestCase
{public function __construct(
    ?int $id = null,
    array $data = [],
    $dataName = '',
    private ?CommentRepositoryInterface $commentRepository = null,
    private ?CommentSearchHandler $commentSearchHandler = null
)
{
    $this->commentRepository ??= new CommentRepository();
    $this->commentSearchHandler = $this->commentSearchHandler ?? new CommentSearchHandler($this->commentRepository);
    parent::__construct($id, $data, $dataName);
}

    public function testItReturnsErrorResponseIfNoCommentIdProvided(): void
    {
        $request = new Request([], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: id"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfCommentNotFound(): void
    {
        $request = new Request(['id' => 3], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Comment with id:3 not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['id' => 2], []);
        $response = $this->commentSearchHandler->handle($request);

        /** @var SuccessResponse $response */
        $this->assertInstanceOf(SuccessResponse::class,$response);
        $this->expectOutputString(
            '{"success":true,"data":{"id":2,"comment":"what a nice article, is it true?"}}');

        echo json_encode($response);
    }

    private function commentsRepository(array $comments): CommentRepositoryInterface
    {
        return new class($comments) implements CommentRepositoryInterface {
            public function __construct(
                private array $comments
            ) {
            }

            public function save(Comment $comment): void
            {
            }

            public function get(int $id): Comment
            {
                throw new CommentNotFoundException("Not found");
            }

            public function getById(int $id): Comment
            {
                foreach ($this->comments as $comment) {
                    if ($comment instanceof Comment && $id === $comment->getId()) {
                        return $comment;
                    }
                }

                throw new CommentNotFoundException("Not found");
            }
        };
    }

}