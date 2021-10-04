<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Hash;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class HashValidadorInserir
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Hash $hash): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($hash->getUsuario())) {
            $resultadoValidacao->addErro('status', "Chave inválida");
        }

        if (empty($hash->getUsuario()->getIdUsuario())) {
            $resultadoValidacao->addErro('idUsuario', 'IdUsusario: Este campo é requerido');
        }

        if (empty($hash->getHash())) {
            $resultadoValidacao->addErro('hash', 'Hash: Este campo é requerido');
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
