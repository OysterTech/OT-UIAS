<?php
/**
 * @name 生蚝科技统一身份认证平台-A-用户组接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2021-08-20
 * @version V3.0 2021-08-20
 */
namespace app\controller\api\v3;

use app\BaseController;
use app\model\UserGroup as UserGroupModel;

class UserGroup extends BaseController
{
	public function getList()
	{
		return packApiData(200, 'success', ['list' => UserGroupModel::select()], '', false);
	}
}
