<?php

/**
 * @name 生蚝科技统一身份认证平台-C-用户组管理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2021-08-20
 * @version V3.0 2021-08-20
 */

namespace app\controller\system;

use app\BaseController;
use app\model\UserGroup as UserGroupModel;

class UserGroup extends BaseController
{
	public function index()
	{
		return view('/system/userGroup/index', [
			'pageName' => '用户组管理',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];

		$countQuery = new UserGroupModel;
		$query = UserGroupModel::field('*');

		
	}
}
