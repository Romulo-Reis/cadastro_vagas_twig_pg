<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Models\Entidades\Hash;
use App\Lib\Log;
use DateTime;

class HashValidadorAtivacao
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Hash $hash)
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if ($hash->getStatus() === 1) {
            $resultadoValidacao->addErro('status', "A chave já está ativa");
        }

        $dataAtual = new DateTime('now');
        $diferenca = $hash->getDataCadastro()->diff($dataAtual);

        if (($diferenca->h + ($diferenca->days * 24)) > 72) {
            $resultadoValidacao->addErro('status', "Chave expirada");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
