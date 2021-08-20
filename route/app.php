<?php

/**
 * @name 生蚝科技统一身份认证平台-R-全局路由
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version V3.0 2021-08-17
 */

use think\facade\Route;

// 用户中心
Route::group('user', function () {
	Route::get('index', 'User/index');
	Route::post('modifyProfile', 'User/modifyProfile');
	Route::post('modifyPassword', 'User/modifyPassword');
	Route::post('checkDuplicate', 'User/checkDuplicate');
});

Route::group('app', function () {
	Route::group('user', function () {
		Route::get('index', 'app.User/index');
		Route::post('list', 'app.User/getList');
	});

	Route::group('admin', function () {
		Route::get('index', 'app.Admin/index');
		Route::post('list', 'app.Admin/getList');
		Route::post('switchShowStatus', 'app.Admin/switchShowStatus');
		Route::post('toCU', 'app.Admin/toCU');
		Route::post('toDelete', 'app.Admin/toDelete');
	});
});

Route::group('thirdLogin', function () {
	Route::get('github', 'thirdLogin/github');
	Route::get('gzlib', 'thirdLogin/gzlib');
	Route::get('workWeixin', 'thirdLogin/workWeixin');
	Route::get('gdyht', 'thirdLogin/gdyht');
	Route::get('yuque', 'thirdLogin/yuque');
	Route::get('bind', 'thirdLogin/bind');
	Route::post('toBind', 'thirdLogin/toBind');
	Route::get('abandonBind', 'thirdLogin/abandonBind');
});

Route::get('/', 'Index/index');
Route::get('login', 'Index/login');
Route::post('toLogin', 'Index/toLogin');
Route::get('logout', 'Index/logout');
Route::get('forgetPassword', 'Index/forgetPassword');
Route::post('sendCode2ForgetPassword', 'Index/sendCode2ForgetPassword');
Route::post('resetPassword', 'Index/toResetPassword');
Route::get('register', 'Index/register');
Route::post('toRegister', 'Index/toRegister');
Route::post('sendCode2ActivePassport', 'Index/sendCode2ActivePassport');
Route::get('activePassport', 'Index/activePassport');
