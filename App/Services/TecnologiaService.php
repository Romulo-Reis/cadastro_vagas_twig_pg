<?php

namespace App\Services;

use App\Models\Entidades\Tecnologia;
use App\Models\Entidades\Vaga;
use App\Models\DAO\TecnologiaDAO;
use App\Models\Validacao\TecnologiaValidadorInserir;
use App\Models\Validacao\TecnologiaValidadorEditar;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Sessao;
use App\Lib\Exportar;
use App\Lib\Log;

class TecnologiaService
{

    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function listar($idTecnologia = null)
    {
        $this->log->info("Executando o método listar");
        $tecnologiaDAO = new TecnologiaDAO();
        return $tecnologiaDAO->listar($idTecnologia);
    }

    public function listarPorVaga(Vaga $vaga)
    {
        $this->log->info("Executando o método listarPorVaga");
        $tecnologiaDao = new TecnologiaDAO();
        return $tecnologiaDao->listarPorVaga($vaga->getIdVaga());
    }

    public function autoComplete(Tecnologia $tecnologia)
    {
        $this->log->info("Executando o método autoComplete");
        $tecnologiaDAO = new TecnologiaDAO();
        $busca = $tecnologiaDAO->listarPorTecnologia($tecnologia);
        $exportar = new Exportar();
        return $exportar->exportarJSON($busca);
    }

    public function salvar(Tecnologia $tecnologia)
    {
        $this->log->info("Executando o método salvar");
        $tecnologiaValidador = new TecnologiaValidadorInserir();
        $resultadoValidacao = $tecnologiaValidador->validar($tecnologia);
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $tecnologiaDAO = new TecnologiaDAO();
            $tecnologiaDAO->salvar($tecnologia);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Nova tecnologia cadastrada com sucesso.");
            return true;
        }
    }

    public function editar(Tecnologia $novaTecnologia)
    {
        $this->log->info("Executando o método editar");
        $tecnologiaValidador = new TecnologiaValidadorEditar();
        $tecnologiaDAO = new TecnologiaDAO();
        $tecnologiaCadastrada = $tecnologiaDAO->listar($novaTecnologia->getIdTecnologia())[0];
        $resultadoValidacao = $tecnologiaValidador->validar($novaTecnologia, $tecnologiaCadastrada);
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $tecnologiaDAO->editar($novaTecnologia);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Tecnologia atualizada com sucesso.");
            return true;
        }
    }

    public function preencheTecnologias($arrayTecnologias)
    {
        $this->log->info("Executando o método preencheTecnologias");
        $tecnologiaDao = new TecnologiaDAO();
        $tecnologias = [];
        foreach ($arrayTecnologias as $idTecnologia) {
            $tecnologia = $tecnologiaDao->listar($idTecnologia)[0];
            $tecnologias[] = $tecnologia;
        }
        return $tecnologias;
    }

    public function excluir(Tecnologia $tecnologia)
    {
    }
}
