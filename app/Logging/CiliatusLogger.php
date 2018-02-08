<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CiliatusLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @return \Monolog\Logger
     */
    public function __invoke()
    {
        $logger = new Logger('ciliatus');
        $filename = storage_path('logs/laravel-' . php_sapi_name() . '.log');
        $formatter = new LineFormatter(null, null, true);
        $handler = new RotatingFileHandler($filename, 8);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }
}