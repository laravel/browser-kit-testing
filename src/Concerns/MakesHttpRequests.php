<?php

namespace Laravel\BrowserKitTesting\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\BrowserKitTesting\TestResponse;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait MakesHttpRequests
{
    use InteractsWithPages;

    /**
     * The last response returned by the application.
     *
     * @var \Illuminate\Http\Response
     */
    protected $response;

    /**
     * The current URL being viewed.
     *
     * @var string
     */
    protected $currentUri;

    /**
     * Additional server variables for the request.
     *
     * @var array
     */
    protected $serverVariables = [];

    /**
     * Disable middleware for the test.
     *
     * @param null $middleware
     *
     * @return $this
     */
    public function withoutMiddleware($middleware = null)
    {
        if (is_null($middleware)) {
            $this->app->instance('middleware.disable', true);

            return $this;
        }

        foreach ((array) $middleware as $abstract) {
            $this->app->instance($abstract, new class {
                public function handle($request, $next)
                {
                    return $next($request);
                }
            });
        }

        return $this;
    }

    /**
     * Visit the given URI with a JSON request.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $files = $this->extractFilesFromDataArray($data);

        $content = json_encode($data);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);

        $this->call(
            $method, $uri, [], [], $files, $this->transformHeadersToServerVars($headers), $content
        );

        return $this;
    }

    /**
     * Extract the file uploads from the given data array.
     *
     * @param  array  $data
     * @return array
     */
    protected function extractFilesFromDataArray(&$data)
    {
        $files = [];

        foreach ($data as $key => $value) {
            if ($value instanceof SymfonyUploadedFile) {
                $files[$key] = $value;

                unset($data[$key]);
            }
        }

        return $files;
    }

