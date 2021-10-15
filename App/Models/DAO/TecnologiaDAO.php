<?php

namespace App\Models\DAO;

use App\Models\Entidades\Vaga;
use App\Models\Entidades\Tecnologia;
use PDO;
use Exception;

class TecnologiaDAO extends BaseDAO
{

    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("tecnologia", "tecnologia_idtecnologia_seq");
    }

    public function listar($id = null)
    {
        if ($id) {
            $resultado = $this->select("select * from tecnologia where excluido = '0' and idtecnologia = $id");
        } else {
            $resultado = $this->select("select * from tecnologia where excluido = '0'");
        }
        return $resultado->fetchAll(PDO::FETCH_CLASS, Tecnologia::class);
    }

    public function listarPorVaga($id = null)
    {
        if ($id) {
            $resultado = $this->select("select t.idtecnologia, t.tecnologia from tecnologiasporvaga v inner join tecnologia t on t.idtecnologia = v.\"FK_idtecnologia\" where v.excluido = '0' and v.\"FK_idvaga\" = $id");
        }

        return $resultado->fetchAll(PDO::FETCH_CLASS, Tecnologia::class);
    }

    public function listarPorTecnologia(Tecnologia $tecnologia)
    {
        $resultado = $this->select("SELECT * FROM tecnologia where excluido = '0' and tecnologia LIKE '%" . $tecnologia->getTecnologia() . "%' LIMIT 6 OFFSET 0");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar(Tecnologia $tecnologia)
    {
        try {
            $nomeTecnologia = $tecnologia->getTecnologia();

            return $this->insert(
                "tecnologia",
                ":tecnologia",
                [
                ":tecnologia" => $nomeTecnologia
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(Tecnologia $tecnologia)
    {
        try {
            $idTecnologia = $tecnologia->getIdTecnologia();
            $nomeTecnologia = $tecnologia->getTecnologia();
            return $this->update(
                "tecnologia",
                "tecnologia = :tecnologia",
                [
                ":tecnologia" => $nomeTecnologia
                ],
                "idtecnoologia = $idTecnologia"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 50, $e);
        }
    }

    public function excluir(Tecnologia $tecnologia)
    {
        try {
            $idTecnologia = $tecnologia->getIdTecnologia();
            $this->delete("tecnologia", "idtecnologia = $idTecnologia");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro ao deletar a tecnologia.", 500, $e);
        }
    }

    public function excluirPorVaga(Vaga $vaga)
    {
        try {
            $idvaga = $vaga->getIdVaga();
            return $this->delete("tecnologiasporvaga", "FK_idvaga = $idvaga");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro ao deletar as tecnologias por vaga.", 500, $e);
        }
    }

    public function verificarExistencia(Tecnologia $tecnologia)
    {
        $resultado = $this->select("select count(*) from tecnologia where excluido = '0' and tecnologia ='" . $tecnologia->getTecnologia() . "'");
        return $resultado->fetchColumn();
    }

    public function verificarExistenciaVagas(Tecnologia $tecnologia)
    {
        $resultado = $this->select("select count(*) from tecnologiasporvaga where excluido = '0' and FK_idtecnologia ='" . $tecnologia->getIdTecnologia() . "'");
        return $resultado->fetchColumn();
    }
}
