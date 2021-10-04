<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Models\Entidades\Usuario;
use App\Models\DAO\UsuarioDAO;
use App\Lib\Log;

class UsuarioValidadorInserir
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Usuario $usuario): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $usuarioDAO = new UsuarioDAO();
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($usuario->getLogin())) {
            $resultadoValidacao->addErro("login", "<b>Login:</b> Este campo não pode ser vazio.");
        }

        if (empty($usuario->getSenha())) {
            $resultadoValidacao->addErro("senha", "<b>Senha:</b> Este campo não pode ser vazio.");
        }

        if (empty($usuario->getConfSenha())) {
            $resultadoValidacao->addErro("confSenha", "<b>Confirmação de senha:</b> Este campo não pode ser vazio.");
        }

        if ($usuario->getConfSenha() != $usuario->getSenha()) {
            $resultadoValidacao->addErro("confSenha", "<b>Confirmação de senha:</b> A senha não confere.");
        }

        if (empty($usuario->getEmail())) {
            $resultadoValidacao->addErro("email", "<b>E-mail:</b> Este campo não pode ser vazio.");
        }

        if ($usuarioDAO->verificaExistenciaEmail($usuario) > 0) {
            $resultadoValidacao->addErro('email', "<b>E-mail:</b> Já existe um usuário com este 'e-mail'");
        }

        if ($usuarioDAO->verificaExistenciaLogin($usuario) > 0) {
            $resultadoValidacao->addErro('login', "<b>Login:</b> Já existe um usuário com este 'login'");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
