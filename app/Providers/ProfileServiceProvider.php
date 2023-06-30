<?php

namespace App\Providers;

use App\Services\Contracts\ProfileContract;
use App\Services\ProfileService;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProfileContract::class, function () {
            return new ProfileService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
