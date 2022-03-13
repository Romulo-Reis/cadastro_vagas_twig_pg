<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\EmpresaController;
use App\Controllers\TecnologiaController;
use App\Controllers\VagaController;
use App\Lib\LoaderFactory;
use App\Lib\DataUtil;
use Exception;

class App
{

    private $controller;
    private $controllerFile;
    private $action;
    private $params;
    private $loader;
    private $optionsTwig;
    public $controllerName;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $diretorio_app = $_ENV['DIRETORIO_APP'] ? $_ENV['DIRETORIO_APP'] : "";
        define('APP_HOST', $_SERVER['HTTP_HOST'] . $diretorio_app);
        define('PATH', realpath('./'));
        define('TITLE', "Cadastro de vagas - mestre detalhe");
        $this->optionsTwig = array('cache' => false);
        $this->loader = LoaderFactory::getLoader();
    }

    public function run()
    {
        if (isset($_GET["url"])) {
            $path = $_GET['url'];
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            $path = explode('/', $path);

            $this->controller  = $this->verificaArray($path, 0);
            $this->action      = $this->verificaArray($path, 1);

            if ($this->verificaArray($path, 2)) {
                unset($path[0]);
                unset($path[1]);
                $this->params = array_values($path);
            }
        }

        if ($this->controller) {
            $this->controllerName = ucwords($this->controller) . 'Controller';
            $this->controllerName = preg_replace('/[^a-zA-Z]/i', '', $this->controllerName);
        } else {
            $this->controllerName = "HomeController";
        }

        $this->controllerFile   = $this->controllerName . '.php';
        $this->action           = preg_replace('/[^a-zA-Z]/i', '', $this->action);

        if (!$this->controller) {
            $this->controller = new HomeController($this, $this->loader, $this->optionsTwig);
            $this->controller->index();
            return;
        }

        if (!file_exists(PATH . '/App/Controllers/' . $this->controllerFile)) {
            throw new Exception("Página não encontrada.", 404);
        }

        $nomeClasse     = "\\App\\Controllers\\" . $this->controllerName;
        $objetoController = new $nomeClasse($this, $this->loader, $this->optionsTwig);

        if (!class_exists($nomeClasse)) {
            throw new Exception("Erro na aplicação", 500);
        }

        if (method_exists($objetoController, $this->action)) {
            $objetoController->{$this->action}($this->params);
            return;
        } elseif (!$this->action && method_exists($objetoController, 'index')) {
            $objetoController->index($this->params);
            return;
        } else {
            throw new Exception("Nosso suporte já esta verificando desculpe!", 500);
        }
        throw new Exception("Página não encontrada.", 404);
    }

    public function url()
    {
        if (isset($_GET['url'])) {
            $path = $_GET['url'];
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);

            $path = explode('/', $path);

            $this->controller  = $this->verificaArray($path, 0);
            $this->action      = $this->verificaArray($path, 1);

            if ($this->verificaArray($path, 2)) {
                unset($path[0]);
                unset($path[1]);
                $this->params = array_values($path);
            }
        }
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    private function verificaArray($array, $key)
    {

        if (isset($array[ $key ]) && !empty($array[ $key ])) {
            return $array[ $key ];
        }

        return null;
    }

    /**
     * Get the value of optionsTwig
     */
    public function getOptionsTwig()
    {
        return $this->optionsTwig;
    }

    /**
     * Set the value of optionsTwig
     *
     * @return  self
     */
    public function setOptionsTwig($optionsTwig)
    {
        $this->optionsTwig = $optionsTwig;

        return $this;
    }
}
