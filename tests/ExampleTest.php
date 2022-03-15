<?php

declare(strict_types=1);

namespace Tests;

use XNXK\LaravelEsign\Esign;
use XNXK\LaravelEsign\EsignRefactor;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
       print(
           (new EsignRefactor('token', 'token'))->account()->queryPersonalAccountByAccountId('test'));
    }
}
