<?php

namespace App\Models\DAO;

use App\Models\Entidades\Vaga;
use App\Models\Entidades\Empresa;
use App\Models\Entidades\Tecnologia;
use Exception;

class VagaDAO extends BaseDAO
{

    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("vaga", "vaga_idvaga_seq");
    }

    public function listar($id = null)
    {
        $SQL = "select v.idvaga, v.titulo, v.descricao, v.\"FK_idempresa\", v.excluido as \"excluidoVaga\", e.idempresa, e.razaosocial, e.nomefantasia, e.\"CNPJ\", e.excluido as \"excluidoEmpresa\" from vaga v inner join empresa e on v.\"FK_idempresa\" = e.idempresa where v.excluido = '0'";

        if ($id) {
            $SQL .= " and v.idvaga = $id";
        }

        $resultado = $this->select($SQL);
        $dataSetVagas = $resultado->fetchAll();
        $listaVagas = [];
        foreach ($dataSetVagas as $dataSetVaga) {
            $vaga = new Vaga();
            $vaga->setIdVaga($dataSetVaga['idvaga']);
            $vaga->setTitulo($dataSetVaga['titulo']);
            $vaga->setDescricao($dataSetVaga['descricao']);
            $vaga->setExcluido($dataSetVaga['excluidoVaga']);
            $vaga->setEmpresa(new Empresa());
            $vaga->getEmpresa()->setIdEmpresa($dataSetVaga['FK_idempresa']);
            $vaga->getEmpresa()->setNomeFantasia($dataSetVaga['nomefantasia']);
            $vaga->getEmpresa()->setRazaoSocial($dataSetVaga['razaosocial']);
            $vaga->getEmpresa()->setCNPJ($dataSetVaga['CNPJ']);
            $vaga->getEmpresa()->setExcluido($dataSetVaga['excluidoEmpresa']);
            $listaVagas[] = $vaga;
        }
        return $listaVagas;
    }

    public function salvar(Vaga $vaga)
    {
        try {
            $titulo = $vaga->getTitulo();
            $idEmpresa = $vaga->getEmpresa()->getIdEmpresa();
            $descricao = $vaga->getDescricao();
            return $this->insert(
                "vaga",
                ":titulo, :FK_idempresa, :descricao",
                [
                ":titulo" => $titulo,
                ":FK_idempresa" => $idEmpresa,
                ":descricao" => $descricao
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(Vaga $vaga)
    {
        try {
            $idVaga = $vaga->getIdVaga();
            $titulo = $vaga->getTitulo();
            $idEmpresa = $vaga->getEmpresa()->getIdEmpresa();
            $descricao = $vaga->getDescricao();
            return $this->update(
                "vaga",
                "titulo = :titulo, \"FK_idempresa\" = :FK_idempresa, descricao = :descricao",
                [
                ":titulo" => $titulo,
                ":FK_idempresa" => $idEmpresa,
                ":descricao" => $descricao
                ],
                "idvaga = $idVaga"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function addTecnologia(Vaga $vaga)
    {
        $tecnologias = $vaga->getTecnologias();
        try {
            if (isset($tecnologias)) {
                foreach ($tecnologias as $tecnologia) {
                    $this->insert(
                        "tecnologiasporvaga",
                        ":FK_idvaga, :FK_idtecnologia",
                        [
                        ":FK_idvaga" => $vaga->getIdVaga(),
                        ":FK_idtecnologia" => $tecnologia->getIdTecnologia()
                        ]
                    );
                }
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluirComRelacionamento(Vaga $vaga)
    {
        try {
            $id = $vaga->getIdVaga();
            $this->delete("tecnologiasporvaga", "\"FK_idvaga\" = $id");
            $this->delete("vaga", "idvaga = $id");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }
}
