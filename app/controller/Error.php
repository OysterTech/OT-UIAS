<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-异常处理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-15
 * @version 2020-12-20
 */

namespace app\controller;

use think\facade\Request;

class Error
{
	public function index()
	{
		return view('/error', [
			'status' => inputGet('a', 1),
			'content' => inputGet('t'),
			'canBack' => inputGet('b', 1),
			'hideTitle' => inputGet('ht', 1)
		]);
	}


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


	public function lackApiVersion()
	{
		if (Request::isAjax()) return packApiData(50001, 'Lack api version', [], '最新版UIAS接口已要求附带版本号，如有疑惑请联系UIAS管理员');
		else return view('/error', [
			'status' => 'error',
			'content' => '缺少接口版本号<br>请联系UIAS系统管理员',
			'canBack' => '1',
			'hideTitle' => '0'
		]);
	}
}
