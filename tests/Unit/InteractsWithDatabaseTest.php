<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Laravel\BrowserKitTesting\Concerns\InteractsWithConsole;
use Laravel\BrowserKitTesting\Concerns\InteractsWithDatabase;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InteractsWithDatabaseTest extends TestCase
{
    use InteractsWithDatabase,
        InteractsWithConsole;

    protected $app;

    #[Test]
    public function assert_that_data_exists_on_databases()
    {
        $this->app = new class
        {
            public function make()
            {
                return $this;
            }

            public function getDefaultConnection()
            {
                return $this;
            }

            public function connection()
            {
                return $this;
            }

            public function table()
            {
                return $this;
            }

            public function where()
            {
                return $this;
            }

            public function count()
            {
                return 1;
            }
        };
        $table = 'users';
        $data = ['name' => 'john', 'email' => 'john.doe@testing.com'];
        $this->seeInDatabase($table, $data);
    }

    #[Test]
    public function assert_that_data_not_exists_on_databases()
    {
        $this->app = new class
        {
            public function make()
            {
                return $this;
            }

            public function getDefaultConnection()
            {
                return $this;
            }

            public function connection()
            {
                return $this;
            }

            public function table()
            {
                return $this;
            }

            public function where()
            {
                return $this;
            }

            public function count()
            {
                return 0;
            }
        };
        $table = 'users';
        $data = ['name' => 'john', 'email' => 'john.doe@testing.com'];

        $this->missingFromDatabase($table, $data);
        $this->dontSeeInDatabase($table, $data);
        $this->notSeeInDatabase($table, $data);
    }

    #[Test]
    public function run_seed()
    {
        $this->app[Kernel::class] = new class
        {
            public function call()
            {
                return 'Seeding: DatabaseSeeder';
            }
        };
        $this->seed();
        $this->assertSame(
            'Seeding: DatabaseSeeder',
            $this->code
        );
    }
}
