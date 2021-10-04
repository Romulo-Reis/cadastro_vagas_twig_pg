<?php

namespace App\Lib;

use App\Controllers\Controller;
use Exception;
use Twig\Loader\LoaderInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Erro extends Controller
{

    private $message;
    private $code;

    public function __construct($objetoException = Exception::class, $app, LoaderInterface $loader, $options = array())
    {
        parent::__construct($app, $loader, $options);
        $this->code     = $objetoException->getCode();
        $this->message  = $objetoException->getMessage() . $objetoException->getTraceAsString();
        $this->setViewParam("codigo", $this->code);
        $this->setViewParam("mensagemErro", $this->message);
    }

    public function render($view, $dados = array())
    {
        echo parent::render($view, $dados);
    }

    public function renderError()
    {
        $this->render("@error/erro.html.twig", $this->getViewVar());
    }
}
