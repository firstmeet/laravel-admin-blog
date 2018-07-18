<?php

namespace App\Providers;

use App\Repositories\GroupBuyingInterface;
use App\Repositories\GroupBuyingRepository;
use Illuminate\Support\ServiceProvider;

class GroupProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GroupBuyingInterface::class,GroupBuyingRepository::class);
    }
}
