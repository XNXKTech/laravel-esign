<?php

declare(strict_types=1);

use XNXK\LaravelEsign\Esign;

if (!function_exists('esign')) {
    /**
     * Helper to easy load an esign method or the api.
     *
     * @param  string|null  $method esign method name
     */
    function esign(?string $method = null): Esign
    {
        $esign = app(Esign::class);

        return $method ? $esign->load($method) : $esign;
    }
}

if (!function_exists('getContentMd5')) {
    function getContentMd5($bodyData): string
    {
        return base64_encode(md5($bodyData, true));
    }
}

if (!function_exists('getSignature')) {
    function getSignature(
        string $httpMethod,
        string $accept,
        string $contentType,
        string $contentMd5,
        string $date,
        $headers,
        string $url
    ): string {
        $stringToSign = $httpMethod . "\n" . $accept . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $headers;
        if ($headers !== '') {
            $stringToSign .= "\n" . $url;
        } else {
            $stringToSign .= $url;
        }
        $signature = hash_hmac('sha256', utf8_encode($stringToSign), utf8_encode(Factory::getProjectScert()), true);

        return base64_encode($signature);
    }
}

if (!function_exists('getMillisecond')) {
    function getMillisecond(): float
    {
        [$t1, $t2] = explode(' ', microtime());

        return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}

if (!function_exists('getContentBase64Md5')) {
    function getContentBase64Md5($filePath): string
    {
        $md5file = md5_file($filePath, true);

        return base64_encode($md5file);
    }
}
