<?php

namespace App\Connection;

use PDO;

class SqliteConnector implements ConnectorInterface
{
    private static PDO $pdo;

    /*
        public function __construct(PDO $pdo)
        {
            self::$pdo = $pdo;

        }
    */
    public static function getConnector(): PDO
    {
        //var_dump(dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite');
        return new PDO("sqlite:" . dirname(__DIR__, 2) . '\newdatabase\dump\database.sqlite');

    }
}
/*
    public static function getConnector(): PDO
    {
        return self::$pdo;

    }
}
*/



