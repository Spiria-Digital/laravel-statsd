<?php

/**
 * Talk to Statsd from Laravel.
 *
 * @author Spiria-Digital
 * @license MIT
 */

namespace SpiriaDigital\Statsd;

use Illuminate\Contracts\Http\Kernel;
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

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'statsd');

        $this->app->singleton('statsd', function ($app) {
            $statsd =  new Statsd(
                $app['config']->get('statsd.host', 'localhost'),
                $app['config']->get('statsd.port', 8126),
                $app['config']->get('statsd.protocol', 'udp')
            );
            // Disable logging if we aren't on the right environment
            $environments        = $this->app['config']->get('statsd.environments', array());
            $current_environment = $this->app['env'];
            if (is_array($environments) AND !in_array($current_environment, $environments)) {
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
