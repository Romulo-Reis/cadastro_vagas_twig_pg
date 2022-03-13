<?php

namespace App\Models\DAO;

use App\Models\Entidades\PermissaoUsuario;
use App\Models\Entidades\Usuario;
use Exception;

class PermissaoUsuarioDAO extends BaseDAO
{
    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("permissaousuario", "permissaousuario_idPermissaoUsuario_seq");
    }

    public function listar($id)
    {
        try {
            $SQL = "select pu.\"idPermissaoUsuario\", pu.nome, pu.\"tipoPermissao\", pu.excluido as \"excluidoPermissao\", u.\"idUsuario\", u.login, u.email, u.senha, u.status, u.\"dataCadastro\", u.excluido as \"excluidoUsuario\" from permissaousuario pu inner join usuario u on pu.\"FK_idUsuario\" = u.\"idUsuario\" where pu.excluido = '0'";
            if ($id) {
                $SQL .= " and pu.\"idPermissaoUsuario\" = $id";
            }
            $resultado = $this->select($SQL);
            $dataSetPermissaoUsuarios = $resultado->fetchAll();
            $listaPermissoes = [];
            foreach ($dataSetPermissaoUsuarios as $dataSetPermissaoUsuario) {
                $permissaoUsuario = new PermissaoUsuario();
                $permissaoUsuario->setIdPermissaoUsuario($dataSetPermissaoUsuario['idPermissaoUsuario']);
                $permissaoUsuario->setNome($dataSetPermissaoUsuario['nome']);
                $permissaoUsuario->setTipoPermissao($dataSetPermissaoUsuario['tipoPermissao']);
                $permissaoUsuario->setExcluido($dataSetPermissaoUsuario['excluidoPermissao']);
                $permissaoUsuario->setUsuario(new Usuario());
                $permissaoUsuario->getUsuario()->setIdUsuario($dataSetPermissaoUsuario['idUsuario']);
                $permissaoUsuario->getUsuario()->setLogin($dataSetPermissaoUsuario['login']);
                $permissaoUsuario->getUsuario()->setSenha($dataSetPermissaoUsuario['senha']);
                $permissaoUsuario->getUsuario()->setSenha($dataSetPermissaoUsuario['status']);
                $permissaoUsuario->getUsuario()->setExcluido($dataSetPermissaoUsuario['excluidoUsuario']);
                $listaPermissoes[] = $permissaoUsuario;
            }
            return $listaPermissoes;
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Erro na listagem dos dados. " . $e->getMessage(), 500);
        }
    }

    public function salvar(PermissaoUsuario $permissaoUsuario)
    {
        try {
            $nome = $permissaoUsuario->getNome();
            $tipoPermissao = $permissaoUsuario->getTipoPermissao();
            $idUsuario = $permissaoUsuario->getUsuario()->getIdUsuario();
            return $this->insert(
                "permissaousuario",
                ":nome, :tipoPermissao, :FK_idUsuario",
                [
                    ":nome" => $nome,
                    ":tipoPermissao" => $tipoPermissao,
                    ":FK_idUsuario" => $idUsuario
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(PermissaoUsuario $permissaoUsuario)
    {
        try {
            $idPermissaoUsuario = $permissaoUsuario->getIdPermissaoUsuario();
            $nome = $permissaoUsuario->getNome();
            $tipoPermissao = $permissaoUsuario->getTipoPermissao();
            $idUsuario = $permissaoUsuario->getUsuario()->getIdUsuario();
            return $this->update(
                "permissaousuario",
                ":nome, :tipoPermissao, :FK_idUsuario",
                [
                    ":nome" => $nome,
                    ":tipoPermissao" => $tipoPermissao,
                    ":FK_idUsuario" => $idUsuario
                ],
                "\"idPermissaoUsuario\" = $idPermissaoUsuario"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluir(PermissaoUsuario $permissaoUsuario)
    {
        try {
            $idPermissaoUsuario = $permissaoUsuario->getIdPermissaoUsuario();
            $this->delete("permissaousuario", "\"idPermissaoUsuario\" = $idPermissaoUsuario");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }
}
