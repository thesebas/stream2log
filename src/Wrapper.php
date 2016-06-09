<?php

namespace thesebas\stream2log;


use Psr\Log\LoggerInterface;

class Wrapper {
    protected static $loggers = [];

    /**
     * @param $id
     * @param $logger LoggerInterface
     */
    public static function registerLogger($id, LoggerInterface $logger) {
        self::$loggers[$id] = $logger;
    }

    public static function setup() {
        stream_register_wrapper('log', self::class);
    }

    /**
     * @var LoggerInterface
     */
    protected $log;

    public function stream_open($path, $mode, $options, &$opened_path) {
        $url = parse_url($path);
        $this->log = self::$loggers[$url['host']];
        return true;
    }

    public function stream_write($message) {
        $strlen = strlen($message);
        switch ($message{0}) {
            case '@':
                $level = \Psr\Log\LogLevel::DEBUG;
                $message = ltrim($message, '@ ');
                break;
            case '*':
            default:
                $level = \Psr\Log\LogLevel::INFO;
                $message = ltrim($message, '* ');
                break;
            case '#':
                $level = \Psr\Log\LogLevel::WARNING;
                $message = ltrim($message, '# ');
                break;

            case '!':
                $level = \Psr\Log\LogLevel::ERROR;
                $message = ltrim($message, '! ');
                break;

        }

        $this->log->log($level, $message);
        return $strlen;
    }
}