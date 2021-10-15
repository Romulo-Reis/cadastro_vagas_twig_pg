<?php

namespace App\Controllers;

use App\Services\LoginService;
use App\Models\Entidades\Usuario;
use App\Lib\DataUtil;
use App\Lib\Sessao;
use Exception;

class LoginController extends Controller
{
    public function index()
    {
        $this->setViewParam("erros", Sessao::retornaErro());
        $this->setViewParam("mensagem", Sessao::retornaMensagem());
        Sessao::limpaMensagem();
        Sessao::limpaErro();
        $this->render("@login/index.html.twig", $this->getViewVar());
    }

    public function logar()
    {
        if (empty($_POST)) {
            throw new Exception("Página não encontrada", 404);
        } else {
            $loginService = new LoginService();
            $login = $_POST['login'];
            $senha = $_POST['senha'];

            if ($loginService->autenticar($login, $senha)) {
                $this->redirect("/");
            } else {
                $this->redirect("/login");
            }
        }
    }

    public function deslogar()
    {
        $loginService = new LoginService();
        $loginService->deslogar();
        $this->redirect("/login");
    }

    public function cadastrar()
    {
        $usuario = new Usuario();

        if (Sessao::existeFormulario()) {
            $usuario->setLogin(Sessao::retornaValorFormulario("login"));
            $usuario->setEmail(Sessao::retornaValorFormulario("email"));
            $usuario->setSenha(Sessao::retornaValorFormulario("senha"));
            $usuario->setStatus(0);
            Sessao::limpaFormulario();
        }
            $this->setViewParam("usuario", $usuario);
            $this->setViewParam("erros", Sessao::retornaErro());
            Sessao::limpaErro();
            $this->render("@login/cadastro.html.twig", $this->getViewVar());
    }

    public function salvar()
    {
        if (empty($_POST)) {
            throw new Exception("Página não encontrada!", 404);
        } else {
            $usuario = new Usuario();
            $usuario->setLogin($_POST['login']);
            $usuario->setSenha($_POST['senha']);
            $usuario->setConfSenha($_POST['confSenha']);
            $usuario->setEmail($_POST['email']);
            $usuario->setStatus(false);
            $dataAtual = DataUtil::getDataAtual();
            $usuario->setDataCadastro(DataUtil::getDataFormatoATOM($dataAtual));
            Sessao::gravaFormulario($_POST);
            $loginService = new LoginService();
            if ($loginService->criarConta($usuario)) {
                $this->redirect("/login");
            } else {
                $this->redirect("/login/cadastrar");
            }
        }
    }

    public function recuperar()
    {
        $this->setViewParam("erros", Sessao::retornaErro());
        Sessao::limpaErro();
        $this->render("@login/recuperacao.html.twig", $this->getViewVar());
    }

    public function enviarCodigo()
    {
        $loginService = new LoginService();
        $email = "";
        if ($_POST) {
            $email = $_POST['email'];
            if ($loginService->enviarCodigo($email)) {
                $this->redirect("/login/alterarSenha");
            } else {
                $this->redirect("/login/recuperar");
            }
        } else {
            throw new Exception("Página não encontrada.", 404);
        }
    }
}
