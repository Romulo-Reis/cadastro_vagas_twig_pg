<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Perfil;
use App\Models\Validacao\ResultadoValidacao;

class PerfilValidadorGravar
{
    public function validar(Perfil $perfil = null)
    {
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($perfil)) {
            $resultadoValidacao->addErro("perfil", "<b>Perfil:</b> O registro não foi encontrado.");
        }

        if (empty($perfil->getNome())) {
            $resultadoValidacao->addErro("nome", "<b>Nome:</b> Este campo não pode ser vazio");
        }

        return $resultadoValidacao;
    }
}
