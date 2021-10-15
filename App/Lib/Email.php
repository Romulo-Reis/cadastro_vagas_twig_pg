<?php

namespace App\Lib;

use Exception;

class Email
{
    public static function enviarEmailConfirmacaoCadastro($usuario, $hash)
    {
        $log = new Log(get_called_class());
        $log->info("Enviando e-mail de confirmação e cadastro");
        $recipients = array();
        $recipients[] = array(
            'name' => $usuario->getLogin(),
            'email' => $usuario->getEmail()
        );
        $list = array();
        $contacts = array();
        $methods = array(
            'postmark' => false,
            'secureSend' => false,
            'encryptContent' => false,
            'secureReply' => false
        );
        $mensagem = array(
            'template' => array(
                'name' => 'confirmacao_conta_cadastro_vagas',
                'fields' => array(
                    'usuario' => $usuario->getLogin(),
                    'link' => "<a href='http://" . APP_HOST . "/usuario/ativacao/{$hash->getHash()}'>Clique aqui</a>"
                )
            ),
            'recipients' => $recipients,
            'list' => $list,
            'contact' => $contacts,
            'methods' => $methods
        );
        self::enviar(
            $usuario->getEmail(),
            $usuario->getLogin(),
            json_encode($mensagem)
        );
    }

    public static function enviarEmailComCodigoRecuperacao($usuario, $codigo)
    {
        $log = new Log(get_called_class());
        $log->info("Enviando e-mail com o código de recuperação de senha");
        $recipients = array();
        $recipients[] = array(
            'name' => $usuario->getLogin(),
            'email' => $usuario->getEmail()
        );
        $list = array();
        $contacts = array();
        $methods = array(
            'postmark' => false,
            'secureSend' => false,
            'encryptContent' => false,
            'secureReply' => false
        );
        $mensagem = array(
            'template' => array(
                'name' => 'codigo_de_recuperacao_senha_cadastro_vagas',
                'fields' => array(
                    'usuario' => $usuario->getLogin(),
                    'codigo' => $codigo
                )
            ),
            'recipients' => $recipients,
            'list' => $list,
            'contact' => $contacts,
            'methods' => $methods
        );
        self::enviar(
            $usuario->getEmail(),
            $usuario->getLogin(),
            json_encode($mensagem)
        );
    }

    private static function enviar($para, $nome, $mensagem)
    {
        $log = new Log(get_called_class());
        $log->info("Executando o método enviar");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV['TRUSTIFI_URL'] . "/api/i/v1/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $mensagem,
            CURLOPT_HTTPHEADER => array(
                "x-trustifi-key: " . $_ENV['TRUSTIFI_KEY'],
                "x-trustifi-secret: " . $_ENV['TRUSTIFI_SECRET'],
                "content-type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            throw new Exception($err, 500);
        } else {
            $info = curl_getinfo($curl);
            $status = $info["http_code"];
            if ($status != "200") {
                throw new Exception("Falha no envio do e-mail!", $status);
            } else {
                $response = json_decode($response);
                $log->info($response->meta[0]->message);
            }
        }
    }
}
