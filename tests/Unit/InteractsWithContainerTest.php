<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Laravel\BrowserKitTesting\Concerns\InteractsWithContainer;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InteractsWithContainerTest extends TestCase
{
    use InteractsWithContainer;

    protected $app;

    #[Test]
    public function register_instances_of_object_on_container()
    {
        $this->app = new class
        {
            public function instance()
            {
            }
        };
        $abstract = 'Foo';
        $instance = new class {
        };
        $this->assertEquals(
            $instance,
            $this->instance($abstract, $instance)
        );
    }
}
