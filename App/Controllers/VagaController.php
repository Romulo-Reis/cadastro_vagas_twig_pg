<?php

namespace App\Controllers;

use App\Services\VagaService;
use App\Services\EmpresaService;
use App\Services\TecnologiaService;
use App\Models\Entidades\Vaga;
use App\Models\Entidades\Empresa;
use App\Models\Validacao\LoginValidadorUsuarioLogado;
use App\Lib\Sessao;
use Exception;

class VagaController extends Controller
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
            $vagaService = new VagaService();
            $vagas = $vagaService->listar($id);
            $this->setViewParam("vagas", $vagas);
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render("@vaga/listar.html.twig", $this->getViewVar());
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
            $vaga = new Vaga();

            if (Sessao::existeFormulario()) {
                $vaga->setTitulo(Sessao::retornaValorFormulario("titulo"));
                $vaga->setDescricao(Sessao::retornaValorFormulario("descricao"));

                $idEmpresa = Sessao::retornaValorFormulario('empresa');
                $empresaService = new EmpresaService();
                $empresa = $empresaService->listar($idEmpresa)[0];
                $vaga->setEmpresa($empresa);

                $tecnologias = Sessao::retornaValorFormulario('tecnologias');

                if (empty($tecnologias)) {
                    $vaga->setTecnologias(array());
                } else {
                    $tecnologiaService = new TecnologiaService();
                    $tecnologias = $tecnologiaService->preencheTecnologias($tecnologias);
                    $vaga->setTecnologias($tecnologias);
                }
                Sessao::limpaFormulario();
            } else {
                $vaga = new Vaga();
                $vaga->setEmpresa(new Empresa());
                $vaga->setTecnologias(array());
            }
            $this->setViewParam("vaga", $vaga);
            $this->setViewParam("erros", Sessao::retornaErro());
            $this->render("@vaga/cadastro.html.twig", $this->getViewVar());
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
            }
            $vaga = new Vaga();
            $vaga->setTitulo(trim($_POST['titulo']));
            $vaga->setDescricao(trim($_POST['descricao']));
            if (ctype_digit($_POST['empresa'])) {
                $empresaService = new EmpresaService();
                $empresa = $empresaService->listar($_POST['empresa'])[0];
            } else {
                throw new Exception("Erro ao cadastrar a vaga, pois o campo empresa não é um dígito. O valor informado pelo usuário é " . $_POST['empresa'] . " ", 500);
            }

            if (is_null($empresa)) {
                throw new Exception("Erro ao cadastrar a vaga, pois a variavel empresa está vazia", 500);
            } else {
                $vaga->setEmpresa($empresa);
                $tecnologiaService = new TecnologiaService();
                $tecnologias = $tecnologiaService->preencheTecnologias($_POST['tecnologias']);

                $vaga->setTecnologias($tecnologias);
                Sessao::gravaFormulario($_POST);

                $vagaService = new VagaService();

                if ($vagaService->salvar($vaga)) {
                    $this->redirect('/vaga/listar');
                } else {
                    $this->redirect('/vaga/cadastrar');
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
            $id = $params[0];
            $tecnologiaService = new TecnologiaService();

            if (ctype_digit($id)) {
                if (Sessao::existeFormulario()) {
                    $vaga = new Vaga();
                    $vaga->setIdVaga(Sessao::retornaValorFormulario('idvaga'));
                    $vaga->setTitulo(Sessao::retornaValorFormulario('titulo'));
                    $vaga->setDescricao(Sessao::retornaValorFormulario('descricao'));

                    $idEmpresa = Sessao::retornaValorFormulario('empresa');
                    $empresaService = new EmpresaService();
                    $empresa = $empresaService->listar($idEmpresa)[0];
                    $vaga->setEmpresa($empresa);

                    $tecnologias = Sessao::retornaValorFormulario('tecnologias');
                    if (empty($tecnologias)) {
                        $vaga->setTecnologias(array());
                    } else {
                        $tecnologiaService = new TecnologiaService();
                        $tecnologias = $tecnologiaService->preencheTecnologias($tecnologias);
                        $vaga->setTecnologias($tecnologias);
                    }
                    Sessao::limpaFormulario();
                } else {
                    $vagaService = new VagaService();
                    $vaga = $vagaService->listar($id)[0];
                    $vaga->setTecnologias($tecnologiaService->listarPorVaga($vaga));
                }

                if (is_null($vaga)) {
                    throw new Exception("Página não encontrada.", 404);
                } else {
                    $this->setViewParam('vaga', $vaga);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render('@vaga/editar.html.twig', $this->getViewVar());
                    Sessao::limpaErro();
                }
            } else {
                throw new Exception("Página não encontrada.", 404);
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
                throw new Exception("Página não encontrada.", 404);
            } else {
                $vaga = new Vaga();
                $vaga->setIdVaga($_POST['idvaga']);
                $vaga->setTitulo($_POST['titulo']);
                $vaga->setDescricao($_POST['descricao']);
                $empresaService = new EmpresaService();
                $empresa = $empresaService->listar($_POST['empresa'])[0];
                $vaga->setEmpresa($empresa);
                $tecnologiaService = new TecnologiaService();
                $tecnologias = $tecnologiaService->preencheTecnologias($_POST['tecnologias']);
                $vaga->setTecnologias($tecnologias);
                Sessao::gravaFormulario($_POST);

                $vagaService = new VagaService();
                if ($vagaService->editar($vaga)) {
                    $this->redirect('/vaga/listar');
                } else {
                    $this->redirect('/vaga/edicao/' . $vaga->getIdVaga());
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
                $vagaService = new VagaService();
                $vaga = $vagaService->listar($id)[0];
                if (is_null($vaga)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam("vaga", $vaga);

                    $tecnologiaService = new TecnologiaService();
                    $tecnologias = $tecnologiaService->listarPorVaga($vaga);
                    $this->setViewParam("tecnologias", $tecnologias);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@vaga/excluir.html.twig", $this->getViewVar());
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
            if (empty($_POST)) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $vaga = new Vaga();
                $vaga->setIdVaga($_POST["idvaga"]);
                $vagaService = new VagaService();

                if ($vagaService->excluir($vaga)) {
                    $this->redirect("/vaga/listar");
                } else {
                    $this->redirect("/vaga/exclusao/" . $vaga->getIdVaga());
                }
            }
        }
    }
}
