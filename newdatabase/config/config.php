<?php

function databaseConfig(): array
{
    return [
        'sqlite' =>
            [
                'driver' => 'sqlite',
                'DATABASE_URL' => 'sqlite:' . dirname(__DIR__, 2). '\newdatabase\dump\database.sqlite',
            ],
        'mysql' =>
            [
                'driver' => 'mysql',
                'user' => 'root',
                'password' => '***',
                'database' => 'test',
            ],
    ];
}