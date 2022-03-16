<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Facades;

use Illuminate\Support\Facades\Facade;

class Esign extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'laravel-esign';
    }
}
