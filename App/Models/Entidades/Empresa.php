<?php

namespace App\Models\Entidades;

class Empresa
{

    private $idempresa;
    private $razaosocial;
    private $nomefantasia;
    private $CNPJ;

    public function setIdEmpresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function getIdEmpresa()
    {
        return $this->idempresa;
    }

    public function setRazaoSocial($razaosocial)
    {
        $this->razaosocial = $razaosocial;
    }

    public function getRazaoSocial()
    {
        return $this->razaosocial;
    }

    public function setNomeFantasia($nomefantasia)
    {
        $this->nomefantasia = $nomefantasia;
    }

    public function getNomeFantasia()
    {
        return $this->nomefantasia;
    }

    public function setCNPJ($CNPJ)
    {
        $this->CNPJ = $CNPJ;
    }

    public function getCNPJ()
    {
        return $this->CNPJ;
    }
}
