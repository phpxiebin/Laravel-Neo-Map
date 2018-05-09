<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});
Route::get('area/get', 'AreaController@getArea');
//Wap端接口
Route::group(['namespace' => 'Wap'], function () {

    //活动相关
    Route::group(['prefix' => 'activity'], function () {
        //活动列表
        Route::get('data', 'ActivityController@data')->name('wap.activity.data');
        //活动详情
        Route::get('detail', 'ActivityController@detail')->name('wap.activity.detail');
    });

    //机构相关
    Route::group(['prefix' => 'organization'], function () {
        //机构信息-列表展示
        Route::get('data', 'OrganizationController@data')->name('wap.organization.data');
        //机构信息-地图展示
        Route::get('map', 'OrganizationController@map')->name('wap.organization.map');
        //所有机构信息-地图展示
        Route::get('allmap', 'OrganizationController@allmap')->name('wap.organization.map');
        //机构详情
        Route::get('detail', 'OrganizationController@detail')->name('wap.organization.detail');
        //机构发起的活动
        Route::get('activity', 'OrganizationController@activity')->name('wap.organization.activity');
    });

    //其他相关
    Route::group(['prefix' => 'other'], function () {
        //机构名称自动补全
        Route::get('organization-autocomple', 'OtherController@organizationAutocomple')->name('wap.other.organization-autocomple');
        //获取后台筛选值列表
        Route::get('filter', 'OtherController@filter')->name('wap.other.filter');
        //同步高德坐标
        Route::get('amap-sync', 'OtherController@amapSync')->name('wap.other.amap-sync');
    });

    //校友激活
    Route::match(['get', 'post'], 'alumnus/activate', 'AlumnusController@activate')->name('wap.alumnus.activate');
});

//校友平台
Route::group(['namespace' => 'Alumni', 'prefix' => 'alumni'], function () {
    //用户注册
    Route::get('/register', 'AuthController@register')->name('alumni.auth.register');
    //登录页面
    Route::get('/login', 'AuthController@authenticate')->name('alumni.auth.authenticate');
    //授权登录(触发内置用户认证)
    Route::post('/login', 'AuthController@login')->name('alumni.auth.login');
    //用户退出
    Route::get('/logout', 'AuthController@logout')->name('alumni.auth.logout');

    //todo 需授权分组
    Route::group(['middleware' => ['auth:alumni']], function () {

        //控制台主页
        Route::get('/', 'HomeController@dashboard')->name('alumni.home.dashboard');

        //活动相关
        Route::group(['prefix' => 'activity'], function () {
            //活动列表
            Route::get('/', 'ActivityController@index')->name('alumni.activity');
            //活动列表数据
            Route::get('/data', 'ActivityController@data');
            //发布活动
            Route::match(['get', 'post'], '/store', 'ActivityController@store')->name('alumni.activity.store');
            //修改活动
            Route::match(['get', 'post'], '/update', 'ActivityController@update')->name('alumni.activity.update');
            //活动详情
            Route::get('/detail', 'ActivityController@detail')->name('alumni.activity.detail');
            //删除活动
            Route::match(['get', 'post'], '/destroy', 'ActivityController@destroy')->name('alumni.activity.destroy');
        });

        //信息管理
        Route::group(['prefix' => 'info'], function () {
            //机构信息
            Route::match(['get', 'post'], '/organization', 'InfoController@organization')->name('alumni.info.organization');
            //校友信息
            Route::match(['get', 'post'], '/alumnus', 'InfoController@alumnus')->name('alumni.info.alumnus');
        });

    });
});

