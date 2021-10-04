<?php

namespace App\Lib;

use App\Lib\Log;
use PDO;
use PDOException;
use Exception;

class Conexao
{

    private static $connection;

    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        $log = new Log(get_called_class());
        try {
            $drive = $_ENV['DB_DRIVER'];
            $host =  $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db_name = $_ENV['DB_NAME'];
            $pdoConfig = "$drive:host = $host;port=$port;";
            $pdoConfig .= "dbname=$db_name;";
            $usuario = $_ENV['DB_USER'];
            $senha = $_ENV['DB_PASSWORD'];
            if (self::$connection === null) {
                self::$connection = new PDO($pdoConfig, $usuario, $senha);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $log->info("Iniciando a conexão com o banco de dados.");
            }
            return self::$connection;
        } catch (PDOException $e) {
            $log->emergency("Erro de conexão com o banco de dados.", ['exception' => $e]);
            throw new Exception("Erro de conexão com o banco de dados.", 500, $e);
        }
    }
}
