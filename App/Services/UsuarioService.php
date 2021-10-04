<?php

namespace App\Services;

use App\Models\Entidades\Usuario;
use App\Models\DAO\UsuarioDAO;
use App\Models\DAO\HashDAO;
use App\Models\Validacao\UsuarioValidadorInserir;
use App\Models\Validacao\UsuarioValidadorEditar;
use App\Models\Entidades\Hash;
use App\Models\Validacao\HashValidadorInserir;
use App\Models\Validacao\HashValidadorAtivacao;
use App\Lib\Transacao;
use App\Lib\DataUtil;
use App\Lib\Criptografia;
use App\Lib\Sessao;
use App\Lib\Email;
use App\Lib\Log;
use Exception;

class UsuarioService
{
    private $log;

    public function __construct()
    {
        $this->log = new Log(get_class($this));
    }

    public function listar($idUsuario = null)
    {
        $this->log->info("Executando o método listar");
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->listar($idUsuario);
    }

    public function listarPorLogin($login)
    {
        $this->log->info("Executando o método listarPorLogin");
        $usuarioDAO = new UsuarioDAO();
        $usuario = new Usuario();
        $usuario->setLogin($login);
        return $usuarioDAO->listarPorLogin($usuario);
    }

    public function listarPorEmail($email)
    {
        $this->log->info("Executando o método listarPorEmail");
        $usuarioDAO = new UsuarioDAO();
        $usuario = new Usuario();
        $usuario->setEmail($email);
        return $usuarioDAO->listarPorEmail($usuario);
    }

    public function salvar(Usuario $usuario)
    {
        $this->log->info("Executando o método salvar");
        $transacao = new Transacao();
        $usuarioValidador = new UsuarioValidadorInserir();
        $hashValidador = new HashValidadorInserir();
        $resultadoValidacaoUsuario = $usuarioValidador->validar($usuario);
        try {
            $transacao->beginTransaction();
            if ($resultadoValidacaoUsuario->getErros()) {
                Sessao::limpaErro();
                Sessao::gravaErro($resultadoValidacaoUsuario->getErros());
                return false;
            } else {
                $usuarioDAO = new UsuarioDAO();
                $hashDAO = new HashDAO();
                $usuario->setSenha(Criptografia::criptografar($usuario->getSenha()));
                $usuario->setConfSenha(Criptografia::criptografar($usuario->getConfSenha()));
                $idUsuario = $usuarioDAO->salvar($usuario);
                $usuario->setIdUsuario($idUsuario);
                $hash = $this->gerarHash($usuario);
                $resultadoValidacaoHash = $hashValidador->validar($hash);
                if ($resultadoValidacaoHash->getErros()) {
                    Sessao::limpaErro();
                    Sessao::gravaErro($resultadoValidacaoHash->getErros());
                    $transacao->rollback();
                    return false;
                }
                $hashDAO->salvar($hash);
                $transacao->commit();
                $this->enviarEmailConfirmacaoCadastro($usuario, $hash);
                Sessao::limpaFormulario();
                Sessao::limpaMensagem();
                Sessao::gravaMensagem("Novo Usuário cadastrado com sucesso.");
                return true;
            }
        } catch (Exception $e) {
            $transacao->rollback();
            throw new Exception($e);
        }
    }

    public function editar(Usuario $usuario)
    {
        $this->log->info("Executando o método editar");
        $usuarioValidador = new UsuarioValidadorEditar();
        $usuarioDAO = new UsuarioDAO();
        $usuarioCadastrado = $usuarioDAO->listar($usuario->getIdUsuario())[0];
        $usuarioCadastrado->setConfSenha($usuarioCadastrado->getSenha());
        if ($usuario->getSenha() != $usuarioCadastrado->getSenha()) {
            $usuario->setSenha(Criptografia::criptografar($usuario->getSenha()));
        }

        if ($usuario->getConfSenha() != $usuarioCadastrado->getConfSenha()) {
            $usuario->setConfSenha(Criptografia::criptografar($usuario->getConfSenha()));
        }

        $resultadoValidacaoUsuario = $usuarioValidador->validar($usuario, $usuarioCadastrado);
        if ($resultadoValidacaoUsuario->getErros()) {
            Sessao::limpaErro();
            Sessao::gravaErro($resultadoValidacaoUsuario->getErros());
            return false;
        } else {
            $usuarioDAO->editar($usuario);
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Vaga atualizada com sucesso.");
            return true;
        }
    }

    public function excluir(Usuario $usuario)
    {
        $this->log->info("Executando o método excluir");
        $transacao = new Transacao();
        $usuarioDAO = new UsuarioDAO();
        try {
            $transacao->beginTransaction();
            $usuarioDAO->excluirComRelacionamento($usuario);
            $transacao->commit();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Usuário excluído com sucesso.");
            return true;
        } catch (Exception $e) {
            $transacao->rollback();
            throw new Exception($e);
        }
    }

    public function gerarHash(Usuario $usuario): Hash
    {
        $this->log->info("Executando o método gerarHash");
        $hash = new Hash();
        $hash->setStatus(0);
        $hash->setHash(Criptografia::criptografar($usuario->getEmail()));
        $dataAtual = DataUtil::getDataAtual();
        $hash->setDataCadastro(DataUtil::getDataFormatoATOM($dataAtual));
        $hash->setUsuario($usuario);
        return $hash;
    }

    public function ativacao(Usuario $usuario, $codigoHash)
    {
        $this->log->info("Executando o método ativacao");
        $transacao = new Transacao();
        $usuarioDAO = new UsuarioDAO();
        $hashDAO = new HashDAO();
        $hashValidador = new HashValidadorAtivacao();
        try {
            $transacao->beginTransaction();
            $hash = $hashDAO->listarPorIdUsuario($usuario->getIdUsuario())[0];
            if (!empty($hash) && $hash->getStatus() == 1) {
                Sessao::limpaFormulario();
                throw new Exception("A chave já foi ativada.");
            }

            if ($hash->getHash() != $codigoHash) {
                throw new Exception("A chave não está associada ao usuário de origem.");
            }

            $resultadoValidacao = $hashValidador->validar($hash);

            if ($resultadoValidacao->getErros()) {
                Sessao::limpaErro();
                Sessao::gravaErro($resultadoValidacao->getErros());
                $transacao->rollback();
                return false;
            } else {
                $usuario->setStatus(1);
                $hash->setStatus(1);
                $usuarioDAO->ativar($usuario);
                $hashDAO->ativar($hash);
                $transacao->commit();
                Sessao::limpaFormulario();
                Sessao::limpaMensagem();
                Sessao::gravaMensagem("Usuário ativado com sucesso.");
                return true;
            }
        } catch (Exception $e) {
            $transacao->rollback();
            throw new Exception($e);
        }
    }

    public function enviarEmailConfirmacaoCadastro(Usuario $usuario, Hash $hash)
    {
        $this->log->info("Executando o método enviarEmailConfirmacaoCadastro");
        Email::enviarEmailConfirmacaoCadastro($usuario, $hash);
    }
}
