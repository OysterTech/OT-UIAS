<?php

/**
 * @name 生蚝科技统一身份认证平台-R-全局路由
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version V3.0 2020-12-20
 */

use think\facade\Route;

// 用户中心
Route::group('user', function () {
	Route::get('index', 'User/index');
	Route::post('modifyProfile', 'User/modifyProfile');
	Route::post('modifyPassword', 'User/modifyPassword');
});

Route::group('app', function () {
	Route::group('user', function () {
		Route::get('index', 'app.User/index');
	});
});

Route::group('thirdLogin', function () {
	Route::get('github', 'thirdLogin/github');
	Route::get('gzlib', 'thirdLogin/gzlib');
	Route::get('gdyht', 'thirdLogin/gdyht');
	Route::get('yuque', 'thirdLogin/yuque');
});

Route::get('/', 'Index/index');
Route::get('login', 'Index/login');
Route::post('toLogin', 'Index/toLogin');
Route::get('logout', 'Index/logout');
Route::get('forgetPassword', 'Index/forgetPassword');
Route::post('sendCode2ForgetPassword', 'Index/sendCode2ForgetPassword');
Route::post('resetPassword', 'Index/toResetPassword');

Route::group('error', function () {
	Route::get('appInfo', 'error/appInfo');
	Route::get('noPermission', 'error/noPermission');
});
