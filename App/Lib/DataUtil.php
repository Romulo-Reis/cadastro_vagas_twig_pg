<?php

namespace App\Lib;

use DateTime;
use DateTimeZone;

class DataUtil
{
    private static $timezones = array(
        'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
        'AP' => 'America/Belem',        'AM' => 'America/Manaus',
        'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
        'DF' => 'America/Sao_Paulo',    'ES' => 'America/Sao_Paulo',
        'GO' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
        'MT' => 'America/Cuiaba',       'MS' => 'America/Campo_Grande',
        'MG' => 'America/Sao_Paulo',    'PR' => 'America/Sao_Paulo',
        'PB' => 'America/Fortaleza',    'PA' => 'America/Belem',
        'PE' => 'America/Recife',       'PI' => 'America/Fortaleza',
        'RJ' => 'America/Sao_Paulo',    'RN' => 'America/Fortaleza',
        'RS' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
        'RR' => 'America/Boa_Vista',    'SC' => 'America/Sao_Paulo',
        'SE' => 'America/Maceio',       'SP' => 'America/Sao_Paulo',
        'TO' => 'America/Araguaia',
        );
    public static function getDataAtual()
    {
        $dataAtual = new DateTime('now');
        return $dataAtual;
    }

    public static function getDataFormatoATOM(DateTime $data)
    {
        return date(DateTime::ATOM, $data->getTimestamp());
    }

    public static function getFusoHorario($uf): DateTimeZone
    {
        return new DateTimeZone(self::$timezones[$uf]);
    }

    public static function getData($textoData, $formato)
    {
        $timeZone = self::getFusoHorario('RJ');
        $data = DateTime::createFromFormat($formato, $textoData, $timeZone);
        return date(DateTime::ATOM, $data->getTimestamp());
    }
}
