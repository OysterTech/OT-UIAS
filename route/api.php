<?php

/**
 * @name 生蚝科技统一身份认证平台-R-接口路由
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-15
 * @version 2020-08-02
 */

use think\facade\Route;

Route::group('api', function () {
	Route::group('menu', function () {
		Route::get('getCurrentUserMenu', 'api.Menu/getCurrentUserMenu');
		Route::get('roleMenuForZtree', 'api.Menu/getRoleMenuForZtree');
		Route::get('list', 'api.Menu/getList');
	});

	Route::group('user', function () {
		Route::get('getCurrentUserInfo', 'api.User/getCurrentUserInfo');
		Route::get('list', 'api.User/getList');
		Route::post('info','api.User/getUserInfoByToken');
	});

	Route::group('role', function () {
		Route::get('list', 'api.Role/getList');
	});
});