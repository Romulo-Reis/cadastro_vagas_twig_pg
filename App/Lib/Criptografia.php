<?php

namespace App\Lib;

class Criptografia
{
    const KEY = 'juju78¨&*%$==';

    public static function criptografar($texto)
    {
        if (empty($texto)) {
            return '';
        }

        return base64_encode($texto . self::KEY);
    }

    public static function descriptografar($token)
    {
        $texto = base64_decode($token);

        return str_replace(self::KEY, '', $texto);
    }
}
