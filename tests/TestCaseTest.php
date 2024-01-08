<?php

namespace Laravel\BrowserKitTesting\Tests;

use Illuminate\Foundation\Application;
use Laravel\BrowserKitTesting\TestCase;

class TestCaseTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        return new Application();
    }

    #[Test]
    public function test_refresh_application()
    {
        $this->refreshApplication();

        $this->assertInstanceOf(Application::class, $this->app);
    }
}
