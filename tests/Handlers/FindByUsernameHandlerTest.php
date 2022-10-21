<?php

namespace Test\Handlers;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';



use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\UserNotFoundException;
use App\Handlers\UserSearchHandler;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use App\User\User;
use Dotenv\Dotenv;
use Exception;
use Faker\Container\Container;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FindByUsernameHandlerTest extends TestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        private ?UserRepositoryInterface $userRepository = null,
        private ?UserSearchHandler $userSearchHandler = null
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

        $this->userRepository ??= new UserRepository($connector);
        $this->userSearchHandler = $this->userSearchHandler ?? new UserSearchHandler($this->userRepository, $logger);

        $this->userSearchHandler->handle($request);

        parent::__construct($name, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: email"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['email' => 'fadeev123123@example.ru'], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"User with email:fadeev123123@example.ru not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['email' => '1@test.ru'], []);
        $response = $this->userSearchHandler->handle($request);

        /** @var SuccessResponse $response */
        $this->assertInstanceOf(SuccessResponse::class,$response);
        $this->expectOutputString(
            '{"success":true,"data":{"email":"1@test.ru","name":"Harly Queen"}}');

        echo json_encode($response);
    }

    private function usersRepository(array $users): UserRepositoryInterface
    {
        return new class($users) implements UserRepositoryInterface {
            public function __construct(
                private array $users
            ) {
            }

            public function save(User $user): void
            {
            }

            public function get(int $id): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function findUserByEmail(string $email): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $email === $user->getEmail()) {
                        return $user;
                    }
                }

                throw new UserNotFoundException("Not found");
            }
        };
    }
}