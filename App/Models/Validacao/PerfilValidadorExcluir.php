<?php

namespace App\Models\Validacao;

use App\Models\Entidades\Perfil;
use App\Models\DAO\PerfilDAO;
use App\Models\Validacao\ResultadoValidacao;

class PerfilValidadorExcluir
{
    public function validar(Perfil $perfil)
    {
        $perfilDAO = new PerfilDAO();
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($perfil)) {
            $resultadoValidacao->addErro("Perfil", "<b>Perfil:</b> Perfil não encontrado.");
        }

        if ($perfilDAO->varificaExistenciaUsuarios($perfil) > 0) {
            $resultadoValidacao->addErro("Usuario", "<b>Usuário:</b> Existem usuários vinculados a este perfil.");
        }

        return $resultadoValidacao;
    }
}
