<?php

namespace App\Models\DAO;

use App\Models\Entidades\Empresa;
use App\Models\Entidades\Vaga;
use PDO;
use Exception;

class EmpresaDAO extends BaseDAO
{

    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("empresa", "empresa_idempresa_seq");
    }

    public function listar($id = null)
    {
        try {
            if ($id) {
                $resultado = $this->select(
                    "SELECT * FROM empresa where idempresa = $id"
                );
            } else {
                $resultado = $this->select(
                    "SELECT * FROM empresa"
                );
            }

            return $resultado->fetchAll(PDO::FETCH_CLASS, Empresa::class);
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Erro na listagem dos dados.", 500, $e);
        }
    }
    public function listaPorNomeFantasia(Empresa $empresa)
    {

        $resultado = $this->select(
            "SELECT * FROM empresa where nomefantasia like '%" . $empresa->getNomeFantasia() . "%' LIMIT 6 OFFSET 0"
        );
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarVagasVinculadas(Empresa $empresa)
    {

        $id = $empresa->getIdEmpresa();
        $resultado = $this->select(
            "SELECT * FROM vaga where \"FK_idempresa\" = $id"
        );
        return $resultado->fetchAll(PDO::FETCH_CLASS, Vaga::class);
    }

    public function verificaExistenciaCNPJ(Empresa $empresa)
    {
        $resultado = $this->select("SELECT count(*) FROM empresa where \"CNPJ\" = '" . $empresa->getCNPJ() . "'");
        return $resultado->fetchColumn();
    }

    public function verificaExistenciaRazaoSocial(Empresa $empresa)
    {
        $resultado = $this->select("SELECT count(*) FROM empresa where razaosocial = '" . $empresa->getRazaoSocial() . "'");
        return $resultado->fetchColumn();
    }

    public function verificaExistenciaNomeFantasia(Empresa $empresa)
    {
        $resultado = $this->select("SELECT count(*) FROM empresa where nomefantasia = '" . $empresa->getNomeFantasia() . "'");
        return $resultado->fetchColumn();
    }

    public function salvar(Empresa $empresa)
    {
        try {
            $razaoSocial = $empresa->getRazaoSocial();
            $nomeFantasia = $empresa->getNomeFantasia();
            $CNPJ = $empresa->getCNPJ();

            return $this->insert(
                "empresa",
                ":razaosocial, :nomefantasia, :CNPJ",
                [
                    ":razaosocial" => $razaoSocial,
                    ":nomefantasia" => $nomeFantasia,
                    ":CNPJ" => $CNPJ
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(Empresa $empresa)
    {
        try {
            $idEmpresa = $empresa->getIdEmpresa();
            $razaoSocial = $empresa->getRazaoSocial();
            $nomeFantasia = $empresa->getNomeFantasia();
            $CNPJ = $empresa->getCNPJ();

            return $this->update(
                "empresa",
                "razaosocial = :razaosocial, nomefantasia = :nomefantasia, \"CNPJ\" = :CNPJ",
                [
                    ":razaosocial" => $razaoSocial,
                    ":nomefantasia" => $nomeFantasia,
                    ":CNPJ" => $CNPJ
                ],
                "idempresa = $idEmpresa"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados", 500, $e);
        }
    }

    public function excluir(Empresa $empresa)
    {
        try {
            $id = $empresa->getIdEmpresa();
            $this->delete("empresa", "idempresa = $id");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados", 500, $e);
        }
    }
}
