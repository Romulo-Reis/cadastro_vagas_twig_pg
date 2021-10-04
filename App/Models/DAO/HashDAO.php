<?php

namespace App\Models\DAO;

use Exception;
use PDO;
use App\Models\Entidades\Hash;
use App\Models\Entidades\Usuario;

class HashDAO extends BaseDAO
{

    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("hash", "\"hash_idHash_seq\"");
    }

    public function listar($id = null)
    {
        try {
            if ($id) {
                $resultado = $this->select("select h.\"idHash\", h.hash, h.status, h.\"dataCadastro\", u.\"idUsuario\", u.nome, u.login, u.status, u.senha, u.email, u.\"dataCadastro\" from hash h inner join usuario u on u.\"FK_idUsuario\" = u.\"idUsuario\" where h.\"idHash\" = $id");
            } else {
                $resultado = $this->select("select h.\"idHash\", h.hash, h.status, h.\"dataCadastro\", u.\"idUsuario\", u.nome, u.login, u.status, u.senha, u.email, u.\"dataCadastro\" from hash h inner join usuario u on u.\"FK_idUsuario\" = u.\"idUsuario\"");
            }
            $dataSetHashs = $resultado->fetchAll();
            $listaHashs = [];
            foreach ($dataSetHashs as $dataSetHash) {
                $hash = new Hash();
                $hash->setIdHash($dataSetHash['idHash']);
                $hash->setHash($dataSetHash['hash']);
                $hash->setStatus($dataSetHash['status']);
                $hash->setDataCadastro($dataSetHash['dataCadastro']);
                $hash->setUsuario(new Usuario());
                $hash->getUsuario()->setIdUsuario($dataSetHash['idUsuario']);
                $hash->getUsuario()->setNome($dataSetHash['nome']);
                $hash->getUsuario()->setLogin($dataSetHash['login']);
                $hash->getUsuario()->setSenha($dataSetHash['senha']);
                $hash->getUsuario()->setDataCadastro($dataSetHash['dataCadastro']);
                $hash->getUsuario()->setStatus($dataSetHash['status']);
                $listaHashs[] = $hash;
            }
            return $listaHashs;
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Falha na listagem dos dados.", 500, $e);
        }
    }

    public function listarPorIdUsuario($id = null)
    {
        try {
            $listaHashs = [];
            if ($id) {
                $resultado = $this->select("select h.\"idHash\", h.hash, h.status, h.\"dataCadastro\", u.\"idUsuario\", u.login, u.status, u.senha, u.email, u.\"dataCadastro\" from hash h inner join usuario u on h.\"FK_idUsuario\" = u.\"idUsuario\" where h.\"FK_idUsuario\" = $id");
                $dataSetHashs = $resultado->fetchAll();

                foreach ($dataSetHashs as $dataSetHash) {
                    $hash = new Hash();
                    $hash->setIdHash($dataSetHash['idHash']);
                    $hash->setHash($dataSetHash['hash']);
                    $hash->setStatus($dataSetHash['status']);
                    $hash->setDataCadastro($dataSetHash['dataCadastro']);
                    $hash->setUsuario(new Usuario());
                    $hash->getUsuario()->setIdUsuario($dataSetHash['idUsuario']);
                    $hash->getUsuario()->setLogin($dataSetHash['login']);
                    $hash->getUsuario()->setSenha($dataSetHash['senha']);
                    $hash->getUsuario()->setEmail($dataSetHash['email']);
                    $hash->getUsuario()->setDataCadastro($dataSetHash['dataCadastro']);
                    $hash->getUsuario()->setStatus($dataSetHash['status']);
                    $listaHashs[] = $hash;
                }
            }
            return $listaHashs;
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Falha na listagem dos dados.", 500, $e);
        }
    }

    public function salvar(Hash $hash)
    {
        try {
            $codigoHash = $hash->getHash();
            $status = $hash->getStatus();
            $idUsuario = $hash->getUsuario()->getIdUsuario();
            $dataCadastro = $hash->getDataCadastro()->format("Y-m-d H:i:s");
            return $this->insert(
                "hash",
                ":hash, :status, :FK_idUsuario, :dataCadastro",
                [
                    ":hash" => $codigoHash,
                    ":status" => $status,
                    ":FK_idUsuario" => $idUsuario,
                    ":dataCadastro" => $dataCadastro
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(Hash $hash)
    {
        try {
            $idHash = $hash->getIdHash();
            $codigoHash = $hash->getHash();
            $status = $hash->getStatus();
            $idUsuario = $hash->getUsuario()->getIdUsuario();
            return $this->update(
                "hash",
                "hash = :hash, status = :status, FK_idUsuario = :FK_idUsuario",
                [
                    ":hash" => $codigoHash,
                    ":status" => $status,
                    ":FK_idUsuario" => $idUsuario
                ],
                "\"idHash\" = $idHash"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluir(Hash $hash)
    {
        try {
            $idHash = $hash->getIdHash();
            $this->delete("hash", "\"idHash\" = $idHash");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function ativar(Hash $hash)
    {
        try {
            $idHash = $hash->getIdHash();
            return $this->update(
                "hash",
                "status = :status",
                [
                    ":status" => 1
                ],
                "\"idHash\" = $idHash"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }
}
