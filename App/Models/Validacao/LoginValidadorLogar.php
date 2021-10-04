<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Models\Entidades\Usuario;
use App\Lib\Log;

class LoginValidadorLogar
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Usuario $usuario = null, $senha)
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($usuario)) {
            $resultadoValidacao->addErro("usuario", "<b>Usuário:</b> O usuário não foi encontrado.");
        } else {
            if ($usuario->getStatus() == 0) {
                $resultadoValidacao->addErro("status", "<b>Status:</b> O usuário não está habilitado.");
            }

            if ($usuario->getSenha() != $senha) {
                $resultadoValidacao->addErro('login', "Login ou senha inválidos");
            }
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
