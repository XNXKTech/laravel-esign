<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\FilesystemCache;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use XNXK\LaravelEsign\Core\AbstractAPI;
use XNXK\LaravelEsign\Core\AccessToken;
use XNXK\LaravelEsign\Core\Http;
use XNXK\LaravelEsign\Support\Log;

/**
 * Class Esign.
 *
 * @property \XNXK\LaravelEsign\Core\AccessToken $access_token
 * @property \XNXK\LaravelEsign\Account\Account $account
 * @property \XNXK\LaravelEsign\File\File $file
 * @property \XNXK\LaravelEsign\SignFlow\SignFlow $signflow
 * @property \XNXK\LaravelEsign\Template\Template $template
 * @property \XNXK\LaravelEsign\Identity\Identity $identity
 */
class Esign extends Container
{
    protected $providers = [
        Foundation\ServiceProviders\AccountProvider::class,
        Foundation\ServiceProviders\FileProvider::class,
        Foundation\ServiceProviders\SignFlowProvider::class,
        Foundation\ServiceProviders\TemplateProvider::class,
        Foundation\ServiceProviders\IdentityProvider::class,
    ];

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this['config'] = static function () use ($config) {
            return new Foundation\Config($config);
        };

        $this->registerBase();
        $this->registerProviders();
        $this->initializeLogger();

        $baseUri = $this['config']->get('server', 'https://smlopenapi.esign.cn');

        Http::setDefaultOptions($this['config']->get('guzzle', ['timeout' => 5.0, 'base_uri' => $baseUri]));

        AbstractAPI::maxRetries($this['config']->get('max_retries', 2));

        $this->logConfiguration($config);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param $method
     * @param $args
     *
     * @throws \Exception
     */
    public function __call($method, $args): mixed
    {
        if (is_callable([$this['fundamental.api'], $method])) {
            return call_user_func_array([$this['fundamental.api'], $method], $args);
        }

        throw new \Exception("Call to undefined method {$method}()");
    }

    public function logConfiguration($config): void
    {
        $config = new Foundation\Config($config);

        $keys = ['appId', 'secret'];
        foreach ($keys as $key) {
            !$config->has($key) || $config[$key] = '***' . substr($config[$key], -5);
        }

        Log::debug('Current config:', $config->toArray());
    }

    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    public function setProviders(array $providers): void
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function getProviders()
    {
        return $this->providers;
    }

    private function registerProviders(): void
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    private function registerBase(): void
    {
        $this['request'] = static function () {
            return Request::createFromGlobals();
        };

        if (!empty($this['config']['cache']) && $this['config']['cache'] instanceof CacheInterface) {
            $this['cache'] = $this['config']['cache'];
        } else {
            $this['cache'] = static function () {
                return new FilesystemCache(sys_get_temp_dir());
            };
        }

        $this['access_token'] = function () {
            return new AccessToken(
                $this['config']['appId'],
                $this['config']['secret'],
                $this['cache']
            );
        };
    }

    private function initializeLogger(): void
    {
        if (Log::hasLogger()) {
            return;
        }

        $logger = new Logger('esign');

        if (!$this['config']['debug'] || defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif ($this['config']['log.handler'] instanceof HandlerInterface) {
            $logger->pushHandler($this['config']['log.handler']);
        } elseif ($logFile = $this['config']['log.file']) {
            try {
                $logger->pushHandler(
                    new StreamHandler(
                        $logFile,
                        $this['config']->get('log.level', Logger::WARNING),
                        true,
                        $this['config']->get('log.permission', null)
                    )
                );
            } catch (\Exception $e) {
            }
        }

        Log::setLogger($logger);
    }
}
