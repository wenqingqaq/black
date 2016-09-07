<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * 后台模块显示
 */
Route::group(['prefix' => 'admin','namespace'=>'Admin'], function () {
    Route::get('index', 'IndexController@index'); //后台首页
    Route::get('getMenuList', 'IndexController@getMenuList'); //获取菜单数据
});
