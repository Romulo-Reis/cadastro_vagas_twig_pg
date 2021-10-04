<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class LoginValidadorAutenticar
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar($login, $senha): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($login)) {
            $resultadoValidacao->addErro('login', "<b>Login:</b> Este campo não pode ser vazio");
        }

        if (empty($senha)) {
            $resultadoValidacao->addErro('senha', "<b>Senha:</b> Este campo não pode ser vazio");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
