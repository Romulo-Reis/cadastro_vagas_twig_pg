<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Usuario;
use App\Models\DAO\UsuarioDAO;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class UsuarioValidadorEditar
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Usuario $novoUsuario, Usuario $usuarioCadastrado): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $usuarioDAO = new UsuarioDAO();
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($novoUsuario->getLogin())) {
            $resultadoValidacao->addErro("login", "<b>Login:</b> Este campo não pode ser vazio.");
        }

        if (empty($novoUsuario->getSenha())) {
            $resultadoValidacao->addErro("senha", "<b>Senha:</b> Este campo não pode ser vazio.");
        }

        if (empty($novoUsuario->getConfSenha())) {
            $resultadoValidacao->addErro("confSenha", "<b>Confirmação de senha:</b> Este campo não pode ser vazio.");
        }

        if ($novoUsuario->getConfSenha() != $novoUsuario->getSenha()) {
            $resultadoValidacao->addErro("confSenha", "<b>Confirmação de senha:</b> A senha não confere.");
        }

        if (empty($novoUsuario->getEmail())) {
            $resultadoValidacao->addErro("email", "<b>E-mail:</b> Este campo não pode ser vazio.");
        }

        if ($usuarioDAO->verificaExistenciaEmail($novoUsuario) > 0 && ($novoUsuario->getEmail() != $usuarioCadastrado->getEmail())) {
            $resultadoValidacao->addErro('email', "<b>E-mail:</b> Já existe um usuário com este 'e-mail'");
        }

        if ($usuarioDAO->verificaExistenciaLogin($novoUsuario) > 0 && ($novoUsuario->getLogin() != $usuarioCadastrado->getLogin())) {
            $resultadoValidacao->addErro('login', "<b>Login:</b> Já existe um usuário com este 'login'");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
