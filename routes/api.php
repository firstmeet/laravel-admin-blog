<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('login', 'api\PassportController@login');
Route::post('register', 'api\PassportController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('get-details', 'api\PassportController@getDetails');
    Route::resource('group','api\GroupBuyingController');
    Route::any('alipay','api\AliPayController@index');
    Route::any('wechat_pay','api\WechatPayController@index');


});

Route::post('alipay/notify','api\AliPayController@notify');
Route::post('wechat_pay/notify','api\WechatPayController@notify');