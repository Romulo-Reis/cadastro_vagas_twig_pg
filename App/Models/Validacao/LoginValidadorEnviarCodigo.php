<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class LoginValidadorEnviarCodigo
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar($email)
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();
        if (empty($email)) {
            $resultadoValidacao->addErro('email', "<b>E-mail:</b> Este campo não pode ser vazio");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
