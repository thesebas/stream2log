<?php
ini_set('date.timezone', "UTC");

require_once __DIR__ . '/../vendor/autoload.php';

class MockLogger extends \Psr\Log\AbstractLogger {
    public function log($level, $message, array $context = array()) {
        fprintf(STDERR, "[%s] [level:%s] %s", date('c', 0x44884488), $level, strtr($message, $context));
    }
}

\thesebas\stream2log\Wrapper::setup();
\thesebas\stream2log\Wrapper::registerLogger('mocklog', new MockLogger());

define("MYERR", fopen("log://mocklog", 'w'));

fprintf(MYERR, "! error message\n");
fprintf(STDERR, "! error message\n");

fprintf(MYERR, "# warning message\n");
fprintf(STDERR, "# warning message\n");

fprintf(MYERR, "* info message\n");
fprintf(STDERR, "* info message\n");
