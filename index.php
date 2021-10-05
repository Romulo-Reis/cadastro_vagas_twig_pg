<?php

use App\App;
use App\Lib\Erro;
use App\Lib\LoaderFactory;
use Dotenv\Dotenv;

session_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once("vendor/autoload.php");
if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . ".env")) {
    $dotenv = Dotenv::createMutable(__DIR__);
    $dotenv->load();
}
$app = new App();
try {
    $app->run();
} catch (Exception $e) {
    $loader = LoaderFactory::getLoader();
    $oError = new Erro($e, $app, $loader, $app->getOptionsTwig());
    $oError->renderError();
}
