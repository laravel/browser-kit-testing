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

    public function test_refresh_application()
    {
        $this->refreshApplication();

        $this->assertInstanceOf(Application::class, $this->app);
    }
    
    public function test_refresh_application_does_not_override_app_env()
    {
	putenv('APP_ENV=foo');

	$this->refreshApplication();

	$this->assertEquals('foo', getenv('APP_ENV'));
    }
}
