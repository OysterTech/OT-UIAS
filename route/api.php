<?php

/**
 * @name 生蚝科技统一身份认证平台-R-接口路由
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-15
 * @version 2021-08-20
 */

use think\facade\Route;

Route::group('api', function () {
	Route::group('v3', function () {
		Route::group('menu', function () {
			Route::get('getCurrentUserMenu', 'api.v3.Menu/getCurrentUserMenu');
			Route::get('roleMenuForZtree', 'api.v3.Menu/getRoleMenuForZtree');
			Route::get('list', 'api.v3.Menu/getList');
		});

		Route::group('user', function () {
			Route::get('getCurrentUserInfo', 'api.v3.User/getCurrentUserInfo');
			Route::get('list', 'api.v3.User/getList');
			Route::post('info', 'api.v3.User/getUserInfoByToken');
		});

		Route::group('role', function () {
			Route::get('list', 'api.v3.Role/getList');
		});

		Route::group('userGroup', function () {
			Route::get('list', 'api.v3.UserGroup/getList');
		});
	});

	Route::any('[:error]', 'Error/lackApiVersion');
});
