<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Illuminate\Http\Response;
use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests;
use Laravel\BrowserKitTesting\TestResponse;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\ExpectationFailedException;

class MakesHttpRequestsTest extends TestCase
{
    use MakesHttpRequests;

    protected $baseUrl;

    #[Test]
    #[DataProvider('dataUrls')]
    public function prepare_url_for_request_method_return_all_url($url, $expectedUrl)
    {
        $this->baseUrl = 'http://localhost';
        $this->assertSame(
            $this->prepareUrlForRequest($url),
            $expectedUrl
        );
    }

    public static function dataUrls()
    {
        return [
            ['', 'http://localhost'],
            ['/', 'http://localhost'],
            ['users', 'http://localhost/users'],
            ['/users', 'http://localhost/users'],
            ['users/', 'http://localhost/users'],
            ['/users/', 'http://localhost/users'],
        ];
    }

    #[Test]
    public function see_status_code_check_status_code()
    {
        $this->response = TestResponse::fromBaseResponse(new class extends Response
        {
            public function getStatusCode(): int
            {
                return 200;
            }
        });

        $this->seeStatusCode(200);
    }

    #[Test]
    public function assert_response_ok_check_that_the_status_page_should_be_200()
    {
        $this->response = TestResponse::fromBaseResponse(new class extends Response
        {
            public function getStatusCode(): int
            {
                return 200;
            }

            public function isOk(): bool
            {
                return true;
            }
        });

        $this->response->assertResponseOk();
    }

    #[Test]
    public function assert_response_ok_throw_exception_when_the_status_page_is_not_200()
    {
        $this->expectException(ExpectationFailedException::class);

        $this->response = TestResponse::fromBaseResponse(new class extends Response
        {
            public function getStatusCode(): int
            {
                return 404;
            }

            public function isOk(): bool
            {
                return false;
            }
        });

        $this->response->assertResponseOk();
    }

    #[Test]
    public function assert_response_status_check_the_response_status_is_equal_to_passed_by_parameter()
    {
        $this->response = TestResponse::fromBaseResponse(new class extends Response
        {
            public function getStatusCode(): int
            {
                return 200;
            }
        });

        $this->response->assertResponseStatus(200);
    }

    #[Test]
    public function assert_response_status_throw_exception_when_the_response_status_is_not_equal_to_passed_by_parameter()
    {
        $this->expectException(ExpectationFailedException::class);

        $this->response = TestResponse::fromBaseResponse(new class extends Response
        {
            public function getStatusCode(): int
            {
                return 200;
            }
        });

        $this->response->assertResponseStatus(404);
    }

    public function test_with_cookie_set_cookie()
    {
        $this->withCookie('foo', 'bar');

        $this->assertCount(1, $this->defaultCookies);
        $this->assertSame('bar', $this->defaultCookies['foo']);
    }

    public function test_with_cookies_sets_cookies_and_overwrites_previous_values()
    {
        $this->withCookie('foo', 'bar');
        $this->withCookies([
            'foo' => 'baz',
            'new-cookie' => 'new-value',
        ]);

        $this->assertCount(2, $this->defaultCookies);
        $this->assertSame('baz', $this->defaultCookies['foo']);
        $this->assertSame('new-value', $this->defaultCookies['new-cookie']);
    }
}
