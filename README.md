# stream2log

Stream to PSR Logger wrapper, example use:

```php
class MockLogger extends \Psr\Log\AbstractLogger {
    public function log($level, $message, array $context = array()) {
        fprintf(STDERR, "[%s] [level:%s] %s", date('c', 0x44884488), $level, strtr($message, $context));
    }
}
\thesebas\stream2log\Wrapper::setup();
\thesebas\stream2log\Wrapper::registerLogger('mocklog', new MockLogger());

define("MYERR", fopen("log://mocklog", 'w'));

// regular STDERR output
fprintf(STDERR, "! error message\n");    // ! error message
fprintf(STDERR, "# warning message\n");  // # warning message
fprintf(STDERR, "* info message\n");     // * info message

// writes to MYERR are redirected to and handled by registered logger
fprintf(MYERR, "! error message\n");     // [2006-06-08T15:38:48+00:00] [level:error] error message
fprintf(MYERR, "# warning message\n");   // [2006-06-08T15:38:48+00:00] [level:warning] warning message
fprintf(MYERR, "* info message\n");      // [2006-06-08T15:38:48+00:00] [level:info] info message

```






