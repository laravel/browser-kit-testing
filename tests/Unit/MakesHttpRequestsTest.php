<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use InvalidArgumentException;
use Laravel\BrowserKitTesting\HttpException;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests;

class MakesHttpRequestsTest extends TestCase
{
    use MakesHttpRequests;

    /**
     * @test
     */
    public function type_method_write_on_input()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" id="name"/>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);
        $name = 'Taylor';

        $this->type($name, 'name');
        $this->assertSame($this->inputs['name'], $name);
    }

    /**
     * @test
     */
    public function check_method_check_checkbox()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="checkbox" id="terms-conditions"/>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $this->check('terms-conditions');
        $this->assertTrue($this->inputs['terms-conditions']);
    }

    /**
     * @test
     */
    public function uncheck_method_uncheck_checkbox()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="checkbox" id="terms-conditions" checked/>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $this->uncheck('terms-conditions');
        $this->assertFalse($this->inputs['terms-conditions']);
    }

    /**
     * @test
     */
    public function select_method_select_an_option_from_drop_down()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <select name="role" id="role">
                        <option value="user"></option>
                        <option value="auxiliar"></option>
                        <option value="admin"></option>
                    </select>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $role = 'user';
        $this->select($role, 'role');
        $this->assertSame($this->inputs['role'], $role);
    }

    /**
     * @test
     */
    public function attach_method_attach_a_file()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="file" id="avatar">
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $avatar = '/path/to/my-avatar.png';
        $this->attach($avatar, 'avatar');

        $this->assertSame($this->inputs['avatar'], $avatar);
        $this->assertSame($this->uploads['avatar'], $avatar);
    }

     /**
      * @test
      */
    public function storeInput_method_store_a_form_input_in_the_local_array()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" id="name"/>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);
        $this->assertEmpty($this->inputs);

        $name = 'Taylor';
        $this->storeInput('name', $name);
        $this->assertSame($this->inputs['name'], $name);

        $this->clearInputs();

        $this->storeInput('name', true);
        $this->assertTrue($this->inputs['name']);

        $this->clearInputs();

        $this->storeInput('name', false);
        $this->assertFalse($this->inputs['name']);

        $this->clearInputs();

        $name = 2;
        $this->storeInput('name', $name);
        $this->assertSame($this->inputs['name'], $name);
    }

    /**
     * @test
     */
    public function when_input_dont_exist_storeInput_throw_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Nothing matched the filter [name] CSS query provided for [].');

        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" id="email"/>
                    <button class="btn" type="submit">Search</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);
        $this->assertEmpty($this->inputs);

        $this->storeInput('name', 'Taylor');
    }

    /**
     * @test
     */
    public function getForm_method_returns_Form_from_page_with_the_given_submit_button_text()
    {
        $html = '<html>
            <body>
                <form action="http://localhost" method="POST">
                    <button type="submit">Send</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $this->assertInstanceOf(\Symfony\Component\DomCrawler\Form::class, $this->getForm('Send'));
        $this->assertInstanceOf(\Symfony\Component\DomCrawler\Form::class, $this->getForm());
    }

    /**
     * @test
     */
    public function when_exists_button_getForm_method_throw_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find a form that has submit button [Search].');

        $html = '<html>
            <body>
                <form action="http://localhost" method="POST">
                    <button type="submit">Send</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $this->assertInstanceOf(\Symfony\Component\DomCrawler\Form::class, $this->getForm('Search'));
    }

    /**
     * @test
     */
    public function fillForm_method_return_Form_with_the_given_data()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" name="name" id="name"/>
                    <button class="btn" type="submit">Send</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);
        $form = $this->fillForm('Send', ['name' => 'Taylor']);
        $this->assertInstanceOf(\Symfony\Component\DomCrawler\Form::class, $form);
        $this->assertSame('Taylor', $form->get('name')->getValue());
    }

    /**
     * @test
     */
    public function fillForm_method_return_Form_when_given_array_data()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" name="name" id="name"/>
                    <button class="btn" type="submit">Send</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);
        $form = $this->fillForm(['name' => 'Taylor']);
        $this->assertInstanceOf(\Symfony\Component\DomCrawler\Form::class, $form);
        $this->assertSame('Taylor', $form->get('name')->getValue());
    }

    /**
     * @test
     * @dataProvider dataUrls
     */
    public function prepareUrlForRequest_method_return_all_url($url, $expectedUrl)
    {
        $this->baseUrl = 'http://localhost';
        $this->assertSame(
            $this->prepareUrlForRequest($url),
            $expectedUrl
        );
    }

    public function dataUrls()
    {
        return [
            ['', 'http://localhost'],
            ['/', 'http://localhost'],
            ['users', 'http://localhost/users'],
            ['/users', 'http://localhost/users'],
            ['users/', 'http://localhost/users'],
            ['/users/', 'http://localhost/users']
        ];
    }

    /**
     * @test
     */
    public function resetPageContext_method_clear_crawler_subcrawlers()
    {
        $body = '<body>
            <form action="https://localhost" method="post">
                <input type="text" id="name">
                <button class="btn" type="submit">Search</button>
            </form>
        </body>';
        $this->createPage($body);

        $this->within('form', function () {
            $this->assertInstanceOf(
                \Symfony\Component\DomCrawler\Crawler::class,
                $this->crawler
            );
            $this->assertInstanceOf(
                \Symfony\Component\DomCrawler\Crawler::class,
                $this->subCrawlers[0]
            );

            // Clear Crawler and SubCrawlers
            $this->resetPageContext();
            $this->assertNull($this->crawler);
            $this->assertEmpty($this->subCrawlers);
        });
    }

    /**
     * @test
     */
    public function clearInputs_method_clear_all_inputs_and_uploads()
    {
        $avatar = '/path/to/my-avatar.png';
        $this->inputs = [
            'avatar' => $avatar
        ];
        $this->uploads = [
            'avatar' => $avatar
        ];

        $this->clearInputs();
        $this->assertEmpty($this->inputs);
        $this->assertEmpty($this->uploads);
    }

    /**
     * @test
     */
    public function extractParametersFromForm_extract_parameter_of_form()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post">
                    <input type="text" name="name" id="name"/>
                    <input type="text" name="email" id="email"/>
                    <input type="text" name="github" id="github"/>
                    <button class="btn" type="submit">Register</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        $form = $this->crawler()->filter('form')->form();
        $this->assertSame(
            $this->extractParametersFromForm($form),
            ['name' => '', 'email' => '', 'github' => '']
        );
    }

    /**
     * @test
     */
    public function convertUploadsForTesting_converter_uploads_to_UploadedFile_instances()
    {
        $html = '<html>
            <body>
                <form action="https://localhost" method="post" enctype="multipart/form-data">
                    <input type="file" name="avatar" id="avatar"/>
                    <input type="file" name="photos[]" id="photos[]"/>
                    <button class="btn" type="submit">Send</button>
                </form>
            </body>
        </html>';
        $this->createPage($html);

        // Attach Files...
        $this->attach('/path/to/my-avatar.png', 'avatar');
        $this->attach('/path/to/my-wallpaper.png', 'photos[]');

        $form = $this->crawler()->filter('form')->form();

        $uploads = $this->convertUploadsForTesting($form, $this->uploads);

        $this->assertEmpty($uploads['avatar']);
        $this->assertEmpty($uploads['photos'][0]);
    }

    /**
     * @test
     */
    public function assertPageLoaded_check_that_the_page_was_loaded()
    {
        $this->app = null;
        $this->response = new class {
            public function getStatusCode() { return 200; }
        };
        $uri = 'http://localhost/login';
        $this->assertPageLoaded($uri);
    }

    /**
     * @test
     */
    public function assertPageLoaded_throw_exception_when_the_page_was_not_loaded_correctly()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('A request to [http://localhost/login] failed. Received status code [404].');

        $this->app = null;
        $this->response = new class {
            public function getStatusCode() { return 404; }
        };
        $uri = 'http://localhost/login';
        $this->assertPageLoaded($uri);
    }

    /**
     * @test
     */
    public function seeStatusCode_check_status_code()
    {
        $this->response = new class {
            public function getStatusCode() { return 200; }
        };
        $this->seeStatusCode(200);
    }

    /**
     * @test
     */
    public function assertResponseOk_check_that_the_status_page_should_be_200()
    {
        $this->response = new class {
            public function getStatusCode() { return 200; }
            public function isOk() { return true; }
        };
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function assertResponseOk_throw_exception_when_the_status_page_is_not_200()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected status code 200, got 404.');

        $this->response = new class {
            public function getStatusCode() { return 404; }
            public function isOK() { return false; }
        };
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function assertResponseStatus_check_the_response_status_is_equal_to_passed_by_parameter()
    {
        $this->response = new class {
            public function getStatusCode() { return 200; }
        };
        $this->assertResponseStatus(200);
    }

    /**
     * @test
     */
    public function assertResponseStatus_throw_exception_when_the_response_status_is_not_equal_to_passed_by_parameter()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected status code 404, got 200.');

        $this->response = new class {
            public function getStatusCode() { return 200; }
        };
        $this->assertResponseStatus(404);
    }
}
