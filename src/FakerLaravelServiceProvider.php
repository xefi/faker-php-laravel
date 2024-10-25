<?php
namespace Xefi\Faker\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;

class FakerLaravelServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerServices();
    }

    /**
     * Register Rest's services in the container.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton(Xefi\Faker\Faker::class, function (Application $app) {
            return new Xefi\Faker\Faker(
                Config::get('app.faker_locale')
            );
        });
    }
}