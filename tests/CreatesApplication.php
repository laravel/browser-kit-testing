<?php

namespace Laravel\BrowserKitTesting\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

use function Orchestra\Testbench\package_path;

trait CreatesApplication
{
    /**
     * Create a new application instance.
     */
    public function createApplication(): Application
    {
        $app = require package_path('vendor/orchestra/testbench-core/laravel/bootstrap/app.php');

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
