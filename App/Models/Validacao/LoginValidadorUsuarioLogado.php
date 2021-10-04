<?php

namespace App\Models\Validacao;

use App\Models\Validacao\ResultadoValidacao;
use App\Services\LoginService;
use App\Lib\Log;

class LoginValidadorUsuarioLogado
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function validar()
    {
        $this->log->info("Executando o método validar");
        $resultadoValidacao = new ResultadoValidacao();
        $loginService = new LoginService();
        $usuarioLogado = $loginService->retornarUsuarioLogado();

        if (empty($usuarioLogado)) {
            $resultadoValidacao->addErro('usuarioLogado', "Não foi possível atender a solicitação, pois o usuário não está autenticado no sistema.");
        }

        if ($resultadoValidacao->getErros()) {
            $this->log->notice("A validação encontrou erros.", ['erros' => $resultadoValidacao->getErros()]);
        }

        return $resultadoValidacao;
    }
}
