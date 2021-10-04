<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Vaga;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class VagaValidador
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Vaga $vaga): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($vaga->getTitulo())) {
            $resultadoValidacao->addErro('titulo', "<b>Título:</b> Este campo não pode ser vazio");
        }

        if (empty($vaga->getEmpresa())) {
            $resultadoValidacao->addErro('empresa', "<b>Empresa:</b> Este campo não pode ser vazio");
        }

        if (empty($vaga->getDescricao())) {
            $resultadoValidacao->addErro('descricao', "<b>Descrição:</b> Este campo não pode ser vazio");
        }

        if (empty($vaga->getTecnologias())) {
            $resultadoValidacao->addErro('tecnologias', "<b>Tecnologias:</b> É necessário no mínimo 1 (uma) tecnologia");
        }

        if (count($vaga->getTecnologias()) > 3) {
            $resultadoValidacao->addErro('tecnologias', "<b>Tecnologias:</b> Não é permitido mais que 3 tecnologias por vaga");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
