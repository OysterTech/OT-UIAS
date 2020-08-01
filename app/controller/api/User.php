<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-用户接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-07-12
 */

namespace app\controller\api;

use app\BaseController;
use think\facade\Session;
use app\model\User as UserModel;

class User extends BaseController
{
	public function getCurrentUserInfo()
	{
		if (Session::has('userInfo')) return packApiData(200, 'success', ['userInfo' => Session::get('userInfo')]);
		else return packApiData(403, 'User not login', [], '用户尚未登录');
	}


	public function getList()
	{
		return packApiData(200, 'success', ['list' => UserModel::select()->hidden(['password', 'salt'])]);
	}
}
