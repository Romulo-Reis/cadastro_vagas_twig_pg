<?php

namespace App\Models\Entidades;

class Perfil
{
    private $idPerfil;
    private $nome;
    private $excluido;


    /**
     * Get the value of idPerfil
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * Set the value of idPErfil
     *
     * @return  self
     */
    public function setIdPerfil($idPerfil)
    {
        $this->$idPerfil = $idPerfil;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of excluido
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * Set the value of excluido
     *
     * @return  self
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;

        return $this;
    }
}
