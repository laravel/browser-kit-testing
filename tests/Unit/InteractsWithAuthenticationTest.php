<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Laravel\BrowserKitTesting\Concerns\InteractsWithAuthentication;
use Laravel\BrowserKitTesting\Tests\TestCase;

class InteractsWithAuthenticationTest extends TestCase
{
    use InteractsWithAuthentication;

    protected $app;

    protected function createUserProviderToCredentials()
    {
        return new class
        {
            public $retrieveByCredentials;
            public $validateCredentials;

            public function make()
            {
                return $this;
            }

            public function guard()
            {
                return $this;
            }

            public function getProvider()
            {
                return $this;
            }

            public function retrieveByCredentials()
            {
                return $this->retrieveByCredentials;
            }

            public function validateCredentials()
            {
                return $this->validateCredentials;
            }
        };
    }

    /**
     * @test
     */
    public function hasCredentials_return_true_if_the_credentials_are_valid()
    {
        $this->app = $this->createUserProviderToCredentials();
        $this->app->retrieveByCredentials = true;
        $this->app->validateCredentials = true;

        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret',
        ];
        $this->assertTrue($this->hasCredentials($credentials));
    }

    /**
     * @test
     * @dataProvider DataHasCredentials
     */
    public function hasCredentials_return_false_if_the_credentials_arent_valid($validateCredentials, $retrieveByCredentials)
    {
        $this->app = $this->createUserProviderToCredentials();
        $this->app->retrieveByCredentials = $retrieveByCredentials;
        $this->app->validateCredentials = $validateCredentials;

        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret',
        ];
        $this->assertFalse($this->hasCredentials($credentials));
    }

    public function DataHasCredentials()
    {
        return [
            'Case 01' => [false, true],
            'Case 02' => [false, false],
            'Case 03' => [true, false],
        ];
    }

    /**
     * @test
     */
    public function assert_if_credentials_are_valid_or_invalid()
    {
        $this->app = $this->createUserProviderToCredentials();
        $this->app->retrieveByCredentials = true;
        $this->app->validateCredentials = true;
        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret',
        ];

        $this->seeCredentials($credentials);

        $this->app->retrieveByCredentials = false;
        $this->dontSeeCredentials($credentials);
    }

    /**
     * @test
     */
    public function assert_if_user_is_authenticated()
    {
        $this->app = new class
        {
            public function make()
            {
                return $this;
            }

            public function guard()
            {
                return $this;
            }

            public function user()
            {
                return $this->userAuthenticated;
            }

            public function getAuthIdentifier()
            {
                return true;
            }
        };
        $user = new class
        {
            public function getAuthIdentifier()
            {
                return true;
            }
        };

        $this->app->userAuthenticated = $user;

        $this->seeIsAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function can_assert_if_someone_is_authenticated()
    {
        $this->app = new class
        {
            public $check;

            public function make()
            {
                return $this;
            }

            public function guard()
            {
                return $this;
            }

            public function check()
            {
                return $this->check;
            }
        };

        $this->app->check = true;
        $this->assertTrue($this->isAuthenticated());

        $this->app->check = false;
        $this->assertFalse($this->isAuthenticated());
    }

    /**
     * @test
     */
    public function assert_if_someone_is_authenticated()
    {
        $this->app = new class
        {
            public $check;

            public function make()
            {
                return $this;
            }

            public function guard()
            {
                return $this;
            }

            public function check()
            {
                return $this->check;
            }
        };

        $this->app->check = true;
        $this->seeIsAuthenticated();

        $this->app->check = false;
        $this->dontSeeIsAuthenticated();
    }
}
