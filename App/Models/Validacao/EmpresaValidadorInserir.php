<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Empresa;
use App\Models\DAO\EmpresaDAO;
use App\Models\Validacao\ResultadoValidacao;
use App\Lib\Log;

class EmpresaValidadorInserir
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar(Empresa $empresa): ResultadoValidacao
    {
        $this->log->info("Executando o método validar");
        $empresaDao = new EmpresaDAO();
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($empresa->getRazaoSocial())) {
            $resultadoValidacao->addErro('razaosocial', "<b>Razão Social:</b> Este campo não pode ser vazio");
        }

        if (empty($empresa->getNomeFantasia())) {
            $resultadoValidacao->addErro('nomefantasia', "<b>Nome fantasia:</b> Este campo não pode ser vazio");
        }

        if (empty($empresa->getCNPJ())) {
            $resultadoValidacao->addErro('CNPJ', "<b>CNPJ:</b> Este campo não pode ser vazio");
        }

        if ($empresaDao->verificaExistenciaCNPJ($empresa) > 0) {
            $resultadoValidacao->addErro('CNPJ', "<b>CNPJ: </b> Já existe uma empresa com este CNPJ");
        }

        if ($empresaDao->verificaExistenciaNomeFantasia($empresa) > 0) {
            $resultadoValidacao->addErro('NomeFantasia', "<b>Nome fantasia </b> Já existe uma empresa com este 'nome fantasia'");
        }

        if ($empresaDao->verificaExistenciaRazaoSocial($empresa) > 0) {
            $resultadoValidacao->addErro('RazaoSocial', "<b>Razão social </b> Já existe uma empresa com este 'razão social'");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
