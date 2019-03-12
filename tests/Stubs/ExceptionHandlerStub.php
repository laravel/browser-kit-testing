<?php

namespace Laravel\BrowserKitTesting\Tests\Stubs;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandlerStub implements ExceptionHandler
{
    public function __construct()
    {
    }

    public function report(Exception $e)
    {
    }

    public function shouldReport(Exception $e)
    {
        return false;
    }

    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            throw new NotFoundHttpException(
                "{$request->method()} {$request->url()}", null, $e->getCode()
            );
        }

        throw $e;
    }

    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }
}
