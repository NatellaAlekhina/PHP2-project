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

require_once __DIR__ . '/../newdatabase/config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);

$container = new DIContainer();

$dsn = 'sqlite:C:\Users\Abond\Desktop\Geekbrains_PHP2\newdatabase\dump\database.sqlite';

$container->bind(PDO::class, SqliteConnector::getConnector());

$container->bind(ConnectorInterface::class, SqliteConnector::class);
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(UserSearchHandlerInterface::class, UserSearchHandler::class);
$container->bind(ArticleSearchHandlerInterface::class, ArticleSearchHandler::class);
$container->bind(CommentSearchHandlerInterface::class, CommentSearchHandler::class);
$container->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
$container->bind(CommentRepositoryInterface::class, CommentRepository::class);
$container->bind(LikeRepositoryInterface::class, LikeRepository::class);


$container->bind(SqliteConnector::class,
SqliteConnector::getConnector());

return $container;
