<?php

namespace App\Connection;

use PDO;

class SqliteConnector implements ConnectorInterface
{
    public static function getConnector(): PDO
    {
        //var_dump(dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite');
        return new PDO("sqlite:" . dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite');

    }
}




