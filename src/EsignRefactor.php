<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

use Illuminate\Support\Traits\Macroable;
use JetBrains\PhpStorm\Pure;
use XNXK\LaravelEsign\Account\Account;
use XNXK\LaravelEsign\Core\AbstractAPI;
use XNXK\LaravelEsign\Core\AccessToken;
use XNXK\LaravelEsign\Core\Http;
use XNXK\LaravelEsign\Support\Log;

final class EsignRefactor
{
    use Macroable;

    /**
     * @var \XNXK\LaravelEsign\Core\AccessToken
     */
    protected AccessToken $token;

    #[Pure] public function __construct(string $appId, string $ESIGN_SECRET)
    {
        $this->token = new AccessToken($appId, $ESIGN_SECRET);
    }

    public function account(): Account{
        return new Account($this->token);
    }
}