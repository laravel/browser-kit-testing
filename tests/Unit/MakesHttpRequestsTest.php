<?php

namespace Tests\Unit;

use Tests\TestCase;
use InvalidArgumentException;
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Nothing matched the filter [name] CSS query provided for [].
     */
    public function when_input_dont_exist_storeInput_throw_exception()
    {
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Could not find a form that has submit button [Search].
     */
    public function when_exists_button_getForm_method_throw_exception()
    {
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
        $this->assertSame('Taylor', $form->get('name')->getValue());
    }
}
