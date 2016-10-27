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

//测试一下laravel的单元测试的使用
Route::get('/test',function(){
    return view('test');
});

/**
 * 前台界面显示区域
 */
Route::group(['namespace'=>'Home'], function () {
    Route::get('/','IndexController@index');
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
    Route::resource('role/index', 'RoleController@index'); //角色界面
    Route::resource('role/getRoleList', 'RoleController@getRoleList'); //角色列表
    Route::resource('role/add', 'RoleController@add'); //添加角色
    Route::resource('role/edit', 'RoleController@edit'); //编辑角色
    Route::resource('role/delete', 'RoleController@delete'); //删除角色
    Route::resource('role/getAuthList', 'RoleController@getAuthList'); //权限列表
    Route::resource('role/saveRoleAuthority', 'RoleController@saveRoleAuthority'); //权限列表

    Route::resource('user/index', 'UserController@index'); //用户界面
    Route::resource('user/getUserList', 'UserController@getUserList'); //用户列表数据
    Route::resource('user/getRoleUserList', 'UserController@getRoleUserList'); //用户列表数据

    Route::resource('blog/index', 'BlogController@index'); //博客首页界面
    Route::resource('blog/option', 'BlogController@option'); //博客操作
    Route::resource('blog/getList', 'BlogController@getList'); //博客列表信息
    Route::resource('blog/getCategory', 'BlogController@getCategory'); //博客分类信息
});
