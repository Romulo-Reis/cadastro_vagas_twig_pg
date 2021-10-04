<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Tecnologia;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class TecnologiaValidadorInserir
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Tecnologia $tecnologia): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($tecnologia->getTecnologia())) {
            $resultadoValidacao->addErro('tecnologia', "<b>Tecnologia:</b> Este campo não pode ser vazio");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
