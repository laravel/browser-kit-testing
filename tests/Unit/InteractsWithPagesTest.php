<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use DOMDocument;
use Exception;
use InvalidArgumentException;
use Laravel\BrowserKitTesting\Concerns\InteractsWithPages;
use Laravel\BrowserKitTesting\HttpException;
use Laravel\BrowserKitTesting\Tests\TestCase;

class InteractsWithPagesTest extends TestCase
{
    use InteractsWithPages;

    protected $returns = [
        'prepareUrlForRequest' => null,
        'call' => null
    ];

    public function call()
    {
        return $this->returns['call'];
    }

    public function prepareUrlForRequest($uri)
    {
        return $this->returns['prepareUrlForRequest'];
    }

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

        $this->currentUri = '';

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
            'avatar' => $avatar,
        ];
        $this->uploads = [
            'avatar' => $avatar,
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
            public function getStatusCode()
            {
                return 200;
            }
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
            public function getStatusCode()
            {
                return 404;
            }
        };
        $uri = 'http://localhost/login';
        $this->assertPageLoaded($uri);
    }

    /**
     * @test
     */
    public function assertPageLoaded_throw_exception_with_response_exception()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('A request to [http://localhost/login] failed. Received status code [500].');

        $this->app = null;
        $this->response = new class {
            public $exception;

            public function __construct()
            {
                $this->exception = new Exception('System failure.');
            }

            public function getStatusCode()
            {
                return 500;
            }
        };
        $uri = 'http://localhost/login';
        $this->assertPageLoaded($uri);
    }

    /**
     * @test
     */
    public function crawler_method_return_first_subCrawler()
    {
        $body = '<body>
            <div class="card-user">
                <h3>John Doe</h3> <a href="#">show</a>
            </div>
            <div class="card-user">
                <h3>Not User</h3> <a href="#">show</a>
            </div>
        </body>';
        $this->createPage($body);

        $this->within('.card-user > h3', function () {
            $this->assertEquals(
                'John Doe',
                $this->crawler()->text()
            );
        });
    }

    /**
     * @test
     * @dataProvider attributes_UploadedFile
     */
    public function create_UploadedFile_for_testing($file, $uploads, $name)
    {
        $file = $this->getUploadedFileForTesting(
            $file, $uploads, $name
        );
        $this->assertInstanceOf(
            \Illuminate\Http\UploadedFile::class,
            $file
        );
        $this->assertEquals('avatar.png', $file->getClientOriginalName());
        $this->assertEquals('txt/plain', $file->getClientMimeType());
        $this->assertEquals(0, $file->getClientSize());
    }

    public function attributes_UploadedFile()
    {
        return [
            [
                [
                    'error' => null,
                    'name' => 'avatar.png',
                    'tmp_name' => __DIR__.'/../Fixture/file.txt',
                    'type' => 'txt/plain',
                    'size' => 0,
                ],
                [],
                'avatar',
            ],
            [
                [
                    'error' => null,
                    'tmp_name' => __DIR__.'/../Fixture/file.txt',
                    'type' => 'txt/plain',
                    'size' => 0,
                ],
                ['avatar' => 'avatar.png'],
                'avatar',
            ],
        ];
    }

    /**
     * @test
     */
    public function getUploadedFileForTesting_return_null_if_it_can_not_upload_file()
    {
        $this->assertNull(
            $this->getUploadedFileForTesting(
                ['error' => UPLOAD_ERR_NO_FILE], [], ''
            )
        );
    }

    /**
     * @test
     */
    public function see_on_current_HTML()
    {
        $body = '<body>
            <h3>Hello, <strong>User</strong></h3>
        </body>';
        $this->createPage($body);

        $this->dontSee('Hello, User');
        $this->see('Hello, <strong>User</strong>');
    }

    /**
     * @test
     */
    public function see_element_on_current_HTML()
    {
        $body = '<body>
            <img src="avatar.png" alt="ups"/>
        </body>';
        $this->createPage($body);

        $this->dontSeeElement('img', ['src' => 'unknown.png']);
        $this->seeElement('img', ['src' => 'avatar.png', 'alt' => 'ups']);
    }

    /**
     * @test
     */
    public function count_elements_on_current_HTML()
    {
        $body = '<body>
            <div class="card-user">...</div>
            <div class="card-user">...</div>
        </body>';
        $this->createPage($body);

        $this->seeElementCount('.card-user', 2);
    }

    /**
     * @test
     */
    public function see_text_on_current_HTML()
    {
        $body = '<body>
            <h3>Hello, <strong>User</strong></h3>
        </body>';
        $this->createPage($body);

        $this->dontSeeText('Hello, <strong>User</strong>');
        $this->seeText('Hello, User');
    }

    /**
     * @test
     */
    public function see_html_on_element()
    {
        $body = '<body>
            <h3>Hello, <strong>User</strong></h3>
        </body>';
        $this->createPage($body);

        $this->dontSeeInElement('h3', 'Hello, User');
        $this->seeInElement('h3', 'Hello, <strong>User</strong>');
    }

    /**
     * @test
     */
    public function see_value_on_field()
    {
        $body = '<body>
            <form>
                <input type="text" name="email" value="john.doe@testing.com"/>
            </form>
        </body>';
        $this->createPage($body);

        $this->dontSeeInField('email', 'unknown@testing.com');
        $this->seeInField('email', 'john.doe@testing.com');
    }

    /**
     * @test
     */
    public function see_selected_value_on_select_tag()
    {
        $body = '<body>
            <select name="role">
                <option value="auxiliar">User Auxiliar</option>
                <option value="user">User</option>
                <option value="sales" selected>User Sales</option>
            </select>
        </body>';
        $this->createPage($body);

        $this->dontSeeIsSelected('role', 'User Sales');
        $this->seeIsSelected('role', 'sales');
    }

    /**
     * @test
     */
    public function is_checked_checkbox()
    {
        $body = '<body>
            <input type="checkbox" name="active" checked/>
            <input type="checkbox" name="feedback"/>
        </body>';
        $this->createPage($body);

        $this->dontSeeIsChecked('feedback');
        $this->seeIsChecked('active');
    }

    /**
     * @test
     */
    public function see_text_on_link()
    {
        $body = '<body>
            <a href="/users/1">Show <strong>details</strong></a>
        </body>';
        $this->createPage($body);

        $this->dontSeeLink('Show <strong>details</strong>');
        $this->seeLink('Show details');
    }

    /**
     * @test
     */
    public function seePageIs_assert_the_current_page_matches_a_given_uri()
    {
        $expectedUri = 'http://localhost/users';
        $currentUri = 'http://localhost/users';

        $this->currentUri = $currentUri;
        $this->returns['prepareUrlForRequest'] = $expectedUri;
        $this->response = new class {
            public function getStatusCode() { return 200; }
        };

        $this->seePageIs($expectedUri);
    }

    /**
     * @test
     */
    public function seeRouteIs_assert_the_current_page_matches_a_given_named_route()
    {
        $expectedUri = 'http://localhost/users';
        $currentUri = 'http://localhost/users';

        // Bind Anonymus Class to url alias
        $uriGenerator = new class {
            public function route() { return 'http://localhost/users'; }
        };
        app()->bind(get_class($uriGenerator));
        app()->alias(get_class($uriGenerator), 'url');


        $this->currentUri = $currentUri;
        $this->returns['prepareUrlForRequest'] = $expectedUri;
        $this->response = new class {
            public function getStatusCode() { return 200; }
        };

        $this->seeRouteIs('users');
    }

    /**
     * @test
     */
    public function makeRequest_make_request_to_the_application_and_create_crawler()
    {

        $expectedUri = 'http://localhost/users';
        $this->returns['prepareUrlForRequest'] = $expectedUri;
        $this->response = new class {

            public function isRedirect() {}
            public function getStatusCode() { return 200; }
            public function getContent() {
               $dom = new DOMDocument;
               $dom->loadHTML('<body></body>');
               return $dom;
            }
        };
        $this->app = new class {
            public function make() { return $this; }
            public function fullUrl() { return 'http://localhost/users'; }
        };

        $this->makeRequest('GET', 'users');
    }

    /**
     * @test
     */
    public function visit_to_page_a_given_uri()
    {
        $this->response = new class {
            public function isRedirect() {}
            public function getStatusCode() { return 200; }
            public function getContent() {
                $dom = new DOMDocument;
                $dom->loadHTML('<body></body>');
                return $dom;
            }
        };
        $this->app = new class {
            public function make() { return $this; }
            public function fullUrl() {}
        };

        $this->visit('users');
    }

    /**
     * @test
     */
    public function visitRoute_to_page_a_given_named_route()
    {
        // Bind Anonymus Class to url alias
        $uriGenerator = new class {
            public function route() { return 'http://localhost/users'; }
        };
        app()->bind(get_class($uriGenerator));
        app()->alias(get_class($uriGenerator), 'url');

        $this->response = new class {
            public function isRedirect() {}
            public function getStatusCode() { return 200; }
            public function getContent() {
                $dom = new DOMDocument;
                $dom->loadHTML('<body></body>');
                return $dom;
            }
        };
        $this->app = new class {
            public function make() { return $this; }
            public function fullUrl() {}
        };

        $this->visitRoute('users');
    }
}
