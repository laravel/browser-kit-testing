<?php

namespace Laravel\BrowserKitTesting\Tests\Feature;

use Illuminate\Foundation\Auth\User;
use Laravel\BrowserKitTesting\TestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\Foundation\Env;
use Orchestra\Testbench\Foundation\UndefinedValue;

class ParallelTestingTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (Env::get('LARAVEL_PARALLEL_TESTING', new UndefinedValue) instanceof UndefinedValue) {
            $this->markTestSkipped('Requires paratest to execute the tests');
        }
    }

    public function test_database_connection_name()
    {
        $databaseName = (new User)->getConnection()->getDatabaseName();

        $defaultDatabaseName = config(sprintf('database.connections.%s.database', config('database.default')));

        $this->assertStringContainsString("{$defaultDatabaseName}_test_", $databaseName);
    }
}
