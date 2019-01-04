<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Laravel\BrowserKitTesting\Concerns\InteractsWithAuthentication;
use Laravel\BrowserKitTesting\Tests\TestCase;

class InteractsWithAuthenticationTest extends TestCase
{
    use InteractsWithAuthentication;

    /**
     * @test
     */
    public function hasCredentials_return_true_if_the_credentials_are_valid()
    {
        $this->app = new class {
            public function make() { return $this; }
            public function guard() { return $this; }
            public function getProvider() { return $this; }
            public function retrieveByCredentials() { return true; }
            public function validateCredentials() { return true; }
        };
        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret'
        ];
        $this->assertTrue($this->hasCredentials($credentials));
    }

    /**
     * @test
     * @dataProvider DataHasCredentials
     */
    public function hasCredentials_return_false_if_the_credentials_arent_valid($validateCredentials, $retrieveByCredentials)
    {
        $this->app = new class {
            public $retrieveByCredentials;
            public $validateCredentials;
            public function make() { return $this; }
            public function guard() { return $this; }
            public function getProvider() { return $this; }
            public function retrieveByCredentials() { return $this->retrieveByCredentials; }
            public function validateCredentials() { return $this->validateCredentials; }
        };
        $this->app->retrieveByCredentials = $retrieveByCredentials;
        $this->app->validateCredentials = $validateCredentials;
        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret'
        ];
        $this->assertFalse($this->hasCredentials($credentials));
    }

    public function DataHasCredentials()
    {
        return [
            "Case 01" => [false, true],
            "Case 02" => [false, false],
            "Case 03" => [true, false]
        ];
    }

    /**
     * @test
     */
    public function assert_if_credentials_are_valid_or_invalid()
    {
        $this->app = new class {
            public $retrieveByCredentials;
            public $validateCredentials;
            public function make() { return $this; }
            public function guard() { return $this; }
            public function getProvider() { return $this; }
            public function retrieveByCredentials() { return $this->retrieveByCredentials; }
            public function validateCredentials() { return $this->validateCredentials; }
        };
        $this->app->retrieveByCredentials = true;
        $this->app->validateCredentials = true;
        $credentials = [
            'email' => 'john.doe@testing.com',
            'password' => 'secret'
        ];
        $this->seeCredentials($credentials);
        $this->app->retrieveByCredentials = false;
        $this->dontSeeCredentials($credentials);
    }
}
