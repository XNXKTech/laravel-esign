<?php

namespace Tests\Auth;

use Tests\TestCase;
use XNXK\LaravelEsign\Auth\Token;

class APIKeyTest extends TestCase
{
    public function testGetHeaders()
    {
        $auth    = new Token(env('ESIGN_APPID'), env('ESIGN_SECRET'));
        $headers = $auth->getHeaders();

        $this->assertArrayHasKey('Accept',$headers);
        $this->assertArrayHasKey('X-Tsign-Open-App-Id', $headers);
        $this->assertArrayHasKey('X-Tsign-Open-Ca-Timestamp', $headers);
        $this->assertArrayHasKey('X-Tsign-Open-Ca-Signature', $headers);
        $this->assertArrayHasKey('Content-MD5', $headers);
        $this->assertArrayHasKey('Content-Type',$headers);
        $this->assertArrayHasKey('X-Tsign-Open-Auth-Mode', $headers);

        $this->assertCount(7, $headers);
    }
}
