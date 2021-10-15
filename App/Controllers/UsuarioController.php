<?php

namespace App\Controllers;

use App\Lib\Criptografia;
use App\Services\UsuarioService;
use App\Models\Entidades\Usuario;
use App\Models\Validacao\LoginValidadorUsuarioLogado;
use App\Lib\DataUtil;
use App\Lib\Sessao;
use Exception;

class UsuarioController extends Controller
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
            $usuarioService = new UsuarioService();
            $usuarios = $usuarioService->listar($id);
            $this->setViewParam("usuarios", $usuarios);
            $this->setViewParam("mensagem", Sessao::retornaMensagem());
            $this->render("@usuario/listar.html.twig", $this->getViewVar());
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
            $this->render("@usuario/cadastro.html.twig", $this->getViewVar());
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
                $usuario = new Usuario();
                $usuario->setLogin($_POST['login']);
                $usuario->setSenha($_POST['senha']);
                $usuario->setConfSenha($_POST['confSenha']);
                $usuario->setEmail($_POST['email']);
                $usuario->setStatus(false);
                $dataAtual = DataUtil::getDataAtual();
                $usuario->setDataCadastro(DataUtil::getDataFormatoATOM($dataAtual));
                Sessao::gravaFormulario($_POST);
                $usuarioService = new UsuarioService();
                if ($usuarioService->salvar($usuario)) {
                    $this->redirect("/usuario/listar");
                } else {
                    $this->redirect("/usuario/cadastrar");
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
            $usuario = new Usuario();
            $usuarioService = new UsuarioService();
            if (ctype_digit($id)) {
                if (Sessao::existeFormulario()) {
                    $usuario->setIdUsuario(Sessao::retornaValorFormulario("idUsuario"));
                    $usuario->setLogin(Sessao::retornaValorFormulario("login"));
                    $usuario->setEmail(Sessao::retornaValorFormulario("email"));
                    $usuario->setSenha(Sessao::retornaValorFormulario("senha"));
                    $usuario->setConfSenha(Sessao::retornaValorFormulario("confSenha"));
                    $usuario->setStatus(0);
                    Sessao::limpaFormulario();
                } else {
                    $usuario = $usuarioService->listar($id)[0];
                    $usuario->setConfSenha($usuario->getSenha());
                }

                if (is_null($usuario)) {
                    throw new Exception("Página não encontrada!", 404);
                } else {
                    $this->setViewParam("usuario", $usuario);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@usuario/editar.html.twig", $this->getViewVar());
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
            if (empty($_POST)) {
                throw new Exception("Página não encontrada!", 404);
            } else {
                $usuario = new Usuario();
                $usuario->setIdUsuario($_POST['idUsuario']);
                $usuario->setLogin($_POST['login']);
                $usuario->setSenha($_POST['senha']);
                $usuario->setConfSenha($_POST['confSenha']);
                $usuario->setEmail($_POST['email']);
                $usuario->setStatus($_POST['status']);
                Sessao::gravaFormulario($_POST);
                $usuarioService = new UsuarioService();
                if ($usuarioService->editar($usuario)) {
                    $this->redirect("/usuario/listar");
                } else {
                    $this->redirect("/usuario/edicao/" . $usuario->getIdUsuario());
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
                $usuarioService = new UsuarioService();
                $usuario = $usuarioService->listar($id)[0];
                if (is_null($usuario)) {
                    throw new Exception("Página não encontrada.", 404);
                } else {
                    $this->setViewParam("usuario", $usuario);
                    $this->setViewParam("erros", Sessao::retornaErro());
                    $this->render("@usuario/excluir.html.twig", $this->getViewVar());
                    Sessao::limpaErro();
                }
            } else {
                throw new Exception("Página não encontrada.", 404);
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
            if (empty($_POST["idUsuario"])) {
                throw new Exception("Página não encontrada.", 404);
            } else {
                $usuario = new Usuario();
                $usuarioService = new UsuarioService();
                $usuario->setIdUsuario(trim($_POST['idUsuario']));
                if ($usuarioService->excluir($usuario)) {
                    $this->redirect("/usuario/listar");
                } else {
                    $this->redirect("/usuario/exclusao/" . $usuario->getIdUsuario());
                }
            }
        }
    }

    public function ativacao($params)
    {
        $usuarioService = new UsuarioService();
        if (empty($params)) {
            throw new Exception("Use o link na mensagem enviada para o seu e-mail para ativar o seu cadastro.");
        }
        $email = Criptografia::descriptografar($params[0]);
        $usuario = $usuarioService->listarPorEmail($email)[0];

        if (empty($usuario)) {
            Sessao::limpaFormulario();
            throw new Exception("Usuário não encontrado!", 404);
        }

        $usuarioService->ativacao($usuario, $params[0]);
        $this->redirect("/login");
    }
}
