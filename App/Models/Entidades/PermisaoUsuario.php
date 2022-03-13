<?php

namespace App\Models\Entidades;

use App\Models\Entidades\Usuario;

class PermissaoUsuario
{
    private $idPermissaoUsuario;
    private $nome;
    private $tipoPermissao;
    private $usuario;
    private $excluido;

    /**
     * Get the value of idPermissaoUsuario
     */
    public function getIdPermissaoUsuario()
    {
        return $this->idPermissaoUsuario;
    }

    /**
     * Set the value of idPermissaoUsuario
     *
     * @return  self
     */
    public function setIdPermissaoUsuario($idPermissaoUsuario)
    {
        $this->idPermissaoUsuario = $idPermissaoUsuario;

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
     * Get the value of tipo
     */
    public function getTipoPermissao()
    {
        return $this->tipoPermissao;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipoPermissao($tipoPermissao)
    {
        $this->tipoPermissao = $tipoPermissao;

        return $this;
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;

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
