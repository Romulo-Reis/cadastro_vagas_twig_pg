<?php

namespace App\Services;

use App\Services\UsuarioService;
use App\Models\Entidades\Usuario;
use App\Models\Validacao\LoginValidadorAutenticar;
use App\Lib\Criptografia;
use App\Lib\Email;
use App\Lib\Sessao;
use App\Lib\Log;
use App\Models\Validacao\LoginValidadorEnviarCodigo;
use App\Models\Validacao\LoginValidadorLogar;
use Exception;

class LoginService
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function autenticar($login, $senha)
    {
        $this->log->info("Executando o método autenticar");
        $loginValidador = new LoginValidadorAutenticar();
        $loginValidadorLogar = new LoginValidadorLogar();
        $resultadoValidacao = $loginValidador->validar($login, $senha);
        if (empty($resultadoValidacao->getErros())) {
            $senhaCriptografada = Criptografia::criptografar($senha);
            $usuarioService = new UsuarioService();
            $usuario = $usuarioService->listarPorLogin($login)[0];
            $resultadoValidacao = $loginValidadorLogar->validar($usuario, $senhaCriptografada);
            if (empty($resultadoValidacao->getErros())) {
                $this->logar($usuario);
                Sessao::gravaMensagem("Usuário logado com sucesso.");
                return true;
            }
        }
        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
        }

        return false;
    }

    public function logar(Usuario $usuario)
    {
        $this->log->info("Executando o método logar");
        $usuarioLogado = $this->retornarUsuarioLogado();
        if (empty($usuarioLogado)) {
            Sessao::gravarIdUsuarioLogado($usuario);
        } else {
            throw new Exception("Não foi possível logar no sistema, pois já existe um usuário logado.", 403);
        }
    }

    public function deslogar()
    {
        $this->log->info("Executando o método deslogar");
        $usuarioLogado = $this->retornarUsuarioLogado();
        if (empty($usuarioLogado)) {
            throw new Exception("Não foi possível deslogar do sistema, pois não tinha usuário logado.", 403);
        }
        Sessao::limparIdUsuarioLogado();
    }

    public function retornarUsuarioLogado()
    {
        $this->log->info("Executando o método retornarUsuarioLogado");
        $usuarioService = new UsuarioService();
        $idUsuario = Sessao::retornaIdUsuarioLogado();
        $usuario = null;
        if (!empty($idUsuario)) {
            $usuario = $usuarioService->listar($idUsuario)[0];
        }
        return $usuario;
    }

    public function recuperarUsuarioPorEmail($email)
    {
        $this->log->info("Executando o método recuperarUsuarioPorEmail");
        $usuarioService = new UsuarioService();
        $usuario = null;
        if (!empty($email)) {
            $usuario = $usuarioService->listarPorEmail($email)[0];
        }
        return $usuario;
    }

    public function criarConta(Usuario $usuario)
    {
        $this->log->info("Executando o método criarConta");
        $usuarioService = new UsuarioService();
        if ($usuarioService->salvar($usuario)) {
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Conta criada com sucesso.");
            return true;
        }
        return false;
    }

    public function enviarCodigo($email)
    {
        $this->log->info("Executando o método enviarCodigo");
        $loginValidador = new LoginValidadorEnviarCodigo();
        $resultadoValidacao = $loginValidador->validar($email);
        if (empty($resultadoValidacao->getErros())) {
            $usuario = $this->recuperarUsuarioPorEmail($email);
            if (!empty($usuario)) {
                $codigo = $this->gerarCodigoRecuperacao();
                Email::enviarEmailComCodigoRecuperacao($usuario, $codigo);
                return true;
            } else {
                $resultadoValidacao->addErro('email', "<b>E-mail:</b>E-mail inválido.");
            }
        }

        if ($resultadoValidacao->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacao->getErros());
        }
        return false;
    }

    public function gerarCodigoRecuperacao()
    {
        $this->log->info("Executando o método gerarCodigoRecuperacao");
        $codigo = [];
        $i = 0;
        $n = rand(1, 1000);
        while ($i < 30) {
            if (!in_array($n, $codigo)) {
                $codigo[] = $n;
            }
            $i++;
        }
        return implode("", $codigo);
    }
}
