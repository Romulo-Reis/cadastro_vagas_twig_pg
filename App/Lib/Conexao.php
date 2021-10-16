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
        $dbopts = parse_url($_ENV['DATABASE_URL']);
        try {
            $drive = $_ENV['DB_DRIVER'];
            $host =  $dbopts["host"];
            $port = $dbopts["port"];
            $db_name = ltrim($dbopts["path"], '/');
            $pdoConfig = "$drive:host = $host;port=$port;";
            $pdoConfig .= "dbname=$db_name;";
            $usuario = $dbopts["user"];
            $senha = $dbopts["pass"];
            if (self::$connection === null) {
                self::$connection = new PDO($pdoConfig, $usuario, $senha);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                $log->info("Iniciando a conexão com o banco de dados.");
            }
            return self::$connection;
        } catch (PDOException $e) {
            $log->emergency("Erro de conexão com o banco de dados.", ['exception' => $e]);
            throw new Exception("Erro de conexão com o banco de dados.", 500, $e);
        }
    }
}
