<?php

namespace App\Models\DAO;

use App\Lib\Log;
use App\Lib\Conexao;
use DateTimeImmutable;

abstract class BaseDAO
{

    private $connection;
    private $sequences = [];
    protected $log;

    public function __construct()
    {
        $this->log = $log = new Log(get_class($this));
        if ($_ENV['DB_DRIVER'] == "pgsql") {
            $this->definirSequenceChavePrimaria();
        }
        $this->connection = Conexao::getConnection();
    }

    abstract public function definirSequenceChavePrimaria();

    public function getSequence($nomeTabela)
    {
        return $this->sequences[$nomeTabela] ? $this->sequences[$nomeTabela] : "";
    }

    public function setSequence($nomeTabela, $sequence)
    {
        $this->sequences[$nomeTabela] = $sequence;
    }


    public function select($sql)
    {
        $this->log->info("Executando o método select.");
        return $this->connection->query($sql);
    }
    public function formataColunaPG($colunas)
    {
        $vet = explode(",", $colunas);
        for ($i = 0; $i < count($vet); $i++) {
            if (preg_match('/[A-Z]+/', trim($vet[$i]))) {
                $vet[$i] = "\"" . trim($vet[$i]) . "\"";
            }
        }
        return implode(",", $vet);
    }
    public function insert($table, $cols, $values)
    {
        $this->log->info("Executando o método insert.");
        $id = "";
        if (!empty($table) && !empty($cols) && !empty($values)) {
            $parametros = $cols;
            $colunas = str_replace(":", "", $cols);
            $colunas = $this->formataColunaPG($colunas);
            $stm = $this->connection->prepare("insert into $table ($colunas) VALUES ($parametros)");
            $stm->execute($values);
            if (!empty($this->getSequence($table))) {
                $id = $this->connection->lastInsertId($this->getSequence($table));
            } else {
                $id = $this->connection->lastInsertId();
            }
            return $id;
        } else {
            return false;
        }
    }

    public function update($table, $cols, $values, $where = null)
    {
        $this->log->info("Executando o método update.");
        if (!empty($table) && !empty($cols) && !empty($values)) {
            if ($where) {
                $where = " where $where";
            }

            $stm = $this->connection->prepare("update $table set $cols $where");
            $stm->execute($values);

            return $stm->rowCount();
        } else {
            return false;
        }
    }

    public function delete($table, $where = null)
    {
        $this->log->info("Executando o método delete.");
        return $this->update(
            $table,
            "excluido = :excluido",
            [
                ":excluido" => 1
            ],
            $where
        );
    }

    public function sqlDateTimeFromMicroTimestamp(int $microtimestamp): string
    {
        $dt = new DateTimeImmutable();
        $normalTimestamp = (int)floor($microtimestamp / 1000000);
        $sqlTimestampWithoutMicroseconds = $dt->setTimestamp($normalTimestamp)->format('Y-m-d H:i:s');
        $sqlTimestampWithMicroseconds = $sqlTimestampWithoutMicroseconds . '.' . ($microtimestamp % 1000000);
        return $sqlTimestampWithMicroseconds;
    }
}
