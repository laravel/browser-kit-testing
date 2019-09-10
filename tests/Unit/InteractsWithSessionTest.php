<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Illuminate\Foundation\Application;
use Laravel\BrowserKitTesting\Concerns\InteractsWithSession;
use Laravel\BrowserKitTesting\Tests\TestCase;

class InteractsWithSessionTest extends TestCase
{
    use InteractsWithSession;

    /**
     * @test
     */
    public function session_method_can_add_data_on_session()
    {
        $this->app['session'] = new class {
            protected $put = 0;

            public function isStarted()
            {
                return true;
            }

            public function put()
            {
                return $this->put++;
            }

            public function wasCalledPutMethod($times)
            {
                return $times == $this->put;
            }
        };
        $this->app['session.store'] = new class {
        };

        $this->session([
            'foo' => 'bar',
            'unit' => 'test',
        ]);

        $this->assertTrue($this->app['session']->wasCalledPutMethod(2));
    }

    /**
     * @test
     */
    public function withSession_method_can_add_data_on_session()
    {
        $this->app['session'] = new class {
            protected $put = 0;

            public function isStarted()
            {
                return true;
            }

            public function put()
            {
                return $this->put++;
            }

            public function wasCalledPutMethod($times)
            {
                return $times == $this->put;
            }
        };
        $this->app['session.store'] = new class {
        };

        $this->withSession([
            'foo' => 'bar',
            'unit' => 'test',
        ]);

        $this->assertTrue($this->app['session']->wasCalledPutMethod(2, 'put'));
    }

    /**
     * @test
     */
    public function can_start_session()
    {
        $this->app['session'] = new class {
            public $start = false;

            public function isStarted()
            {
                return $this->start;
            }

            public function start()
            {
                return $this->start = true;
            }
        };

        $this->assertFalse($this->app['session']->isStarted());
        $this->startSession();
        $this->assertTrue($this->app['session']->isStarted());
    }

    /**
     * @test
     */
    public function can_flush_session()
    {
        $this->app['session'] = new class {
            protected $flush = false;

            public function isStarted()
            {
                return true;
            }

            public function flush()
            {
                $this->flush = true;
            }

            public function isCalledFlushMethod()
            {
                return $this->flush;
            }
        };

        $this->assertFalse($this->app['session']->isCalledFlushMethod());
        $this->flushSession();
        $this->assertTrue($this->app['session']->isCalledFlushMethod());
    }

    /**
     * @test
     */
    public function check_if_exists_data_on_session_and_check_exist_key()
    {
        $this->app['session'] = new class {
        };
        $this->app['session.store'] = new class {
            public function get($key)
            {
                return 'bar';
            }

            public function has($key)
            {
                return true;
            }
        };

        $this->seeInSession('foo', 'bar');
        $this->seeInSession('foo');
        $this->assertSessionHas('foo', 'bar');
        $this->seeInSession('foo');
    }

    /**
     * @test
     */
    public function check_multi_data_on_session_and_check_multi_keys()
    {
        $this->app['session'] = new class {
        };
        $this->app['session.store'] = new class {
            protected $data = [
                'foo' => 'bar',
                'unit' => 'test',
            ];

            public function get($key)
            {
                return $this->data[$key];
            }

            public function has($key)
            {
                return true;
            }
        };

        $this->assertSessionHas([
            'foo' => 'bar',
            'unit' => 'test',
        ]);
        $this->assertSessionHas(['foo', 'unit']);

        $this->assertSessionHasAll([
            'foo' => 'bar',
            'unit' => 'test',
        ]);
        $this->assertSessionHasAll(['foo', 'unit']);
    }

    /**
     * @test
     */
    public function check_not_exists_key_and_multi_key_on_session()
    {
        $this->app['session'] = new class {
        };
        $this->app['session.store'] = new class {
            public function has($key)
            {
                return false;
            }
        };
        $this->assertSessionMissing('foo');
        $this->assertSessionMissing(['foo', 'bar']);
    }

    /**
     * @test
     */
    public function check_if_exists_errors_on_session()
    {
        $this->app['session'] = new class {
        };
        $this->app['session.store'] = new class {
            public function get($key)
            {
                return $this;
            }

            public function has($key)
            {
                return true;
            }
        };
        $this->assertSessionHasErrors(['foo', 'bar']);
    }

    /**
     * @test
     */
    public function check_if_exists_errors_with_value_on_session()
    {
        $this->app = new Application();
        $this->app['session.store'] = new class {
            public function get($key)
            {
                return new class {
                    public function get($key)
                    {
                        return ['bar'];
                    }
                };
            }

            public function has($key)
            {
                return true;
            }
        };
        $this->assertSessionHasErrors(['foo' => 'bar']);
    }

    /**
     * @test
     */
    public function check_if_exists_old_input_on_session()
    {
        $this->app['session'] = new class {
        };
        $this->app['session.store'] = new class {
            public function has($key)
            {
                return true;
            }
        };
        $this->assertHasOldInput(true);
    }
}
