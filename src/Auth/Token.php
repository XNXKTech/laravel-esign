<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Auth;

class Token implements Auth
{
    private string $token;
    private string $appid;

    public function __construct(string $appid, string $apiToken)
    {
        $this->appid = $appid;
        $this->token = $apiToken;
    }

    public function getHeaders(): array
    {
        return [
            'X-Tsign-Open-App-Id' => $this->appid,
            'X-Tsign-Open-Ca-Timestamp' => getMillisecond(),
            'X-Tsign-Open-Ca-Signature' => $this->token,
            'Accept' => '*/*',
            'Content-MD5' => '',
            'Content-Type' => 'application/json; charset=UTF-8',
            'X-Tsign-Open-Auth-Mode' => 'Signature',
        ];
    }
}
