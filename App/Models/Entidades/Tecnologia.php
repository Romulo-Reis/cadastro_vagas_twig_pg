<?php

namespace App\Models\Entidades;

class Tecnologia
{

    private $idtecnologia;
    private $tecnologia;
    private $excluido;

    public function setIdTecnologia($idtecnologia)
    {
        $this->idtecnologia = $idtecnologia;
    }

    public function getIdTecnologia()
    {
        return $this->idtecnologia;
    }

    public function setTecnologia($tecnologia)
    {
        $this->tecnologia = $tecnologia;
    }

    public function getTecnologia()
    {
        return $this->tecnologia;
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
