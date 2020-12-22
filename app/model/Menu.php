<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-M-菜单
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-10-22
 */

namespace app\model;

use think\Model;

class Menu extends Model
{
	static public function getListByRole($roleId = '', $fatherId = '', $type = '')
	{
		$list = self::alias('m')
			->field('m.*')
			->where('m.father_id', $fatherId)
			->where('rp.role_id', $roleId);

		if ($type !== '') $list = $list->where('m.type', $type);

		$list = $list->join('role_permission rp', 'm.id=rp.menu_id')
			->order('m.sort')
			->select();

		return $list->toArray();
	}
}
