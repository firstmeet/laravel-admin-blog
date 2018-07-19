<?php

namespace App\Providers;

use App\Repositories\GoodsInterface;
use App\Repositories\GoodsRepository;
use App\Repositories\GroupBuyingInterface;
use App\Repositories\GroupBuyingRepository;
use App\Repositories\GroupBuyingSubInterface;
use App\Repositories\GroupBuyingSubRepository;
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
        $this->app->bind(GroupBuyingSubInterface::class,GroupBuyingSubRepository::class);
        $this->app->bind(GoodsInterface::class,GoodsRepository::class);
    }
}
