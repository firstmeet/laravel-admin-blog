<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('goods',GoodController::class);
    $router->resource('sku_spec_group',SkuSpecGroupController::class);
    $router->resource('sku_spec',SkuSpecController::class);
    $router->group(['prefix'=>'api','namespace'=>'api'],function(Router $router){
        $router->get('/spec_group','SkuSpecGroupController@index');
    });
    $router->resource('sku',SkuController::class);
});
