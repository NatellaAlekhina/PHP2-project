<?php


namespace Test\Handlers;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

use App\Blog\Article;
use App\Connection\ConnectorInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Handlers\ArticleSearchHandler;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Request\Request;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use PDO;

class FindByArticleIdTest extends TestCase
{
    public function __construct(
        ?int $id = null,
        array $data = [],
        $dataName = '',
        private ?ArticleRepositoryInterface $articleRepository = null,
        private ?ArticleSearchHandler $articleSearchHandler = null
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

        $this->articleRepository ??= new ArticleRepository($connector);
        $this->articleSearchHandler = $this->articleSearchHandler ?? new ArticleSearchHandler($this->articleRepository, $logger);
        parent::__construct($id, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoArticleIdProvided(): void
    {
        $request = new Request([], []);
        $response = $this->articleSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: id"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfArticleNotFound(): void
    {
        $request = new Request(['id' => 1], []);
        $response = $this->articleSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Article with id:1 not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['id' => 2], []);
        $response = $this->articleSearchHandler->handle($request);

        /** @var SuccessResponse $response */
        $this->assertInstanceOf(SuccessResponse::class,$response);
        $this->expectOutputString(
            '{"success":true,"data":{"id":2,"article":"hello world hi every one from this chat"}}');

        echo json_encode($response);
    }

    private function articlesRepository(array $articles): ArticleRepositoryInterface
    {
        return new class($articles) implements ArticleRepositoryInterface {
            public function __construct(
                private array $articles
            ) {
            }

            public function save(Article $article): void
            {
            }

            public function get(int $id): Article
            {
                throw new ArticleNotFoundException("Not found");
            }

            public function getById(int $id): Article
            {
                foreach ($this->articles as $article) {
                    if ($article instanceof Article && $id === $article->getId()) {
                        return $article;
                    }
                }

                throw new ArticleNotFoundException("Not found");
            }
        };
    }

}

