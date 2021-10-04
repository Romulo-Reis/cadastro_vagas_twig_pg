<?php

namespace App\Models\Entidades;

class Tecnologia
{

    private $idtecnologia;
    private $tecnologia;

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
}
