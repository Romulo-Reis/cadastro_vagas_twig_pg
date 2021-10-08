<?php

namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    public static function enviarEmailConfirmacaoCadastro($usuario, $hash)
    {
        $log = new Log(get_called_class());
        $log->info("Enviando e-mail de confirmação e cadastro");
        self::enviar(
            $usuario->getEmail(),
            $usuario->getLogin(),
            'Confirmação de email',
            "<p><a>Clique <a href='http://" . APP_HOST . "/usuario/ativacao/{$hash->getHash()}'>aqui</a>.</p>",
            "usuario/cadastrar/{$hash->getHash()} para ativar o seu cadastro."
        );
    }

    public static function enviarEmailComCodigoRecuperacao($usuario, $codigo)
    {
        $log = new Log(get_called_class());
        $log->info("Enviando e-mail com o código de recuperação de senha");
        self::enviar(
            $usuario->getEmail(),
            $usuario->getLogin(),
            'Código de alteração de senha',
            "<p>Olá, {$usuario->getLogin()}</p><p>Favor, informe o código $codigo para prosseguir com a alteração da senha.</p>",
            "Favor, informe o código $codigo para prosseguir coma alteração da senha."
        );
    }

    private static function enviar($para, $nome, $titulo, $html, $txt)
    {
        $log = new Log(get_called_class());
        $log->info("Executando o método enviar");
        $mail = new PHPMailer();
        $mail->isSMTP();
        //$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = $_ENV['EMAIL_HOST'];
        //$mail->Host = gethostbyname('smtp.gmail.com');
        //$mail->Port = 465;
        $mail->Port = 587;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $user = $_ENV['EMAIL_USERNAME'];
        $password = $_ENV['EMAIL_PASSWORD'];
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $password;
        $mail->setFrom($_ENV['CLOUDMAILIN_FORWARD_ADDRESS'], 'Não responda');
        $mail->addReplyTo($_ENV['CLOUDMAILIN_FORWARD_ADDRESS'], 'Não responda');
        $mail->addAddress($para, $nome);
        $mail->Subject = $titulo;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->msgHTML($html);
        $mail->AltBody = $txt;
        $mail->addAttachment(PATH . '/public/assets/logo-devmedia.png');

        if (!$mail->send()) {
            $log->critical("Erro no envio do e-mail.", [
                "Destinatario" => $para,
                "Erro" => $mail->ErrorInfo
            ]);
            throw new Exception("Erro no envio do e-mail: {$mail->ErrorInfo}");
        }
    }
}
