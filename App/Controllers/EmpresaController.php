<?php

namespace App\Controllers;

use App\Services\EmpresaService;
use App\Models\Entidades\Empresa;
use App\Models\Validacao\LoginValidadorUsuarioLogado;
use App\Lib\Sessao;
use Exception;
use Twig\Error\SyntaxError;

class EmpresaController extends Controller
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

            $empresaService = new EmpresaService();
            $empresas = $empresaService->listar($id);

            $this->setViewParam("empresas", $empresas);
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render("@empresa/listar.html.twig", $this->getViewVar());
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
            $empresa = new Empresa();
            $empresa->setNomeFantasia($params[0]);
            $empresaService = new EmpresaService();
            $busca = $empresaService->autoComplete($empresa);

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
            $empresa = new Empresa();

            if (Sessao::existeFormulario()) {
                $empresa->setRazaoSocial(Sessao::retornaValorFormulario("razaoSocial"));
                $empresa->setNomeFantasia(Sessao::retornaValorFormulario("nomeFantasia"));
                $empresa->setCNPJ(Sessao::retornaValorFormulario("CNPJ"));
                Sessao::limpaFormulario();
            }

            $this->setViewParam("empresa", $empresa);
            $this->setViewParam("erros", Sessao::retornaErro());
            $this->render("@empresa/cadastro.html.twig", $this->getViewVar());

            Sessao::limpaErro();
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
            if (ctype_digit($id)) {
                if (Sessao::existeFormulario()) {
                    $empresa = new Empresa();
                    $empresa->setIdEmpresa(Sessao::retornaValorFormulario("idEmpresa"));
                    $empresa->setRazaoSocial(Sessao::retornaValorFormulario("razaoSocial"));
                    $empresa->setNomeFantasia(Sessao::retornaValorFormulario("nomeFantasia"));
                    $empresa->setCNPJ(Sessao::retornaValorFormulario("CNPJ"));
                    Sessao::limpaFormulario();
                } else {
                    $empresaService = new EmpresaService();
                    $empresa = $empresaService->listar($id)[0];
                }

                if (is_null($empresa)) {
                    throw new Exception("Página não emcontrada!", 404);
                } else {
                    $this->setViewParam("empresa", $empresa);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@empresa/editar.html.twig", $this->getViewVar());
                }
            } else {
                throw new Exception("Página não emcontrada!", 404);
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
                $empresaService = new EmpresaService();
                $empresa = $empresaService->listar($id)[0];
                if (is_null($empresa)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $vagas = $empresaService->listarVagasVinculadas($empresa);
                    $this->setViewParam("empresa", $empresa);
                    $this->setViewParam("vagas", $vagas);
                    $this->render("@empresa/excluir.html.twig", $this->getViewVar());
                }
            } else {
                throw new Exception("Página não encontrada!", 404);
            }
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
                throw new Exception("Página não encontrada!", 404);
            } else {
                $empresa = new Empresa();
                $empresa->setRazaoSocial(trim($_POST['razaoSocial']));
                $empresa->setNomeFantasia(trim($_POST['nomeFantasia']));
                $empresa->setCNPJ(trim($_POST['CNPJ']));
                Sessao::gravaFormulario($_POST);
                $empresaService = new EmpresaService();
                if ($empresaService->salvar($empresa)) {
                    $this->redirect("/empresa/listar");
                } else {
                    $this->redirect("/empresa/cadastrar");
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
                $empresa = new Empresa();
                $empresa->setIdEmpresa(trim($_POST['idEmpresa']));
                $empresa->setRazaoSocial(trim($_POST['razaoSocial']));
                $empresa->setNomeFantasia(trim($_POST['nomeFantasia']));
                $empresa->setCNPJ(trim($_POST['CNPJ']));
                $empresaService = new EmpresaService();

                if ($empresaService->editar($empresa)) {
                    $this->redirect("/empresa/listar");
                } else {
                    Sessao::gravaFormulario($_POST);
                    $this->redirect("/empresa/edicao/" . $empresa->getIdEmpresa());
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
            if (empty($_POST["idempresa"])) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $empresa = new Empresa();
                $empresa->setIdEmpresa(trim($_POST["idEmpresa"]));

                $empresaService = new EmpresaService();
                if ($empresaService->excluir($empresa)) {
                    $this->redirect("/empresa/listar");
                } else {
                    $this->redirect("/empresa/exclusao/" . $empresa->getIdEmpresa());
                }
            }
        }
    }
}
