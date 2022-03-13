<?php

namespace App\Models\Entidades;

use App\Models\Entidades\Perfil;

class PermissaoPerfil
{
    private $idPermissaoPerfil;
    private $nome;
    private $tipoPermissao;
    private $perfil;
    private $excluido;

    /**
     * Get the value of idPermissaoPerfil
     */
    public function getidPermissaoPerfil()
    {
        return $this->idPermissaoPerfil;
    }

    /**
     * Set the value of idPermissaoPerfil
     *
     * @return  self
     */
    public function setidPermissaoPerfil($idPermissaoPerfil)
    {
        $this->idPermissaoPerfil = $idPermissaoPerfil;

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
     * Get the value of tipoPermissao
     */
    public function getTipoPermissao()
    {
        return $this->tipoPermissao;
    }

    /**
     * Set the value of tipoPermissao
     *
     * @return  self
     */
    public function setTipoPermissao($tipoPermissao)
    {
        $this->tipoPermissao = $tipoPermissao;

        return $this;
    }

    /**
     * Get the value of $perfil
     */
    public function getPerfil(): Perfil
    {
        return $this->perfil;
    }

    /**
     * Set the value of $perfil
     *
     * @return  self
     */
    public function setPerfil(Perfil $perfil)
    {
        $this->perfil = $perfil;

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
