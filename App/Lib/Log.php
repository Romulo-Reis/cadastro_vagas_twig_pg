<?php

namespace App\Lib;

use App\Lib\DataUtil;
use Exception;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use DateTime;

class Log implements LoggerInterface
{
    private $arquivoLog;
    private $canal;

    public function __construct($canal = null)
    {
        if (!empty($canal)) {
            $this->canal = $canal;
        } else {
            $this->canal = "my_logger";
        }
        $caminhoLogs = PATH . DIRECTORY_SEPARATOR . "logs";
        $dataAtual = DataUtil::getDataAtual();
        $this->arquivoLog = $caminhoLogs . DIRECTORY_SEPARATOR . "app." . $dataAtual->format("Y-m-d") . ".log";
    }

    public function emergency($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::EMERGENCY));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::EMERGENCY));
        $logger->emergency($message, $context);
    }


    public function alert($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::ALERT));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::ALERT));
        $logger->alert($message, $context);
    }

    public function critical($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::CRITICAL));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::CRITICAL));
        $logger->critical($message, $context);
    }

    public function error($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::ERROR));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::ERROR));
        $logger->error($message, $context);
    }

    public function warning($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::WARNING));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::WARNING));
        $logger->warning($message, $context);
    }

    public function notice($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::NOTICE));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::NOTICE));
        $logger->notice($message, $context);
    }

    public function info($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::INFO));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::INFO));
        $logger->info($message, $context);
    }

    public function debug($message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->pushHandler(new StreamHandler("php://stderr", Logger::DEBUG));
        $logger->pushHandler(new StreamHandler($this->arquivoLog, Logger::DEBUG));
        $logger->debug($message, $context);
    }


    public function log($level, $message, array $context = array())
    {
        $logger = new Logger($this->canal);
        $logger->log($level, $message, $context);
    }
}
