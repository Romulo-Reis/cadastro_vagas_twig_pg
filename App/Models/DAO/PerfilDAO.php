<?php

namespace App\Models\DAO;

use App\Models\Entidades\Perfil;
use PDO;
use Exception;

class PerfilDAO extends BaseDAO
{

    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("perfil", "perfilusuario_idperfil_seq");
    }

    public function listar($id = null)
    {
        try {
            if ($id) {
                $resultado = $this->select(
                    "SELECT * FROM perfil where excluido = '0' and \"idPerfil\" = $id"
                );
            } else {
                $resultado = $this->select(
                    "SELECT * FROM perfil where excluido = '0'"
                );
            }

            return $resultado->fetchAll(PDO::FETCH_CLASS, Perfil::class);
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Erro na listagem dos dados.", 500, $e);
        }
    }

    public function salvar(Perfil $perfil)
    {
        try {
            $nome = $perfil->getNome();

            return $this->insert(
                "perfil",
                ":nome",
                [
                    ":nome" => $nome
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(perfil $perfil)
    {
        try {
            $idPerfil = $perfil->getIdPerfil();
            $nome = $perfil->getNome();

            return $this->update(
                "perfil",
                "nome = :nome",
                [
                    ":nome" => $nome
                ],
                "\"idPerfil\" = $idPerfil"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados", 500, $e);
        }
    }

    public function excluir(Perfil $perfil)
    {
        try {
            $id = $perfil->getIdPerfil();
            $this->delete("perfil", "\"idPerfil\" = $id");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados", 500, $e);
        }
    }

    public function varificaExistenciaUsuarios(Perfil $perfil)
    {
        $idPerfil = $perfil->getIdPerfil();
        $resultado = $this->select("select count(*) from usuario where \"FK_idPerfil\" = $idPerfil");
        return $resultado->fetchColumn();
    }
}
