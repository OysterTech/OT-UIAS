<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-菜单管理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-14
 * @version 2020-07-21
 */

namespace app\controller\system;

use app\BaseController;
use app\model\Menu as MenuModel;
use app\model\RolePermission as RolePermissionModel;
use think\facade\Session;

class Menu extends BaseController
{
	public function index()
	{
		return view('/system/menu/index', [
			'pageName' => '菜单管理',
			'pagePath' => []
		]);
	}


	public function toCU()
	{
		$cuInfo = inputPost('cuInfo', 0, 1);
		$cuType = $cuInfo['operate_type'];

		if ($cuType == 'update') {
			MenuModel::update($cuInfo, ['id' => $cuInfo['id']], ['name', 'icon', 'uri', 'sort']);
			return packApiData(200, 'success');
		} elseif ($cuType == 'create') {
			$cuInfo['id'] = makeUUID();
			MenuModel::create($cuInfo, ['id', 'father_id', 'name', 'icon', 'uri', 'sort']);
			return packApiData(200, 'success');
		} else {
			return packApiData(5002, 'Invalid cu type', [], '非法操作行为');
		}
	}


	public function toDelete()
	{
		$deleteInfo = inputPost('deleteInfo', 0, 1);
		$menuId = $deleteInfo['id'];

		// 是否已经确认要删除有角色绑定的菜单
		if (Session::get('sureDeleteMenuId') !== $menuId) {
			// 检查是否有角色拥有此项权限
			$checkPermission = RolePermissionModel::alias('rp')
				->field('r.name AS roleName')
				->where('menu_id', $menuId)
				->join('role r', 'r.id=rp.role_id')
				->select()
				->toArray();

			if (count($checkPermission) > 0) {
				Session::set('sureDeleteMenuId', $menuId);
				$html = '当前菜单已被' . count($checkPermission) . '个角色绑定<br>如需继续删除<br>请再次点击删除按钮<hr>';

				foreach ($checkPermission as $info) {
					$html .= '<font color="#098ce8">' . $info['roleName'] . '</font><br>';
				}

				return packApiData(4001, 'This menu is bound by roles', ['list' => $checkPermission], $html);
			}
		}

		$query = MenuModel::where('id', $menuId)->delete();

		if ($query === 1) {
			// 还要删除此菜单的所有角色权限
			RolePermissionModel::where('menu_id', $menuId)->delete();
			return packApiData(200, 'success');
		} else {
			return packApiData(500, 'Database error', ['error' => $query], '删除菜单失败');
		}
	}
}
