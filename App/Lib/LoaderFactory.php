<?php

namespace App\Lib;

use Exception;
use Twig\Loader\FilesystemLoader;

class LoaderFactory
{
    private static $loader;

    private function __construct()
    {
    }

    public static function getLoader()
    {
        $log = new Log(get_called_class());
        try {
            $log->info("Carregando as configurações do twig");
            $caminhoTemplates = PATH . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Views";
            if (self::$loader === null) {
                self::$loader = new FilesystemLoader($caminhoTemplates);
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'home', 'home');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'empresa', 'empresa');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'vaga', 'vaga');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'tecnologia', 'tecnologia');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'usuario', 'usuario');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'login', 'login');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'perfil', 'perfil');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'error', 'error');
                self::$loader->addPath($caminhoTemplates . DIRECTORY_SEPARATOR . 'layouts', 'layouts');
            }
            return self::$loader;
        } catch (Exception $e) {
            $log->critical("Erro de configuração do twig.", ['exception' => $e]);
            throw new Exception("Erro de configuração do twig.", 500, $e);
        }
    }
}
