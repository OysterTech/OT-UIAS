<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-R-全局路由
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-07-16
 */

use think\facade\Route;

// 用户中心
Route::group('user', function () {
	Route::get('index', 'User/index');
	Route::post('modifyProfile', 'User/modifyProfile');
	Route::post('modifyPassword', 'User/modifyPassword');
});

// 系统管理模块
Route::group('system', function () {
	Route::group('user', function () {
		Route::get('index', 'system.User/index');
		Route::post('list', 'system.User/getList');
		Route::post('toCU', 'system.User/toCU');
		Route::post('delete', 'system.User/toDelete');
		Route::post('resetPassword', 'system.User/toResetPassword');
		Route::post('banOrOpen', 'system.User/toBanOrOpen');
	});

	Route::group('menu', function () {
		Route::get('index', 'system.Menu/index');
		Route::post('toCU', 'system.Menu/toCU');
		Route::post('delete', 'system.Menu/toDelete');
	});

	Route::group('role', function () {
		Route::get('index', 'system.Role/index');
		Route::post('list', 'system.Role/getList');
		Route::post('toCU', 'system.Role/toCU');
		Route::post('delete', 'system.Role/toDelete');
		Route::post('setDefault', 'system.Role/toSetDefault');
		Route::post('setPermission', 'system.Role/toSetPermission');
	});

	Route::group('setting', function () {
		Route::get('index', 'system.Setting/index');
		Route::post('list', 'system.Setting/getList');
		Route::post('toCU', 'system.Setting/toCU');
		Route::post('delete', 'system.Setting/toDelete');
	});
});

Route::get('/', 'Index/index');
Route::get('login', 'Index/login');
Route::post('toLogin', 'Index/toLogin');
Route::get('logout', 'Index/logout');
Route::get('forgetPassword', 'Index/forgetPassword');
Route::post('sendCode2ForgetPassword', 'Index/sendCode2ForgetPassword');
Route::post('resetPassword', 'Index/toResetPassword');

Route::group('error', function () {
	Route::get('noPermission', 'error/noPermission');
});
