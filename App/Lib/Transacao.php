<?php

namespace App\Lib;

use App\Lib\Conexao;
use Symfony\Component\VarDumper\Server\Connection;

class Transacao
{

    public function beginTransaction()
    {
        return Conexao::getConnection()->beginTransaction();
    }

    public function commit()
    {
        return Conexao::getConnection()->commit();
    }

    public function rollback()
    {
        return Conexao::getConnection()->rollback();
    }
}
