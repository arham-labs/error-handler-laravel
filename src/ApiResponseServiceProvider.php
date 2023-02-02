<?php

namespace Arhamlabs\ApiResponse;

use Illuminate\Support\ServiceProvider;
use Arhamlabs\ApiResponse\ApiResponse;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->register(
        //     ApiResponse::class
        // );   
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/apiResponse.php' => config_path('apiResponse.php'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/apiResponse'),
            //, __DIR__.'/../Jobs/ApiResponseNotificationJob.php' => app_path("Jobs/ApiResponseNotificationJob.php"),
        ]);
    }
}
