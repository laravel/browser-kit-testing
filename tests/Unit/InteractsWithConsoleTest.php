<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Laravel\BrowserKitTesting\Concerns\InteractsWithConsole;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InteractsWithConsoleTest extends TestCase
{
    use InteractsWithConsole;

    protected $app;

    #[Test]
    public function call_artisan_command_return_code()
    {
        $this->app[Kernel::class] = new class
        {
            public function call($command, $parameters)
            {
                return 'User was created.';
            }
        };
        $command = 'app:user';
        $parameters = ['name' => 'john'];

        $this->assertSame(
            'User was created.',
            $this->artisan($command, $parameters)
        );

        $this->assertSame(
            $this->code,
            $this->app[Kernel::class]->call($command, $parameters)
        );
    }
}
