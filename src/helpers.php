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

        return app()->makeWith(\Xefi\Faker\Faker::class, compact('locale'));
    }
}