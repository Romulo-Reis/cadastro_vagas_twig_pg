<?php

namespace App\Services;

use App\Lib\Sessao;
use App\Lib\Transacao;
use App\Lib\Log;
use App\Models\DAO\VagaDAO;
use App\Models\DAO\TecnologiaDAO;
use App\Models\Entidades\Vaga;
use App\Models\Validacao\VagaValidador;
use App\Models\Validacao\ResultadoValidacao;
use Exception;

class VagaService
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }
    public function listar($idVaga = null)
    {
        $this->log->info("Executando o método listar");
        $vagaDAO = new VagaDAO();
        return $vagaDAO->listar($idVaga);
    }

    public function salvar(Vaga $vaga)
    {
        $this->log->info("Executando o método salvar");
        $transacao = new Transacao();
        $vagaValidador = new VagaValidador();
        $resultadoValidador = $vagaValidador->validar($vaga);

        if ($resultadoValidador->getErros()) {
            Sessao::gravaErro($resultadoValidador->getErros());
            return false;
        } else {
            try {
                $vagaDAO = new VagaDAO();
                $transacao->beginTransaction();

                $id = $vagaDAO->salvar($vaga);
                $vaga->setIdVaga($id);
                $vagaDAO->addTecnologia($vaga);

                $transacao->commit();

                Sessao::limpaFormulario();
                Sessao::limpaMensagem();
                Sessao::gravaMensagem("Nova vaga cadastrada com sucesso.");
                return true;
            } catch (Exception $e) {
                $transacao->rollback();
                Sessao::limpaFormulario();
                Sessao::limpaErro();
                Sessao::limpaMensagem();
                throw new Exception($e);
                return false;
            }
        }
    }

    public function editar(Vaga $vaga)
    {
        $this->log->info("Executando o método editar");
        $transacao = new Transacao();
        $vagaValidador = new VagaValidador();
        $resultadoValidador = $vagaValidador->validar($vaga);

        if ($resultadoValidador->getErros()) {
            Sessao::gravaErro($resultadoValidador->getErros());
            return false;
        } else {
            try {
                $tecnologiaDAO = new TecnologiaDAO();
                $tecnologiaDAO->excluirPorVaga($vaga);
                $vagaDAO = new VagaDAO();
                $vagaDAO->addTecnologia($vaga);
                $vagaDAO->editar($vaga);
                Sessao::limpaFormulario();
                Sessao::limpaMensagem();
                Sessao::gravaMensagem("Vaga atualizada com sucesso.");
                return true;
            } catch (Exception $e) {
                $transacao->rollback();
                Sessao::gravaErro(["Erro ao editar a vaga!"]);
                return false;
            }
        }
    }

    public function excluir(Vaga $vaga)
    {
        $this->log->info("Executando o método excluir");
        $transacao = new Transacao();
        $vagaDAO = new VagaDAO();
        try {
            $transacao->beginTransaction();
            $vagaDAO->excluirComRelacionamento($vaga);
            $transacao->commit();

            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Vaga excluída com sucesso.");
            return true;
        } catch (Exception $e) {
            $transacao->rollback();
            Sessao::limpaMensagem();
            throw new Exception($e);
            return false;
        }
    }
}
