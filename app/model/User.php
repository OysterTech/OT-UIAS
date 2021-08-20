<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-M-用户
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2021-08-04
 */

namespace app\model;

use think\Model;

class User extends Model
{
	protected $pk = 'id';
	protected $convertNameToCamel = true;

	public function userGroup()
	{
		return $this->hasManyThrough(UserGroup::class, UserInGroup::class, 'user_id', 'group_id', 'id', 'group_id');
	}
}
