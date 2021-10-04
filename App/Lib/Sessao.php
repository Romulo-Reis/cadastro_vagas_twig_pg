<?php

namespace App\Lib;

use App\Models\Entidades\Usuario;
use App\Services\UsuarioService;

class Sessao
{
    public static function gravarIdUsuarioLogado(Usuario $usuario)
    {
        $log = new Log(get_called_class());
        $log->info("Gravando usuário {$usuario->getLogin()} na sessão", ['id' => $usuario->getIdUsuario()]);
        $_SESSION['idUsuarioLogado'] = $usuario->getIdUsuario();
    }

    public static function limparIdUsuarioLogado()
    {
        $log = new Log(get_called_class());
        $log->info("Limpando id do usuário da sessão", ['id' => $_SESSION['idUsuarioLogado']]);
        unset($_SESSION['idUsuarioLogado']);
    }

    public static function retornaIdUsuarioLogado()
    {
        $log = new Log(get_called_class());
        $log->info("Retornando id do usuário da sessão", ['id' => $_SESSION['idUsuarioLogado']]);
        return (isset($_SESSION['idUsuarioLogado'])) ? $_SESSION['idUsuarioLogado'] : "";
    }

    public static function gravaMensagem($mensagem)
    {
        $log = new Log(get_called_class());
        $log->info("Gravando mensagem na sessão", ['mensagem' => $mensagem]);
        $_SESSION['mensagem'] = $mensagem;
    }

    public static function gravaErro($erros)
    {
        $log = new Log(get_called_class());
        $log->info("Gravando erros na sessão", ['erros' => $erros]);
        $_SESSION['erros'] = $erros;
    }

    public static function limpaMensagem()
    {
        $log = new Log(get_called_class());
        $log->info("Limpando mensgem da sessão", ['mensagem' => $_SESSION['mensagem']]);
        unset($_SESSION['mensagem']);
    }

    public static function limpaErro()
    {
        $log = new Log(get_called_class());
        $log->info("Limpando erros da sessão", ['erros' => $_SESSION['erros']]);
        unset($_SESSION['erros']);
    }

    public static function retornaErro()
    {
        $log = new Log(get_called_class());
        $log->info("Retornando erros da sessão", ['erros' => $_SESSION['erros']]);
        return (isset($_SESSION['erros'])) ? $_SESSION['erros'] : false;
    }

    public static function retornaMensagem()
    {
        $log = new Log(get_called_class());
        $log->info("Retornando mensagem da sessão", ['mensagem' => $_SESSION['mensagem']]);
        return (isset($_SESSION['mensagem'])) ? $_SESSION['mensagem'] : "";
    }

    public static function gravaFormulario($form)
    {
        $log = new Log(get_called_class());
        $log->info("Gravando formulário da sessão");
        $_SESSION['form'] = $form;
    }

    public static function limpaFormulario()
    {
        $log = new Log(get_called_class());
        $log->info("Limpando formulário da sessão");
        unset($_SESSION['form']);
    }

    public static function existeFormulario()
    {
        $log = new Log(get_called_class());
        $log->info("Verificando se tem formulário gravado na sessão");
        return (isset($_SESSION['form'])) ? true : false;
    }

    public static function retornaValorFormulario($key)
    {
        $log = new Log(get_called_class());
        $log->info("Retornando valor de um campo do formulário gravado na sessão", [
            "campo" => $key
        ]);
        return (isset($_SESSION['form'][$key])) ? $_SESSION['form'][$key] : "";
    }

    public static function retornaFormulario()
    {
        $log = new Log(get_called_class());
        $log->info("Retornando o formulário gravado na sessão");
        return $_SESSION['form'];
    }

    public static function gravaCodigoRecuperacao($codigo)
    {
        $log = new Log(get_called_class());
        $log->info("Gravando o código de recuperação de senha na sessão");
        $_SESSION['codigo'] = $codigo;
    }

    public static function retornaCodigoRecuperacao()
    {
        $log = new Log(get_called_class());
        $log->info("Retornando o código de recuperação de senha da sessão");
        return $_SESSION['codigo'];
    }

    public static function limpaCodigo()
    {
        $log = new Log(get_called_class());
        $log->info("Limpando o código de recuperação de senha da sessão");
        unset($_SESSION['codigo']);
    }
}
