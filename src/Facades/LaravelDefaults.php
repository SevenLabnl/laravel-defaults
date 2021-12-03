<?php

namespace SevenLab\LaravelDefaults\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelDefaults extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-defaults';
    }
}
