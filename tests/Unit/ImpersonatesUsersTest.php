<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Laravel\BrowserKitTesting\Concerns\ImpersonatesUsers;
use Laravel\BrowserKitTesting\Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;

class ImpersonatesUsersTest extends TestCase
{
    use ImpersonatesUsers;

    /**
     * @test
     */
    public function set_currently_logged_in_user_for_app()
    {
        $user = new class implements Authenticatable {
            public function getAuthIdentifierName() {}
            public function getAuthIdentifier() {}
            public function getAuthPassword() {}
            public function getRememberToken() {}
            public function setRememberToken($value) {}
            public function getRememberTokenName() {}
        };

        $this->app['auth'] = new class {
            public $user;
            public function guard() { return $this; }
            public function setUser($user) { return $this->user = $user; }
        };

        $this->be($user);
        $this->assertInstanceOf(get_class($user), $this->app['auth']->user);

        $this->actingAs($user);
        $this->assertInstanceOf(get_class($user), $this->app['auth']->user);
    }
}
