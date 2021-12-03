<?php

use Orchestra\Testbench\TestCase;
use SevenLab\LaravelDefaults\Http\Middleware\LogAfterRequest;

class LogAfterRequestTest extends TestCase
{
    public function test_a_request_will_be_logged(): void
    {
        Log::shouldReceive('info')
            ->once();

        $request = new \Illuminate\Http\Request();

        $response = new \Illuminate\Http\Response();

        $middleware = new LogAfterRequest();

        $middleware->handle($request, static function () {
        });

        $middleware->terminate($request, $response);
    }

    public function test_the_middleware_is_loaded_by_default(): void
    {
        $this->mock(LogAfterRequest::class)
            ->shouldReceive('handle');

        $this->get('/');
    }

    public function test_the_middleware_can_be_disabled(): void
    {
        $this->mock(LogAfterRequest::class)
            ->shouldNotReceive('handle');

        config(['laravel-defaults.log-after-request' => false]);

        $this->get('/');
    }
}
