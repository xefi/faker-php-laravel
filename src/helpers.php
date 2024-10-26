<?php

if (! function_exists('faker')) {
    /**
     * Get a faker instance.
     *
     * @param  string|null  $locale
     */
    function faker(string $locale = '') : \Xefi\Faker\Faker
    {
        if ($locale === '') {
            $locale = app()->bound('config') ?
                app('config')->get('app.faker_locale')
                : 'en_US';
        }

        $abstract = \Xefi\Faker\Faker::class.':'.$locale;

        if (! app()->bound($abstract)) {
            app()->singleton($abstract, fn () => new \Xefi\Faker\Faker($locale));
        }

        return app()->make($abstract);
    }
}
