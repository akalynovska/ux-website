<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $setting = Setting::all();
        foreach ($setting as $conf) {
            $arr = json_decode($conf->value,1);
            if (json_last_error() == JSON_ERROR_NONE){
                \Config::set('setting.'.$conf->key, $arr);
            }
            else {
                \Config::set('setting.'.$conf->key, $conf->value);
            }
         } 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
