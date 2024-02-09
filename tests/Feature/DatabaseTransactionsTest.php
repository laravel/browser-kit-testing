<?php

namespace Laravel\BrowserKitTesting\Tests\Feature;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\User;
use Laravel\BrowserKitTesting\TestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\Foundation\Env;

class DatabaseTransactionsTest extends TestCase
{
    use CreatesApplication;

    public function test_database_connection_name()
    {
        $databaseName = (new User)->getConnection()->getDatabaseName();

        $this->assertStringContainsString(
            Env::get('LARAVEL_PARALLEL_TESTING') == 1 ? 'testing_test_' : ':memory:',
            $databaseName
        );
    }
}
