<?php

namespace Tests\Adapter;

use Tests\TestCase;
use XNXK\LaravelEsign\Adapter\ResponseException;
use XNXK\LaravelEsign\Adapter\JSONException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ResponseExceptionTest extends TestCase
{
    public function testFromRequestExceptionNoResponse()
    {
        $reqErr = new RequestException('foo', new Request('GET', '/test'));
        $respErr = ResponseException::fromRequestException($reqErr);

        $this->assertInstanceOf(ResponseException::class, $respErr);
        $this->assertEquals($reqErr->getMessage(), $respErr->getMessage());
        $this->assertEquals(0, $respErr->getCode());
        $this->assertEquals($reqErr, $respErr->getPrevious());
    }
}