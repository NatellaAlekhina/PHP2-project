<?php

namespace Test\Handlers;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

use App\Blog\Comment;
use App\Connection\ConnectorInterface;
use App\Exceptions\CommentNotFoundException;
use App\Handlers\CommentSearchHandler;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Request\Request;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use PDO;

class FindByCommentIdTest extends TestCase
{public function __construct(
    ?int $id = null,
    array $data = [],
    $dataName = '',
    private ?CommentRepositoryInterface $commentRepository = null,
    private ?CommentSearchHandler $commentSearchHandler = null
)
{
    $pathToDotenv = dirname(__DIR__,1);
    Dotenv::createImmutable($pathToDotenv)->safeLoad();

    $request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);

    $connector = new class() implements ConnectorInterface {
        public static function getConnector(): PDO
        {
            return new PDO(databaseConfig()['sqlite']['DATABASE_URL']);
        }
    };
    $logger = new class() implements  LoggerInterface
    {

        public function emergency(\Stringable|string $message, array $context = []): void
        {
        }

        public function alert(\Stringable|string $message, array $context = []): void
        {
        }

        public function critical(\Stringable|string $message, array $context = []): void
        {
        }

        public function error(\Stringable|string $message, array $context = []): void
        {
        }

        public function warning(\Stringable|string $message, array $context = []): void
        {
        }

        public function notice(\Stringable|string $message, array $context = []): void
        {
        }

        public function info(\Stringable|string $message, array $context = []): void
        {
        }

        public function debug(\Stringable|string $message, array $context = []): void
        {
        }

        public function log($level, \Stringable|string $message, array $context = []): void
        {
        }
    };

    $this->commentRepository ??= new CommentRepository($connector);
    $this->commentSearchHandler = $this->commentSearchHandler ?? new CommentSearchHandler($this->commentRepository, $logger);
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