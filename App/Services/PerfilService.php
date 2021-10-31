<?php

namespace App\Services;

use App\Models\Entidades\Perfil;
use App\Models\DAO\PerfilDAO;
use App\Models\Validacao\PerfilValidadorGravar;
use App\Models\Validacao\PerfilValidadorExcluir;
use App\Lib\Log;
use App\Lib\Sessao;
use Exception;

class PerfilService
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function listar($id = null)
    {
        $this->log->info("Executando o método listar");
        $perfilDAO = new PerfilDAO();
        return $perfilDAO->listar($id);
    }

    public function salvar(Perfil $perfil)
    {
        $perfilDAO = new PerfilDAO();
        $PerfilValidadorGravar = new PerfilValidadorGravar();
        $resultadoValidacao = $PerfilValidadorGravar->validar($perfil);

        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $perfilDAO->salvar($perfil);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Novo perfil cadastrado com sucesso.");
            return true;
        }
    }

    public function editar(Perfil $perfil)
    {
        $perfilDAO = new PerfilDAO();
        $PerfilValidadorGravar = new PerfilValidadorGravar();
        $resultadoValidacao = $PerfilValidadorGravar->validar($perfil);

        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $perfilDAO->editar($perfil);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Perfil alterado com sucesso.");
            return true;
        }
    }

    public function excluir(Perfil $perfil)
    {
        $perfilDAO = new PerfilDAO();
        $perfilValidadorExcluir = new PerfilValidadorExcluir();
        $resultadoValidacao = $perfilValidadorExcluir->validar($perfil);
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
            return false;
        } else {
            $perfilDAO->excluir($perfil);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Perfil excluído com sucesso.");
            return true;
        }
    }
}
