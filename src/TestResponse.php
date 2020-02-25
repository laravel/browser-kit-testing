<?php

namespace Laravel\BrowserKitTesting;

use Illuminate\Testing\Assert as PHPUnit;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TestResponse extends \Illuminate\Testing\TestResponse
{
    /**
     * Assert that the client response has an OK status code.
     *
     * @return $this
     */
    public function assertResponseOk()
    {
        return $this->assertOk();
    }

    /**
     * Assert that the client response has a given code.
     *
     * @param  int  $code
     * @return $this
     */
    public function assertResponseStatus($code)
    {
        return $this->assertStatus($code);
    }

    /**
     * Assert whether the client was redirected to a given URI.
     *
     * @param  string  $uri
     * @param  array  $with
     * @return $this
     */
    public function assertRedirectedTo($uri, $with = [])
    {
        PHPUnit::assertInstanceOf(RedirectResponse::class, $this->baseResponse);

        $this->assertRedirect($uri);

        $this->assertSessionHasAll($with);

        return $this;
    }

    /**
     * Assert whether the client was redirected to a given route.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  array  $with
     * @return $this
     */
    public function assertRedirectedToRoute($name, $parameters = [], $with = [])
    {
        return $this->assertRedirectedTo(app('url')->route($name, $parameters), $with);
    }

    /**
     * Assert whether the client was redirected to a given action.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  array  $with
     * @return $this
     */
    public function assertRedirectedToAction($name, $parameters = [], $with = [])
    {
        return $this->assertRedirectedTo(app('url')->action($name, $parameters), $with);
    }
}
