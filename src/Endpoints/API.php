<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;

interface API
{
    public function __construct(Adapter $adapter);
}
