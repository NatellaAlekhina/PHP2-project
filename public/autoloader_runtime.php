<?php

use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Container\DIContainer;
use App\Handlers\ArticleSearchHandler;
use App\Handlers\ArticleSearchHandlerInterface;
use App\Handlers\CommentSearchHandler;
use App\Handlers\CommentSearchHandlerInterface;
use App\Handlers\UserSearchHandler;
use App\Handlers\UserSearchHandlerInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\LikeRepository;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Request\Request;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../newdatabase/config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$pathToDotenv = dirname(__DIR__,1);
//$pathToDotenv = dirname(__DIR__,1) . '\.env';

Dotenv::createImmutable($pathToDotenv)->safeLoad();

$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);

$container = new DIContainer();

$dsn = dirname(__DIR__, 1) . '\newdatabase\dump\database.sqlite';
//$dsn = 'sqlite:C:\Users\Abond\Desktop\Geekbrains_PHP2\newdatabase\dump\database.sqlite';

$container->bind(ConnectorInterface::class, SqliteConnector::class);
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(UserSearchHandlerInterface::class, UserSearchHandler::class);
$container->bind(ArticleSearchHandlerInterface::class, ArticleSearchHandler::class);
$container->bind(CommentSearchHandlerInterface::class, CommentSearchHandler::class);
$container->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
$container->bind(CommentRepositoryInterface::class, CommentRepository::class);
$container->bind(LikeRepositoryInterface::class, LikeRepository::class);

$logger = new Logger('php2_logger');

$pathLoggerInfo = dirname(__DIR__, 1) . '\var\log\php2.log';

$isNeedLogToFile = $_SERVER['LOG_TO_FILE'] === 'true';
$isNeedLogToConsole = $_SERVER['LOG_TO_CONSOLE'] === 'true';

if($isNeedLogToFile)
{
    $logger->pushHandler(new StreamHandler($pathLoggerInfo, Level::Info));
}

if($isNeedLogToConsole)
{
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$pathLoggerError = dirname(__DIR__, 1) . '\var\log\php2.error.log';
$pathLoggerDebug = dirname(__DIR__, 1) . '\var\log\php2.debug.log';


$container->bind(LoggerInterface::class, (new Logger('php2_logger'))->pushHandler(new StreamHandler($pathLoggerInfo, Level::Info))->pushHandler(new StreamHandler("php://stdout"))
    ->pushHandler(new StreamHandler($pathLoggerError, Level::Error))
    ->pushHandler(new StreamHandler($pathLoggerDebug, Level::Debug))
);

$container->bind(PDO::class, new PDO(databaseConfig()['sqlite']['DATABASE_URL']));
$container->bind(
    SqLiteConnector::class, new SqLiteConnector(databaseConfig()['sqlite']['DATABASE_URL']));

return $container;