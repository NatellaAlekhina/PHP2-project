<?php

namespace Test\Container;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

use App\Connection\ConnectorInterface;
use App\Connection\SqliteConnector;
use App\Container\ClassWithoutDependencies;
use App\Container\ClassWithParameter;
use App\Container\DIContainer;
use App\Handlers\UserSearchHandler;
use App\Handlers\UserSearchHandlerInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use PDO;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testItResolveClassWithoutDependencies()
    {
        $container = new DIContainer();

        $object = $container->get(ClassWithoutDependencies::class);

        $this->assertInstanceOf(ClassWithoutDependencies::class, $object);
    }

    public function testItResolveClassUserRepository()
    {
        $container = new DIContainer();

        $container->bind(UserRepositoryInterface::class,UserRepository::class);

        $object = $container->get(UserRepositoryInterface::class);

        $this->assertInstanceOf(UserRepository::class, $object);
    }

    public function testItResolveClassWithParameter()
    {
        $container = new DIContainer();

        $container->bind(ClassWithParameter::class,new ClassWithParameter(1));

        $object = $container->get(ClassWithParameter::class);

        $this->assertSame(1, $object->getValue());
    }

    public function testItA()
    {
        $container = new DIContainer();
        $container->bind(PDO::class, new PDO("sqlite:" . dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite'));
        $object = $container->get(PDO::class);
        $this->assertInstanceOf(PDO::class, $object);
    }

    public function testItResolveClassWithSomeParameter()
    {
        $container = new DIContainer();

        $container->bind(PDO::class, new PDO("sqlite:" . dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite'));

        $container->bind(ConnectorInterface::class, SqliteConnector::class);
        $container->bind(UserRepositoryInterface::class, UserRepository::class);
        $container->bind(UserSearchHandlerInterface::class, UserSearchHandler::class);

        $container->bind(SqliteConnector::class,
            new SqliteConnector(
                new PDO("sqlite:" . dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite')
            )
        );

       /*
        $container->bind(SqliteConnector::class, new SqliteConnector(
            $container->get(PDO::class),
            2
        ));
        */

        $object = $container->get(UserSearchHandlerInterface::class);
        $this->assertInstanceOf(UserSearchHandler::class, $object);
    }
}