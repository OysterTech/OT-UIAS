<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-用户
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-14
 * @version 2020-07-15
 */

namespace app\controller;

use app\BaseController;
use think\facade\Session;
use app\model\User as UserModel;

class User extends BaseController
{
	public function index()
	{
		return view('/user/index', [
			'pageName' => '个人中心',
			'pagePath' => []
		]);
	}


	public function modifyProfile()
	{
		$data = inputPost('data', 0, 1);
		$data['update_time'] = date('Y-m-d H:i:s');

		UserModel::update($data, ['id' => Session::get('userInfo.id')], ['nick_name', 'email', 'phone']);
		return packApiData(200, 'success');
	}


	public function modifyPassword()
	{
		$old = inputPost('old', 0, 1);
		$new = inputPost('new', 0, 1);
		$userName = Session::get('userInfo.userName');

		if (!checkPassword($userName, $old)) {
			return packApiData(403, 'Invalid password', [], '旧密码无效');
		}

		$salt = getRandomStr(10);
		$updateData = [
			'password' => sha1($salt . md5($userName . $new) . $new),
			'salt' => $salt,
			'update_time' => date('Y-m-d H:i:s')
		];

		UserModel::update($updateData, ['user_name' => $userName]);
		return packApiData(200, 'success');
	}
}
