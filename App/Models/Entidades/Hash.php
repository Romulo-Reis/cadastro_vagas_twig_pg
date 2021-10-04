<?php

namespace App\Models\Entidades;

use App\Models\Entidades\Usuario;
use App\Lib\DataUtil;
use DateTime;

class Hash
{
    private $idHash;
    private $hash;
    private $status;
    private $dataCadastro;
    private $usuario;

    /**
     * Get the value of idHash
     */
    public function getIdHash()
    {
        return $this->idHash;
    }

    /**
     * Set the value of idHash
     *
     * @return  self
     */
    public function setIdHash($idHash)
    {
        $this->idHash = $idHash;

        return $this;
    }

    /**
     * Get the value of hash
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the value of hash
     *
     * @return  self
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of dataCadastro
     */
    public function getDataCadastro()
    {
        return new DateTime($this->dataCadastro, DataUtil::getFusoHorario('RJ'));
    }

    /**
     * Set the value of dataCadastro
     *
     * @return  self
     */
    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;

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
}
