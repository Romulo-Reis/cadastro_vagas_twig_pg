<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\Validacao\LoginValidadorUsuarioLogado;

class HomeController extends Controller
{

    public function index()
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render('@home/index.html.twig', $this->getViewVar());
            Sessao::limpaMensagem();
        }
    }
}
