<?php

if (! function_exists('faker')) {
    /**
     * Get a faker instance.
     *
     * @param  string|null  $locale
     */
    function faker(string $locale = null) : \Xefi\Faker\Faker
    {
        if (app()->bound('config')) {
            $locale ??= app('config')->get('app.faker_locale');
        }

        $locale ??= 'en_US';

        $abstract = \Xefi\Faker\Faker::class.':'.$locale;

        if (! app()->bound($abstract)) {
            app()->singleton($abstract, fn () => new \Xefi\Faker\Faker($locale));
        }

        return app()->make($abstract);
    }
}
