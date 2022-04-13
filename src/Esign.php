<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

use Illuminate\Support\Traits\Macroable;
use XNXK\LaravelEsign\Adapter\Guzzle as Adapter;
use XNXK\LaravelEsign\Auth\Token;
use XNXK\LaravelEsign\Endpoints\Account;
use XNXK\LaravelEsign\Endpoints\File;
use XNXK\LaravelEsign\Endpoints\SignFlow;

class Esign
{
    use Macroable;

    protected Adapter $adapter;
    private Token $token;

    public function __construct(?string $appId = null, ?string $secret = null)
    {
        $token = $appId && $secret ? new Token($appId, $secret) : new Token(getenv('ESIGN_APPID'), getenv('ESIGN_SECRET'));
        $this->adapter = new Adapter($token, getenv('ESIGN_SERVER'));
    }

    public function account(): Account
    {
        return new Account($this->adapter);
    }

    public function file(): File
    {
        return new File($this->adapter);
    }

    public function signFlow(): SignFlow
    {
        return new SignFlow($this->adapter);
    }
}
