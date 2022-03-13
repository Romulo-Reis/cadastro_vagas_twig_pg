<?php

namespace App\Models\DAO;

use App\Models\Entidades\PermissaoPerfil;
use App\Models\Entidades\Perfil;
use Exception;

class PermissaoPerfilDAO extends BaseDAO
{
    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("permissaogrupo", "permissaogrupo_idPermissaoGrupo_seq");
    }

    public function listar($id)
    {
        try {
            $SQL = "select pg.\"idPermissaoPerfil\", pg.nome as \"nomePermissao\", pg.\"tipoPermissao\", pg.excluido as \"excluidoPermissao\", gu.\"idGrupoUsuario\", gu.nome as \"nomeGrupo\", gu.excluido as \"excluidoGrupo\" from permissaogrupo pg inner join grupousuario gu on pg.\"FK_idGrupo\" = gu.\"idGrupoUsuario\" where pg.excluido = '0'";
            if ($id) {
                $SQL .= " and pg.\"idPermissaoPerfil\" = $id";
            }
            $resultado = $this->select($SQL);
            $dataSetPermissaoPerfis = $resultado->fetchAll();
            $listaPermissoes = [];
            foreach ($dataSetPermissaoPerfis as $dataSetPermissaoPerfil) {
                $permissaoPerfil = new PermissaoPerfil();
                $permissaoPerfil->setIdPermissaoPerfil($dataSetPermissaoPerfil['idPermissaoPerfil']);
                $permissaoPerfil->setNome($dataSetPermissaoPerfil['nomePermissao']);
                $permissaoPerfil->setTipoPermissao($dataSetPermissaoPerfil['tipoPermissao']);
                $permissaoPerfil->setExcluido($dataSetPermissaoPerfil['excluidoPermissao']);
                $permissaoPerfil->setPerfil(new Perfil());
                $permissaoPerfil->getPerfil()->setIdPerfil($dataSetPermissaoPerfil['idPerfil']);
                $permissaoPerfil->getPerfil()->setNome($dataSetPermissaoPerfil['nomeGrupo']);
                $permissaoPerfil->getPerfil()->setExcluido($dataSetPermissaoPerfil['excluidoGrupo']);
                $listaPermissoes[] = $permissaoPerfil;
            }

            return $listaPermissoes;
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Erro na listagem dos dados. " . $e->getMessage(), 500);
        }
    }

    public function salvar(PermissaoPerfil $permissaoPerfil)
    {
        try {
            $nome = $permissaoPerfil->getNome();
            $tipoPermissao = $permissaoPerfil->getTipoPermissao();
            $idPerfil = $permissaoPerfil->getPerfil()->getIdPerfil();
            return $this->insert(
                "permissaoperfil",
                ":nome, :tipoPermissao, :FK_idPerfil",
                [
                    ":nome" => $nome,
                    ":tipoPermissao" => $tipoPermissao,
                    ":idPerfil" => $idPerfil
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(PermissaoPerfil $permissaoPerfil)
    {
        try {
            $id = $permissaoPerfil->getidPermissaoPerfil();
            $nome = $permissaoPerfil->getNome();
            $tipoPermissao = $permissaoPerfil->getTipoPermissao();
            $idPerfil = $permissaoPerfil->getPerfil()->getIdPerfil();

            return $this->update(
                "permissaoperfil",
                "nome = :nome, tipoPermissao = :tipoPermissao, FK_idPerfil = :FK_idPerfil",
                [
                    ":nome" => $nome,
                    ":tipoPermissao" => $tipoPermissao,
                    ":idPerfil" => $idPerfil
                ],
                "\"idPermissaoPerfil\" = $id"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluir(PermissaoPerfil $permissaoPerfil)
    {
        try {
            $idPermissaoPerfil = $permissaoPerfil->getIdPermissaoPerfil();
            $this->delete("permissaoperfil", "\"idPermissaoPerfil\" = $idPermissaoPerfil");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }
}