//系统管理平台
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    //用户注册
    Route::get('/register', 'AuthController@register')->name('admin.auth.register');
    //登录页面
    Route::get('/login', 'AuthController@authenticate')->name('admin.auth.authenticate');
    //授权登录(触发内置用户认证)
    Route::post('/login', 'AuthController@login')->name('admin.auth.login');
    //用户退出
    Route::get('/logout', 'AuthController@logout')->name('admin.auth.logout');

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::group(['prefix' => 'entrust'], function () {
            // Route::resource('users', 'UsersController')->name('admin.entrust.users');
            // Route::resource('roles', 'Entrust\RolesController')->name('admin.entrust.roles');

            Route::get('permissions', 'Entrust\PermissionsController@index')->name('admin.entrust.permissions');
            Route::get('permissions/create', 'Entrust\PermissionsController@create')->name('admin.entrust.permissions.create');
            Route::post('permissions/store', 'Entrust\PermissionsController@store')->name('admin.entrust.permissions.store');
            Route::get('permissions/edit', 'Entrust\PermissionsController@edit')->name('admin.entrust.permissions.edit');
            Route::post('permissions/update', 'Entrust\PermissionsController@update')->name('admin.entrust.permissions.update');
            Route::post('permissions/destroy', 'Entrust\PermissionsController@destroy')->name('admin.entrust.permissions.destroy');

            Route::get('users', 'Entrust\UsersController@index')->name('admin.entrust.users');
            Route::get('users/create', 'Entrust\UsersController@create')->name('admin.entrust.users.create');
            Route::post('users/store', 'Entrust\UsersController@store')->name('admin.entrust.users.store');
            Route::get('users/edit', 'Entrust\UsersController@edit')->name('admin.entrust.users.edit');
            Route::post('users/update', 'Entrust\UsersController@update')->name('admin.entrust.users.update');
            Route::post('users/destroy', 'Entrust\UsersController@destroy')->name('admin.entrust.users.destroy');

            Route::get('roles', 'Entrust\RolesController@index')->name('admin.entrust.roles');
            Route::get('roles/create', 'Entrust\RolesController@create')->name('admin.entrust.roles.create');
            Route::post('roles/store', 'Entrust\RolesController@store')->name('admin.entrust.roles.store');
            Route::get('roles/edit', 'Entrust\RolesController@edit')->name('admin.entrust.roles.edit');
            Route::post('roles/update', 'Entrust\RolesController@update')->name('admin.entrust.roles.update');
            Route::post('roles/destroy', 'Entrust\RolesController@destroy')->name('admin.entrust.roles.destroy');

            // Route::get('/role_permission', 'Entrust\RolesPermissionsController@index')->name('admin.entrust.role_permission');
            // Route::post('/role_permission', 'Entrust\RolesPermissionsController@store')->name('admin.entrust.role_permission1');
        });
    });

    //todo 需授权分组
    Route::group(['middleware' => ['auth:admin']], function () {

        //控制台主页
        Route::get('/', 'HomeController@dashboard')->name('admin.home.dashboard');

        //校友相关
        Route::group(['prefix' => 'alumnus'], function () {
            //校友列表
            Route::get('/', 'AlumnusController@index')->name('admin.alumnus');
            //校友列表数据
            Route::get('/data', 'AlumnusController@data')->name('admin.alumnus.data');
            //添加校友
            Route::match(['get', 'post'], '/store', 'AlumnusController@store')->name('admin.alumnus.store');
            //导入校友数据
            Route::post('excel/import', 'AlumnusController@import')->name('admin.alumnus.import');
            //修改校友数据
            Route::match(['get', 'post'], '/update', 'AlumnusController@update')->name('admin.alumnus.update');
            //删除校友
            Route::match(['get', 'post'], '/destroy', 'AlumnusController@destroy')->name('admin.alumnus.destroy');
        });

        //机构相关
        Route::group(['prefix' => 'organization'], function () {
            //机构列表
            Route::get('/', 'OrganizationController@index')->name('admin.organization');
            //机构列表数据
            Route::get('/data', 'OrganizationController@data')->name('admin.organization.data');
            //添加机构
            Route::match(['get', 'post'], '/store', 'OrganizationController@store')->name('admin.organization.store');
            //修改活动
            Route::match(['get', 'post'], '/update', 'OrganizationController@update')->name('admin.organization.update');
            //删除活动
            Route::match(['get', 'post'], '/destroy', 'OrganizationController@destroy')->name('alumni.organization.destroy');
        });

        //活动相关
        Route::group(['prefix' => 'activity'], function () {
            //活动列表
            Route::get('/', 'ActivityController@index')->name('admin.activity');
            //活动列表数据
            Route::get('/data', 'ActivityController@data')->name('admin.activity.data');
            //修改活动
            Route::match(['get', 'post'], '/update', 'ActivityController@update')->name('admin.activity.update');
            //删除活动
            Route::match(['get', 'post'], '/destroy', 'ActivityController@destroy')->name('admin.activity.destroy');
        });

    });
});