    /**
     * Visit the given URI with a GET request.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return $this
     */
    public function get($uri, array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('GET', $uri, [], [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a GET request, expecting a JSON response.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return $this
     */
    public function getJson($uri, array $headers = [])
    {
        return $this->json('GET', $uri, [], $headers);
    }

    /**
     * Visit the given URI with a POST request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function post($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('POST', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a POST request, expecting a JSON response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function postJson($uri, array $data = [], array $headers = [])
    {
        return $this->json('POST', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function put($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('PUT', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a PUT request, expecting a JSON response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function putJson($uri, array $data = [], array $headers = [])
    {
        return $this->json('PUT', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PATCH request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function patch($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('PATCH', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a PATCH request, expecting a JSON response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function patchJson($uri, array $data = [], array $headers = [])
    {
        return $this->json('PATCH', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function delete($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('DELETE', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a DELETE request, expecting a JSON response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function deleteJson($uri, array $data = [], array $headers = [])
    {
        return $this->json('DELETE', $uri, $data, $headers);
    }

    /**
     * Send the given request through the application.
     *
     * This method allows you to fully customize the entire Request object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function handle(Request $request)
    {
        $this->currentUri = $request->fullUrl();

        $this->response = TestResponse::fromBaseResponse(
            $this->app->prepareResponse($this->app->handle($request))
        );

        return $this;
    }

    /**
     * Assert that the response contains JSON.
     *
     * @param  array|null  $data
     * @return $this
     */
    protected function shouldReturnJson(array $data = null)
    {
        return $this->receiveJson($data);
    }

    /**
     * Assert that the response contains JSON.
     *
     * @param  array|null  $data
     * @return $this|null
     */
    protected function receiveJson(array $data = null)
    {
        return $this->seeJson($data);
    }

    /**
     * Assert that the response contains an exact JSON array.
     *
     * @param  array  $data
     * @return $this
     */
    public function seeJsonEquals(array $data)
    {
        $this->response->assertExactJson($data);

        return $this;
    }

    /**
     * Assert that the response contains JSON.
     *
     * @param  array|null  $data
     * @param  bool  $negate
     * @return $this
     */
    public function seeJson(array $data = null, $negate = false)
    {
        if (is_null($data)) {
            $this->assertJson(
                $this->response->getContent(), "JSON was not returned from [{$this->currentUri}]."
            );

            return $this;
        }

        try {
            return $this->seeJsonEquals($data);
        } catch (ExpectationFailedException $e) {
            return $this->seeJsonContains($data, $negate);
        }
    }

    /**
     * Assert that the response doesn't contain JSON.
     *
     * @param  array|null  $data
     * @return $this
     */
    public function dontSeeJson(array $data = null)
    {
        return $this->seeJson($data, true);
    }

    /**
     * Assert that the JSON response has a given structure.
     *
     * @param  array|null  $structure
     * @param  array|null  $responseData
     * @return $this
     */
    public function seeJsonStructure(array $structure = null, $responseData = null)
    {
        $this->response->assertJsonStructure($structure, $responseData);

        return $this;
    }

    /**
     * Assert that the response contains the given JSON.
     *
     * @param  array  $data
     * @param  bool  $negate
     * @return $this
     */
    protected function seeJsonContains(array $data, $negate = false)
    {
        if ($negate) {
            $this->response->assertJsonMissing($data, false);
        } else {
            $this->response->assertJsonFragment($data);
        }

        return $this;
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @return array
     */
    protected function decodeResponseJson()
    {
        $decodedResponse = json_decode($this->response->getContent(), true);

        if (is_null($decodedResponse) || $decodedResponse === false) {
            $this->fail('Invalid JSON was returned from the route. Perhaps an exception was thrown?');
        }

        return $decodedResponse;
    }

    /**
     * Asserts that the status code of the response matches the given code.
     *
     * @param  int  $status
     * @return $this
     */
    protected function seeStatusCode($status)
    {
        $this->response->assertStatus($status);

        return $this;
    }

    /**
     * Asserts that the response contains the given header and equals the optional value.
     *
     * @param  string  $headerName
     * @param  mixed  $value
     * @return $this
     */
    protected function seeHeader($headerName, $value = null)
    {
        $this->response->assertHeader($headerName, $value);

        return $this;
    }

    /**
     * Asserts that the response contains the given cookie and equals the optional value.
     *
     * @param  string  $cookieName
     * @param  mixed  $value
     * @return $this
     */
    protected function seePlainCookie($cookieName, $value = null)
    {
        return $this->seeCookie($cookieName, $value, false);
    }

    /**
     * Asserts that the response contains the given cookie and equals the optional value.
     *
     * @param  string  $cookieName
     * @param  mixed  $value
     * @param  bool  $encrypted
     * @param  bool  $unserialize
     * @return $this
     */
    protected function seeCookie($cookieName, $value = null, $encrypted = true, $unserialize = true)
    {
        $this->response->assertCookie($cookieName, $value, $encrypted, $unserialize);

        return $this;
    }

    /**
     * Define a set of server variables to be sent with the requests.
     *
     * @param  array  $server
     * @return $this
     */
    protected function withServerVariables(array $server)
    {
        $this->serverVariables = $server;

        return $this;
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string  $content
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');

        $this->currentUri = $this->prepareUrlForRequest($uri);

        $this->resetPageContext();

        $symfonyRequest = SymfonyRequest::create(
            $this->currentUri, $method, $parameters,
            $cookies, $this->filterFiles($files), array_replace($this->serverVariables, $server), $content
        );

        $request = Request::createFromBase($symfonyRequest);

        $response = $kernel->handle($request);

        $kernel->terminate($request, $response);

        return $this->response = TestResponse::fromBaseResponse($response);
    }

    /**
     * Call the given HTTPS URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string  $content
     * @return \Illuminate\Http\Response
     */
    public function callSecure($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $uri = $this->app['url']->secure(ltrim($uri, '/'));

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Call a controller action and return the Response.
     *
     * @param  string  $method
     * @param  string  $action
     * @param  array  $wildcards
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string  $content
     * @return \Illuminate\Http\Response
     */
    public function action($method, $action, $wildcards = [], $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $uri = $this->app['url']->action($action, $wildcards, true);

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Call a named route and return the Response.
     *
     * @param  string  $method
     * @param  string  $name
     * @param  array  $routeParameters
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string  $content
     * @return \Illuminate\Http\Response
     */
    public function route($method, $name, $routeParameters = [], $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $uri = $this->app['url']->route($name, $routeParameters);

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Turn the given URI into a fully qualified URL.
     *
     * @param  string  $uri
     * @return string
     */
    protected function prepareUrlForRequest($uri)
    {
        if (Str::startsWith($uri, '/')) {
            $uri = substr($uri, 1);
        }

        if (! Str::startsWith($uri, 'http')) {
            $uri = $this->baseUrl.'/'.$uri;
        }

        return trim($uri, '/');
    }

    /**
     * Transform headers array to array of $_SERVER vars with HTTP_* format.
     *
     * @param  array  $headers
     * @return array
     */
    protected function transformHeadersToServerVars(array $headers)
    {
        $server = [];
        $prefix = 'HTTP_';

        foreach ($headers as $name => $value) {
            $name = strtr(strtoupper($name), '-', '_');

            if (! Str::startsWith($name, $prefix) && $name != 'CONTENT_TYPE') {
                $name = $prefix.$name;
            }

            $server[$name] = $value;
        }

        return $server;
    }

    /**
     * Filter the given array of files, removing any empty values.
     *
     * @param  array  $files
     * @return mixed
     */
    protected function filterFiles($files)
    {
        foreach ($files as $key => $file) {
            if ($file instanceof UploadedFile) {
                continue;
            }

            if (is_array($file)) {
                if (! isset($file['name'])) {
                    $files[$key] = $this->filterFiles($files[$key]);
                } elseif (isset($files[$key]['error']) && $files[$key]['error'] !== 0) {
                    unset($files[$key]);
                }

                continue;
            }

            unset($files[$key]);
        }

        return $files;
    }
}
