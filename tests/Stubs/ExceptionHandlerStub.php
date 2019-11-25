<?php

namespace Laravel\BrowserKitTesting\Tests\Stubs;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionHandlerStub implements ExceptionHandler
{
    public function __construct()
    {
    }

    public function report(Throwable $e)
    {
    }

    public function shouldReport(Throwable $e)
    {
        return false;
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            throw new NotFoundHttpException(
                "{$request->method()} {$request->url()}", null, $e->getCode()
            );
        }

        throw $e;
    }

    public function renderForConsole($output, Throwable $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }
}
