<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

use Illuminate\Support\Traits\Macroable;
use XNXK\LaravelEsign\Adapter\Guzzle as Adapter;
use XNXK\LaravelEsign\Auth\Token;
use XNXK\LaravelEsign\Endpoints\Account;
use XNXK\LaravelEsign\Endpoints\File;

class Esign
{
    use Macroable;

    protected Adapter $adapter;
    private Token $token;

    public function __construct(string $appId, string $secret)
    {
        $token = new Token($appId, $secret);
        $this->adapter = new Adapter($token);
    }

    public function account(): Account
    {
        return new Account($this->adapter);
    }

    public function file(): File
    {
        return new File($this->adapter);
    }
}
