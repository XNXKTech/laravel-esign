<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Auth;

class Token implements Auth
{
    private string $appid;
    private string $secret;

    public function __construct(string $appid, string $secret)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }

    public function getHeaders(string $method, string $uri, array $data, array $headers): array
    {
        $signatureHeaders = [
            'Accept' => '*/*',
            'Content-MD5' => getContentMd5(json_encode($data, JSON_UNESCAPED_SLASHES)),
            'Content-Type' => 'application/json; charset=UTF-8',
            'X-Tsign-Open-App-Id' => $this->appid,
            'X-Tsign-Open-Ca-Timestamp' => getMillisecond(),
            'X-Tsign-Open-Auth-Mode' => 'Signature',
        ];
        $signatureHeaders['X-Tsign-Open-Ca-Signature'] = getSignature(
            strtoupper($method),
            $signatureHeaders['Accept'],
            $signatureHeaders['Content-Type'],
            $signatureHeaders['Content-MD5'],
            date('D, d M Y G:i:s T'),
            getHeadersToString($headers),
            $uri,
            $this->secret
        );

        return array_merge($signatureHeaders, $headers);
    }
}
