<?php

declare(strict_types=1);

namespace Tests;

use XNXK\LaravelEsign\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('appId', env('ESIGN_APPID'));
        $app['config']->set('secret', env('ESIGN_SERVER'));
        $app['config']->set('server', env('ESIGN_SERVER'));
    }
}