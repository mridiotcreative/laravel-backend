<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('true_if_reference_is_false', function ($key, $value, $parameters, $validator) {
            $request = request();
            $keyReference = $parameters[0];
            if ($parameters[0] != 0){
                return false;
            } else {
                return true;
            }
        });
    }
}
