<?php

namespace Application\Service;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerService
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('main');

        $formatter = new LineFormatter(dateFormat: "d/m/Y H:i:s");
        $logHandler = new StreamHandler(getcwd() . '/data/logs/main.log');
        $logHandler->setFormatter($formatter);

        $this->logger->pushHandler($logHandler);
    }

    public function info($message, $context = [])
    {
        $this->logger->info($message, $context);
    }
}
