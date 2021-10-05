<?php

namespace App\Lib;

use App\Lib\Conexao;
use App\Lib\Log;
use Exception;
use PDOException;

class Transacao
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function beginTransaction()
    {
        try {
            $result = false;
            if (!Conexao::getConnection()->inTransaction()) {
                $result = Conexao::getConnection()->beginTransaction();
                if (!$result) {
                    throw new PDOException("Não foi possível iniciar a transação.", 500);
                }
            }
            return $result;
        } catch (PDOException $e) {
            $this->log->alert($e->getMessage(), ['exception' => $e]);
            throw new Exception($e);
        }
    }

    public function commit()
    {
        try {
            $result = false;
            if (Conexao::getConnection()->inTransaction()) {
                $result = Conexao::getConnection()->commit();
                if (!$result) {
                    throw new PDOException("Não foi possível realizar o commit da transação", 500);
                }
            }
            return $result;
        } catch (PDOException $e) {
            $this->log->alert($e->getMessage(), ['exception' => $e]);
            throw new Exception($e);
        }
    }

    public function rollback()
    {
        try {
            $result = false;
            if (Conexao::getConnection()->inTransaction()) {
                $result = Conexao::getConnection()->rollback();
                if (!$result) {
                    throw new PDOException("Não foi possível realizar o rollback da transação", 500);
                }
            }
            return $result;
        } catch (PDOException $e) {
            $this->log->alert($e->getMessage(), ['exception' => $e]);
            throw new Exception($e);
        }
    }
}
