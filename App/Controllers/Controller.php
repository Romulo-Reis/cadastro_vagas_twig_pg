<?php

namespace App\Controllers;

use App\App;
use App\Lib\Sessao;
use App\Services\LoginService;
use Error;
use Exception;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\LoaderInterface;

abstract class Controller extends Environment
{

    private $app;
    private $viewVar = [];

    public function __construct($app, LoaderInterface $loader, $options = array())
    {
        parent::__construct($loader, $options);
        $loginService = new LoginService();
        $this->app = $app;
        $this->setViewParam('nameController', $app->getControllerName());
        $this->setViewParam('nameAction', $app->getAction());
        $this->setViewParam('APP_HOST', APP_HOST);
        $this->setViewParam('usuarioLogado', $loginService->retornarUsuarioLogado());
        $this->addExtension(new IntlExtension());
    }

    public function setViewParam($varName, $varValue)
    {
        if ($varName != "" && $varValue != "") {
            $this->viewVar[$varName] = $varValue;
        }
    }

    public function getViewVar()
    {
        return $this->viewVar;
    }

    /*public function render($view){
        $viewVar   = $this->viewVar;
        if(empty($viewVar)){
            throw new Exception("A variavel está nula.", 500);
        }
        $sessao    = Sessao::class;

        require_once PATH . '/App/Views/layouts/head.php';
        require_once PATH . '/App/Views/layouts/header.php';
        require_once PATH . '/App/Views/layouts/sidebar.php';
        require_once PATH . '/App/Views/'.$view.'.php';
        require_once PATH . '/App/Views/layouts/footer.php';
    }*/

    public function render($view, $dados = array())
    {
        echo parent::render($view, $dados);
    }

    public function redirect($uri)
    {
        $host = APP_HOST;
        $pos = strripos($host, "/");
        if ($pos == (strlen($host) - 1)) {
            $host = str_replace("/", "", $host);
        }
        header('Location: http://' . $host . $uri);
    }
}
