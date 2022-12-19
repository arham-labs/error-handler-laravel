<?php

namespace Arhamlabs\ErrorHandler;

use Illuminate\Support\ServiceProvider;
use Arhamlabs\ApiResponse\ApiResponse;

class ErrorHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(
            ApiResponse::class
        );   
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
