<?php

namespace App\Models\Entidades;

use App\Models\Entidades\Empresa;
use App\Models\Entidades\Tecnologia;

class Vaga
{
    private $idvaga;
    private $titulo;
    private $tecnologias = [];
    private $descricao;
    private $empresa;
    private $excluido;

    public function setIdVaga($idvaga)
    {
        $this->idvaga = $idvaga;
    }

    public function getIdVaga()
    {
        return $this->idvaga;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTecnologias(array $tecnologias)
    {
        $this->tecnologias = $tecnologias;
    }

    public function getTecnologias(): array
    {
        return $this->tecnologias;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setEmpresa(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    public function getEmpresa(): Empresa
    {
        return $this->empresa;
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
