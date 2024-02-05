<?php

namespace Laravel\BrowserKitTesting\Tests;

use Illuminate\Foundation\Application;
use Laravel\BrowserKitTesting\TestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;

class TestCaseTest extends TestCase
{
    use CreatesApplication;

    public function test_refresh_application()
    {
        $this->refreshApplication();

        $this->assertInstanceOf(Application::class, $this->app);
    }
}
