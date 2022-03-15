<?php

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;
use XNXK\LaravelEsign\Configurations\Configurations;
use XNXK\LaravelEsign\Traits\BodyAccessorTrait;

class Account implements API
{
    use BodyAccessorTrait;

    private Adapter $adapter;
}