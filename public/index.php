<?php

use App\Argument\Argument;
use App\Blog\Article;
use App\Blog\Comment;
use App\Commands\CreateUserCommand;
use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Exceptions\CommandException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Request\Request;
use APP\Response\SuccessResponse;
use App\User\User;

require_once __DIR__ . '/autoloader_runtime.php';



/*function makeUser(string $userName): User|int
{
    if (empty($userName)){
        return 0;
    }
    return new User($userName);
}*/

/*
$user = new User(1, 'Ivan', 'Petrov');
$article = new Article(1, $user->getId(), $user,"my first article","what a nice day");
$comment = new Comment(1, $user->getId(), $user, $article->getId(), $article,"good article!");

echo $comment;

$user2 = new User(2,'Tim','Burton');

echo new Article(
    2,
    $user2->getId(),
    $user2,
    'My first article',
    'What a nice day today. It is Sunday!'
);
*/

/*
$user3 = new User(
    3,
    'Publo',
    'Picola'
);

try {
    $userRepository->save($user3);
    $user4 = $userRepository->get(4);
    }
catch (UserNotFoundException $exception)
{
    print($exception->getMessage().PHP_EOL);
}
*/

//var_dump(\App\Connection\SqliteConnector::getConnector());
//var_dump($config);
/*
$now = new DateTimeImmutable();
$now = $now->format('Y-m-d H:i:s');
$connection = \App\Connection\SqliteConnector::getConnector();

$connection->exec(
        "insert into user (name, surname, created_at) 
        values ('Bobby' ,'Braun', '{$now}')"
    );

$user = new User('Mary','Cooper');
$userRepository->save($user);

var_dump($user = $userRepository->get(8));

*/

//$articleRepository = new ArticleRepository();
//$article = new Article(1, 'hello world','hi every one from this chat');
//var_dump($article);
//$articleRepository->save($article);
//var_dump();
//die();
//print_r($articleRepository->get(1));
//$commentRepository = new CommentRepository();
//$comment = new Comment(5,2,'test error 1');
//$commentRepository->save($comment);
//print_r($commentRepository->get(1));

$userRepository = new UserRepository();
$newUser = new CreateUserCommand($userRepository);
//$newUser->handle(['email=1@test.ru', 'name=Иван', 'surname=Войнов']);

/*
try {
    $newUser->handle(Argument::fromArgv($argv));
    echo "New user is done!";
}catch (CommandException $commandException)
{
    echo $commandException->getMessage();
}
*/

$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);
$parameter = $request->query('some_parameter');
$parameter = $request->header('Some-Header');
$path = $request->path();

$response = new SuccessResponse(['message'=> 'Hello PHP']);
$response->send();

echo "Hello";

