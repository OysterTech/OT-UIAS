<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-A-角色接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2021-08-04
 */

namespace app\controller\api\v3;

use app\BaseController;
use app\model\Role as RoleModel;

class Role extends BaseController
{
	public function getList()
	{
		return packApiData(200, 'success', ['list' => RoleModel::select()], '', false);
	}
}
