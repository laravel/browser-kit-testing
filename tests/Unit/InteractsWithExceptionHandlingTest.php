<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Laravel\BrowserKitTesting\Concerns\InteractsWithExceptionHandling;
use Laravel\BrowserKitTesting\Tests\Stubs\ExceptionHandlerStub;
use Laravel\BrowserKitTesting\Tests\Stubs\OutputStub;
use Laravel\BrowserKitTesting\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InteractsWithExceptionHandlingTest extends TestCase
{
    use InteractsWithExceptionHandling;

    protected $app;

    /**
     * @test
     */
    public function withExceptionHandling_restore_exception_handling()
    {
        $this->app = new Application();
        $this->previousExceptionHandler = 'MyExceptionHandler';
        $this->withExceptionHandling();
        $this->assertEquals(
            app(ExceptionHandler::class),
            $this->previousExceptionHandler
        );
    }

    /**
     * @test
     */
    public function withoutExceptionHandling_disable_exception_handling_for_the_test()
    {
        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new ExceptionHandlerStub());
        $this->assertNull($this->previousExceptionHandler);
        $this->withoutExceptionHandling();
        $this->assertInstanceOf(
            ExceptionHandler::class,
            $this->previousExceptionHandler
        );
    }

    /**
     * @test
     */
    public function withExceptionHandling_throw_exception_NotFoundHttpException()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Abort 404');
        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });

        $this->withoutExceptionHandling();
        abort(404, 'Abort 404');
    }

    /**
     * @test
     */
    public function report_of_instance_ExceptionHandler_on_Application_does_nothing()
    {
        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });

        $this->withoutExceptionHandling();
        $this->assertNull(app(ExceptionHandler::class)->report(new Exception));
    }

    /**
     * @test
     */
    public function render_of_instance_ExceptionHandler_on_Application_throw_exception_NotFoundHttpException()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('GET http://localhost');

        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });

        $request = new class
        {
            public function method()
            {
                return 'GET';
            }

            public function url()
            {
                return 'http://localhost';
            }

            public function getCode()
            {
                return 404;
            }
        };

        $this->withoutExceptionHandling();
        app(ExceptionHandler::class)->render($request, new NotFoundHttpException);
    }

    /**
     * @test
     */
    public function render_of_instance_ExceptionHandler_on_Application_throw_exception_anyone()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('My Exception');

        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });

        $request = new class
        {
        };

        $this->withoutExceptionHandling();

        app(ExceptionHandler::class)->render($request, new Exception('My Exception'));
    }

    /**
     * @test
     */
    public function renderForConsole_throw_exception_to_console_and_does_nothing()
    {
        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });
        $output = new OutputStub;
        $this->withoutExceptionHandling();

        $this->assertNull(
            app(ExceptionHandler::class)
                ->renderForConsole($output, new Exception)
        );
    }

    /**
     * @test
     */
    public function withoutExceptionHandling_doesnt_not_report_exceptions()
    {
        $this->app = new Application();
        $this->app->instance(ExceptionHandler::class, new class
        {
        });
        $this->withoutExceptionHandling();
        $this->assertFalse(
            app(ExceptionHandler::class)->shouldReport(new NotFoundHttpException)
        );
    }
}
