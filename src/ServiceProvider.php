<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected bool $defer = true;

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/esign.php',
            'esign'
        );

        $config = config('esign');

        $this->app->bind(Esign::class, static function () use ($config) {
            return new Esign($config);
        });

        $this->app->alias(Esign::class, 'esign');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/esign.php' => config_path('esign.php'),
        ], 'config');
    }
}
