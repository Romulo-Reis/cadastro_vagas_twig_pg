<?php

namespace App\Controllers;

use App\Services\TecnologiaService;
use App\Models\Entidades\Tecnologia;
use App\Models\Validacao\LoginValidadorUsuarioLogado;
use App\Lib\Sessao;
use Exception;

class TecnologiaController extends Controller
{

    public function listar($params)
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();

        if ($resultadoValidacao->getErros()) {
            Sessao::gravaErro($resultadoValidacao->getErros());
            $this->redirect("/login");
        } else {
            $id = $params[0];
            $tecnologiaService = new TecnologiaService();
            $tecnologias = $tecnologiaService->listar($id);
            $this->setViewParam("tecnologias", $tecnologias);
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render("@tecnologia/listar.html.twig", $this->getViewVar());
            Sessao::limpaMensagem();
        }
    }

    public function autoComplete($params)
    {
        $loginValidador = new LoginValidadorUsuarioLogado();
        $resultadoValidacao = $loginValidador->validar();
        if ($resultadoValidacao->getErros()) {
            http_response_code(403);
            echo "";
        } else {
            $tecnologia = new Tecnologia();
            $tecnologia->setTecnologia($params[0]);
            $tecnologiaService = new TecnologiaService();
            $busca = $tecnologiaService->autoComplete($tecnologia);

            echo $busca;
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
            $tecnologia = new Tecnologia();

            if (Sessao::existeFormulario()) {
                $tecnologia->setTecnologia(Sessao::retornaValorFormulario("tecnologia"));
                Sessao::limpaFormulario();
            }
            $this->setViewParam('tecnologia', $tecnologia);
            $this->setViewParam("erros", Sessao::retornaErro());
            $this->render('@tecnologia/cadastro.html.twig', $this->getViewVar());
            Sessao::limpaErro();
        }
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
                throw new Exception("Página não encontrada.", 404);
            } else {
                $tecnologia = new Tecnologia();
                $tecnologia->setTecnologia(trim($_POST['tecnologia']));
                Sessao::gravaFormulario($_POST);
                $tecnologiaService = new TecnologiaService();
                if ($tecnologiaService->salvar($tecnologia)) {
                    $this->redirect('/tecnologia/listar');
                } else {
                    $this->redirect('/tecnologia/cadastrar');
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
            $tecnologiaService = new TecnologiaService();
            $tecnologia = new Tecnologia();
            $id = $params[0];
            if (ctype_digit($id)) {
                if (Sessao::existeFormulario()) {
                    $tecnologia->setIdTecnologia(Sessao::retornaValorFormulario('idTecnologia'));
                    $tecnologia->setTecnologia(Sessao::retornaValorFormulario('tecnologia'));
                    Sessao::limpaFormulario();
                } else {
                    $tecnologia = $tecnologiaService->listar($id)[0];
                }

                if (is_null($tecnologia)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam('tecnologia', $tecnologia);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render('@tecnologia/editar.html.twig', $this->getViewVar());
                    Sessao::limpaErro();
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
            $tecnologiaService = new TecnologiaService();
            if (empty($_POST)) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $novaTecnologia = new Tecnologia();
                $novaTecnologia->setIdTecnologia($_POST['idTecnologia']);
                $novaTecnologia->setTecnologia($_POST['tecnologia']);

                Sessao::gravaFormulario($_POST);
                if ($tecnologiaService->editar($novaTecnologia)) {
                    $this->redirect("/tecnologia/listar");
                } else {
                    $this->redirect("/tecnologia/editar/" . $novaTecnologia->getIdTecnologia());
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
            $id = $params[0];

            if (ctype_digit($id)) {
                $tecnologiaService = new TecnologiaService();
                $tecnologia = $tecnologiaService->listar($id)[0];
                if (is_null($tecnologia)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam("tecnologia", $tecnologia);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@tecnologia/excluir.html.twig", $this->getViewVar());
                    Sessao::limpaErro();
                }
            } else {
                throw new Exception("Página não encontrada!", 404);
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
            if (empty($_POST["idTecnologia"])) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $tecnologia = new Tecnologia();
                $tecnologia->setIdTecnologia(trim($_POST["idTecnologia"]));
                $tecnologiaService = new TecnologiaService();

                if ($tecnologiaService->excluir($tecnologia)) {
                    $this->redirect("/tecnologia/listar");
                } else {
                    $this->redirect("/tecnologia/exclusao/" . $tecnologia->getIdTecnologia());
                }
            }
        }
    }
}
