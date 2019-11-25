<?php

namespace Rawg;

use Illuminate\Support\ServiceProvider;
use Rawg\Rawg;

class RawgServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/rawg.php' => config_path('rawg.php'),
        ], 'rawg');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Rawg', function ($app) {
            return new Rawg();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Rawg::class];
    }
}
