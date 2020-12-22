<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-异常处理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-15
 * @version 2020-12-20
 */

namespace app\controller;

class Error
{
	public function noPermission()
	{
		return view('/error', [
			'status' => 'error',
			'content' => '当前用户无权限访问此功能',
			'canBack' => '1',
			'hideTitle' => '1'
		]);
	}


	public function appInfo()
	{
		return view('/error', [
			'status' => 'error',
			'content' => '系统信息不存在<br>请联系该应用的技术支持',
			'canBack' => '1',
			'hideTitle' => '0'
		]);
	}
}
