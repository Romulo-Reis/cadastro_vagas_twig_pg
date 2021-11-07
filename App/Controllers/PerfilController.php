<?php

namespace App\Controllers;

use App\Services\PerfilService;
use App\Models\Validacao\LoginValidadorUsuarioLogado;
use App\Models\Entidades\Perfil;
use App\Lib\Sessao;
use Exception;

class PerfilController extends Controller
{

    public function listar($params)
    {
        $perfilService = new PerfilService();
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();
        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $id = $params[0];
            $perfis = $perfilService->listar($id);
            $this->setViewParam("perfis", $perfis);
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render("@perfil/listar.html.twig", $this->getViewVar());
            Sessao::limpaMensagem();
        }
    }

    public function cadastrar()
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $perfil = new Perfil();
            if (Sessao::existeFormulario()) {
                $perfil->setNome(Sessao::retornaFormulario("nome"));
                Sessao::limpaFormulario();
            }
        }
        $this->setViewParam("perfil", $perfil);
        $this->setViewParam("erros", Sessao::retornaErro());
        $this->render("@perfil/cadastro.html.twig");
    }

    public function salvar()
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            if (empty($_POST)) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $perfil = new Perfil();
                $perfil->setNome(trim($_POST["nome"]));
                Sessao::gravaFormulario($_POST);
                $perfilService = new PerfilService();
                if ($perfilService->salvar($perfil)) {
                    $this->redirect('/perfil/listar');
                } else {
                    $this->redirect('/perfil/cadastrar');
                }
            }
        }
    }

    public function edicao($params)
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $perfilService = new PerfilService();
            $perfil = new Perfil();
            $id = $params[0];

            if (ctype_digit($id)) {
                if (Sessao::existeFormulario()) {
                    $perfil->setIdPerfil(Sessao::retornaFormulario('idPerfil'));
                    $perfil->setNome(Sessao::retornaFormulario('nome'));
                    Sessao::limpaFormulario();
                } else {
                    $perfil = $perfilService->listar($id)[0];
                }

                if (is_null($perfil)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam("perfil", $perfil);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@perfil/editar.html.twig", $this->getViewVar());
                }
            }
        }
    }

    public function editar()
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            if (empty($_POST)) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $perfil = new Perfil();
                $perfil->setIdPerfil(trim($_POST['idPerfil']));
                $perfil->setNome(trim($_POST['nome']));
                Sessao::gravaFormulario($_POST);
                $perfilService = new PerfilService();
                if ($perfilService->editar($perfil)) {
                    $this->redirect('/perfil/listar');
                } else {
                    $this->redirect('/perfil/edicao/' . $perfil->getIdPerfil());
                }
            }
        }
    }

    public function exclusao($params)
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $perfilService = new PerfilService();
            $id = $params[0];
            if (ctype_digit($id)) {
                $perfil = $perfilService->listar($id)[0];

                if (is_null($perfil)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam("perfil", $perfil);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@perfil/excluir.html.twig", $this->getViewVar());
                }
            }
        }
    }

    public function excluir()
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            if (empty($_POST['idPerfil'])) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $perfilService = new PerfilService();
                $perfil = new Perfil();
                $perfil->setIdPerfil(trim($_POST['idPerfil']));

                if ($perfilService->excluir($perfil)) {
                    $this->redirect('/perfil/listar');
                } else {
                    $this->redirect('/perfil/exclusao/' . $perfil->getIdPerfil());
                }
            }
        }
    }
}
