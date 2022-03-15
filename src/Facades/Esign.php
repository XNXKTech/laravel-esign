<?php

namespace XNXK\LaravelEsign\Facades;

use Illuminate\Support\Facades\Facade;

class Esign extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-esign';
    }
}
