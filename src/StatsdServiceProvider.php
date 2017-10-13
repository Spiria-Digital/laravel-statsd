<?php

/**
 * Talk to Statsd from Laravel.
 *
 * @author Spiria-Digital
 * @license MIT
 */

namespace SpiriaDigital\Statsd;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use SpiriaDigital\Statsd\Middleware\StatsdTerminate;

class StatsdServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    //
    protected $config;
    protected $enabled = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'statsd');

        $environments = $this->app['config']->get('statsd.staging.environments', array());
        $current_environment = $this->app['env'];
        if (is_array($environments) AND in_array($current_environment, $environments)) {
            $this->config = $this->app['config']->get('statsd.staging', array());
            $this->enabled = true;
        } else {
            $environments = $this->app['config']->get('statsd.production.environments', array());
            if (is_array($environments) AND in_array($current_environment, $environments)){
                $this->config = $this->app['config']->get('statsd.production', array());
                $this->enabled = true;
            }
        }

        $this->app->singleton('statsd', function ($app) {
            $statsd =  new Statsd($this->config['host'],$this->config['port'], $this->config['protocol']);

            // Disable logging if we aren't on the right environment
            if(!$this->enabled){
                $statsd->disable();
            }
            return $statsd;

        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('statsd.php')], 'config');
        $this->registerMiddleware(StatsdTerminate::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['statsd'];
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/config.php';
    }

    /**
     * Register the StastdTerminate Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }
}
