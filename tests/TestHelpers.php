<?php

declare(strict_types=1);

namespace Tests;

use XNXK\LaravelEsign\Esign;

class TestHelpers extends TestCase
{
    public function __construct()
    {
        parent::setUp();
    }
    
    /**
     * Helper to easy load an esign test method or the api.
     *
     * @return Esign
     */
    public function esign(): Esign
    {
        return new Esign(env('ESIGN_APPID'), env('ESIGN_SECRET'));
    }
}