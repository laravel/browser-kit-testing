<?php

namespace Laravel\BrowserKitTesting\Tests\Feature;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\BrowserKitTesting\TestCase;
use Laravel\BrowserKitTesting\Tests\CreatesApplication;
use Orchestra\Testbench\Foundation\Env;
use Orchestra\Testbench\Foundation\UndefinedValue;

class ParallelTestingTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected function setUp(): void
    {
        if (Env::get('LARAVEL_PARALLEL_TESTING', new UndefinedValue) instanceof UndefinedValue) {
            $this->markTestSkipped('Requires paratest to execute the tests');
        }

        parent::setUp();
    }

    public function test_database_connection_name()
    {
        $databaseName = (new User)->getConnection()->getDatabaseName();

        $this->assertStringContainsString('_test_', $databaseName);
    }
}
