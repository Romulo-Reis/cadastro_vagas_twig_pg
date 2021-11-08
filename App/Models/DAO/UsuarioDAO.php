<?php

namespace App\Models\DAO;

use App\Models\Entidades\Perfil;
use Exception;
use PDO;
use App\Models\Entidades\Usuario;

class UsuarioDAO extends BaseDAO
{
    public function definirSequenceChavePrimaria()
    {
        $this->setSequence("usuario", "\"usuario_idUsuario_seq\"");
    }

    public function listar($id = null)
    {
        try {
            $SQL = "select u.\"idUsuario\", u.login, u.senha, u.status, u.email, u.\"dataCadastro\", p.\"idPerfil\", p.nome from usuario u inner join perfil p on u.\"FK_idPerfil\" = p.\"idPerfil\" where u.excluido = '0'";
            if ($id) {
                $SQL .= " and u.\"idUsuario\"' = $id";
            }
            $resultado = $this->select($SQL);
            $dataSetUsuarios = $resultado->fetchAll();
            $listaUsuarios = [];
            foreach ($dataSetUsuarios as $dataSetUsuario) {
                $usuario = new Usuario();
                $usuario->setIdUsuario($dataSetUsuario['idUsuario']);
                $usuario->setLogin($dataSetUsuario['login']);
                $usuario->setSenha($dataSetUsuario['senha']);
                $usuario->setPerfil(new Perfil());
                $usuario->getPerfil()->setIdPerfil($dataSetUsuario['idPerfil']);
                $usuario->getPerfil()->setNome($dataSetUsuario['nome']);
                $usuario->setStatus($dataSetUsuario['status']);
                $usuario->setEmail($dataSetUsuario['email']);
                $usuario->setDataCadastro($dataSetUsuario['dataCadastro']);
            }
            return $listaUsuarios;
        } catch (Exception $e) {
            $this->log->alert("Erro na listagem dos dados.", ['exception' => $e]);
            throw new Exception("Erro na listagem dos dados. " . $e->getMessage(), 500);
        }
    }

    public function listarPorLogin(Usuario $usuario)
    {
        $resultado = $this->select("select * from usuario where excluido = '0' and login = '" . $usuario->getLogin() . "'");
        return $resultado->fetchAll(PDO::FETCH_CLASS, Usuario::class);
    }

    public function listarPorEmail(Usuario $usuario)
    {
        $resultado = $this->select("select * from usuario where excluido = '0' and email = '" . $usuario->getEmail() . "'");
        return $resultado->fetchAll(PDO::FETCH_CLASS, Usuario::class);
    }

    public function salvar(Usuario $usuario)
    {
        try {
            $login = $usuario->getLogin();
            $senha = $usuario->getSenha();
            $idPerfil = $usuario->getPerfil()->getIdPerfil();
            $email = $usuario->getEmail();
            $status = $usuario->getStatus();
            $dataCadastro = $usuario->getDataCadastro()->format("Y-m-d H:i:s");

            return $this->insert(
                "usuario",
                ":login, :senha, :FK_idPerfil, :email, :status, :dataCadastro",
                [
                    ":login" => $login,
                    ":senha" => $senha,
                    ":FK_idPerfil" => $idPerfil,
                    ":email" => $email,
                    ":status" => $status,
                    ":dataCadastro" => $dataCadastro
                ]
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function editar(Usuario $usuario)
    {
        try {
            $idUsuario = $usuario->getIdUsuario();
            $login = $usuario->getLogin();
            $idPerfil = $usuario->getPerfil()->getIdPerfil();
            $senha = $usuario->getSenha();
            $email = $usuario->getEmail();
            $status = $usuario->getStatus();

            return $this->update(
                "usuario",
                "login = :login, senha = :senha, \"FK_idPerfil\" = :FK_idPerfil, email = :email, status = :status",
                [
                    ":login" => $login,
                    ":senha" => $senha,
                    ":FK_idPerfil" => $idPerfil,
                    ":email" => $email,
                    ":status" => $status
                ],
                "\"idUsuario\" = $idUsuario"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluir(Usuario $usuario)
    {
        try {
            $idUsuario = $usuario->getIdUsuario();
            $this->delete("usuario", "\"idUsuario\" = $idUsuario");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function excluirComRelacionamento(Usuario $usuario)
    {
        try {
            $idUsuario = $usuario->getIdUsuario();
            $this->delete("hash", "\"FK_idUsuario\" = $idUsuario");
            $this->delete("usuario", "\"idUsuario\" = $idUsuario");
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }


    public function ativar(Usuario $usuario)
    {
        try {
            $idUsuario = $usuario->getIdUsuario();
            $status = $usuario->getStatus();
            $this->update(
                "usuario",
                "status = :status",
                [
                    ":status" => $status
                ],
                "\"idUsuario\" = $idUsuario"
            );
        } catch (Exception $e) {
            $this->log->alert("Erro na gravação dos dados.", ['exception' => $e]);
            throw new Exception("Erro na gravação dos dados.", 500, $e);
        }
    }

    public function verificaExistenciaLogin(Usuario $usuario)
    {
        $resultado = $this->select("SELECT count(*) FROM usuario where excluido = '0' and login = '" . $usuario->getLogin() . "'");
        return $resultado->fetchColumn();
    }

    public function verificaExistenciaEmail(Usuario $usuario)
    {
        $resultado = $this->select("SELECT count(*) FROM usuario where excluido = '0' and email = '" . $usuario->getEmail() . "'");
        return $resultado->fetchColumn();
    }

    public function pegarQuantidadeAtivos()
    {
        $resultado = $this->select("SELECT count(*) from usuario where excluido = '0' and status = '1'");
        return $resultado->fetchColumn();
    }

    public function pegarQuantidadeInativos()
    {
        $resultado = $this->select("SELECT count(*) from usuario where excluido = '0' and status = '0'");
        return $resultado->fetchColumn();
    }
}
