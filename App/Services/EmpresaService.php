<?php

namespace App\Services;

use App\Models\Entidades\Empresa;
use App\Models\DAO\EmpresaDAO;
use App\Models\DAO\VagaDAO;
use App\Lib\Sessao;
use App\Lib\Transacao;
use App\Lib\Exportar;
use App\Lib\Log;
use App\Models\Validacao\EmpresaValidadorInserir;
use App\Models\Validacao\EmpresaValidadorEditar;
use App\Models\Validacao\ResultadoValidacao;
use Exception;

class EmpresaService
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function listar($idEmpresa = null)
    {
        $this->log->info("Executando o método listar");
        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->listar($idEmpresa);
    }

    public function listarVagasVinculadas(Empresa $empresa)
    {
        $this->log->info("Executando o método listarVagasVinculadas");
        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->listarVagasVinculadas($empresa);
    }

    public function autoComplete(Empresa $empresa)
    {
        $this->log->info("Executando o método autoComplete");
        $empresaDao = new EmpresaDAO();
        $busca = $empresaDao->listaPorNomeFantasia($empresa);
        $exportar = new Exportar();
        return $exportar->exportarJSON($busca);
    }

    public function salvar(Empresa $empresa)
    {
        $this->log->info("Executando o método salvar");
        $empresaValidadorInserir = new EmpresaValidadorInserir();
        $resultadoValidacao = $empresaValidadorInserir->validar($empresa);
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $empresaDAO = new EmpresaDAO();
            $id = $empresaDAO->salvar($empresa);
            $empresa->setIdEmpresa($id);
            Sessao::gravaMensagem("Nova empresa cadastrada com sucesso.");
            Sessao::limpaFormulario();
            return true;
        }
    }

    public function editar(Empresa $novaEmpresa)
    {
        $this->log->info("Executando o método editar");
        $empresaDAO = new EmpresaDAO();
        $empresaCadastrada = $empresaDAO->listar($novaEmpresa->getIdEmpresa())[0];
        $empresaValidacaoEditar = new EmpresaValidadorEditar();
        $resultadoValidacao = $empresaValidacaoEditar->validar($novaEmpresa, $empresaCadastrada);
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
        } else {
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Empresa atualizada com sucesso!");
            return $empresaDAO->editar($novaEmpresa);
        }
        return false;
    }

    public function excluir(Empresa $empresa)
    {
        $this->log->info("Executando o método excluir");
        $transacao = new Transacao();
        $empresaDAO = new EmpresaDAO();
        $vagaDAO = new VagaDAO();
        try {
            $transacao->beginTransaction();
            $vagas = $empresaDAO->listarVagasVinculadas($empresa);

            if (isset($vagas)) {
                foreach ($vagas as $vaga) {
                    $vagaDAO->excluirComRelacionamento($vaga);
                }
            }

            $empresaDAO->excluir($empresa);
            $transacao->commit();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Empresa excluída com sucesso.");
            return true;
        } catch (Exception $e) {
            $transacao->rollback();
            throw new Exception($e);
            return false;
        }
    }
}
