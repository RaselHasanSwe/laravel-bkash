<?php
namespace RaselSwe\Bkash;

use Illuminate\Support\ServiceProvider;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->mergeConfigFrom(__DIR__.'/config/bkash.php', 'bkash');
        $this->publishes([
            __DIR__.'/config/bkash.php' => config_path('bkash.php'),
        ]);


    }

    public function register()
    {

    }
}
