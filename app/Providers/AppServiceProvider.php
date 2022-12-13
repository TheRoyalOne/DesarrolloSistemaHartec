<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider_before extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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


// Import Schema
use Illuminate\Support\Facades\Schema;
// ...

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Add the following line
        Schema::defaultStringLength(191);
    }

    // ...

}