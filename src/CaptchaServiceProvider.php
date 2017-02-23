<?php

namespace AndreiBu\laravel_captcha;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Validation\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;


class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $app = $this->app;

        $this->bootConfig();
        
        
        $this->publishMigrations();


        
        $app['validator']->extend('captcha', function ($attribute, $value,$validator) use ($app) 
        {
            if(!isset($app['request']->captcha_md5)){return false;}
            
            return $app['captcha']->verifyResponse($value, $app['request']->captcha_md5);
            
        });

        if ($app->bound('form')) {
            $app['form']->macro('captcha', function ($attributes = []) use ($app) {
                return $app['captcha']->display($attributes, $app->getLocale());
            });
        }
    }

    /**
     * Booting configure.
     */
    protected function bootConfig()
    {
        $path = __DIR__.'/config/captcha.php';

        $this->mergeConfigFrom($path, 'captcha');

        if (function_exists('config_path')) {
            $this->publishes([$path => config_path('captcha.php')]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('captcha', function ($app) {
            return new Captcha($app['config']['captcha']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['captcha'];
    }
    
    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }
    
    private function getMigrationsPath()
    {
        return __DIR__ . '/../database/migrations/';
    }
       
    
}
