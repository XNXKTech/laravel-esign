<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Traits;

trait BodyAccessorTrait
{
    private $body;

    public function getBody()
    {
        return $this->body;
    }
}
