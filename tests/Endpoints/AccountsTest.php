<?php

declare(strict_types=1);

namespace Tests\Endpoints;

use Tests\TestCase;
use XNXK\LaravelEsign\Esign;

class AccountsTest extends TestCase
{
    public function testGetUserID()
    {
        (new Esign(env('ESIGN_APPID'), env('ESIGN_SECRET')))->account()->queryPersonalAccountByThirdId('testid');
    }
}
