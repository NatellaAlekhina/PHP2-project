<?php

namespace App\Connection;

use PDO;

interface ConnectorInterface
{
    public static function getConnector():PDO;
}