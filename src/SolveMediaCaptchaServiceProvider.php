<?php

namespace Rubensrocha\SolveMediaCaptcha;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Input;

class SolveMediaCaptchaServiceProvider extends ServiceProvider
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

        $app['validator']->extend('solvemediacaptcha', function ($attribute, $value) use ($app) {
            return $app['solvemediacaptcha']->checkAnswer($app['request']->getClientIp(), Input::get('adcopy_challenge'), $value);
        });

        if ($app->bound('form')) {
            $app['form']->macro('solvemediacaptcha', function () use ($app) {
                return $app['solvemediacaptcha']->display();
            });
        }
    }

    /**
     * Booting configure.
     */
    protected function bootConfig()
    {
        $path = __DIR__.'/config/solvemediacaptcha.php';

        $this->mergeConfigFrom($path, 'solvemediacaptcha');

        if (function_exists('config_path')) {
            $this->publishes([$path => config_path('solvemediacaptcha.php')]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('solvemediacaptcha', function ($app) {
            return new SolveMediaCaptcha(
                $app['config']['solvemediacaptcha.ckey'],
                $app['config']['solvemediacaptcha.vkey'],
                $app['config']['solvemediacaptcha.hkey'],
                $app['config']['solvemediacaptcha.ssl']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['solvemediacaptcha'];
    }
}
