<?php

namespace Bruna\Pokedex\Persistence;

use Symfony\Component\Dotenv\Dotenv;
use PDO;

class ConnectionCreator
{
    static public ?PDO $connection = null;

    public static function createConnection(): \PDO
    {
        if (is_null(self::$connection)) {
            $dotenv = new Dotenv();
            $dotenv->load(__DIR__.'/../../.env');
    
            $username = $_ENV["MYSQL_USER"];
            $password = $_ENV["PASSWORD"];
            
            self::$connection = new PDO(
                'mysql:host=' . $_ENV["HOSTNAME"] 
                . ';dbname=' 
                . $_ENV["DATABASE"], 
                $username, 
                $password
            );
        }
        return self::$connection;
    }
}
