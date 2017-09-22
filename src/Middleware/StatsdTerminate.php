<?php

namespace SpiriaDigital\Statsd\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;

class StatsdTerminate
{
    /**
     * The App container
     *
     * @var Container
     */
    protected $container;

    /**
     * Create a new middleware instance.
     *
     * @param  Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle the request after the response is sent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure response
     */
    public function terminate($request, $response)
    {
        $this->container['statsd']->send();
    }
}