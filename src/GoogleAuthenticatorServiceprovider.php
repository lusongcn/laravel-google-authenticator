<?php 
namespace Earnp\GoogleAuthenticator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class GoogleAuthenticatorServiceprovider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    public function boot()
    {
        // this for conig
        $this->publishes([
            __DIR__.'/config/google.php' => config_path('google.php'),
        ]);

        $this->publishes([
            __DIR__.'/images/google' => public_path('images/google'),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/views', 'google');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/login/google'),
        ]);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */


    public function register()
    {
        $this->app->bind('GoogleAuthenticator',function($app){
            return new GoogleAuthenticator();
        });
    }
}