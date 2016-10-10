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
Route::group(['namespace'=>'Admin'], function () {
    Route::get('index', 'IndexController@index'); //后台首页
    Route::get('login', 'IndexController@login'); //后台登录界面
    Route::get('img_verify', 'IndexController@img_verify'); //后台登录验证
    Route::get('loginCheck', 'IndexController@loginCheck'); //后台登录界面
    Route::get('getMenuList', 'IndexController@getMenuList'); //获取菜单数据
    Route::get('logout', 'IndexController@logout'); //退出登录操作

    //区域管理
    Route::resource('area/index', 'AreaController@index'); //区域管理主页
    Route::resource('area/getProvince', 'AreaController@getProvince'); //获取省份
    Route::resource('area/getCity', 'AreaController@getCity'); //获取城市
    Route::resource('area/getRegion', 'AreaController@getRegion'); //获取区域
    Route::resource('area/addRegion', 'AreaController@addRegion'); //添加区域

    //权限控制

});
