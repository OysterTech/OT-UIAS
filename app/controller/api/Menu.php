<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-菜单接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-10-22
 */

namespace app\controller\api;

use app\BaseController;
use think\facade\Session;
use app\controller\Rbac;
use app\model\Menu as MenuModel;
use app\model\RolePermission as RolePermissionModel;

class Menu extends BaseController
{
	public function getCurrentUserMenu()
	{
		if (Session::has('userInfo.roleId')) $roleId = Session::get('userInfo.roleId');
		else return packApiData(403, 'User not login', [], '用户未登录');

		$obj_Rbac = new Rbac();
		return packApiData(200, 'success', ['treeData' => $obj_Rbac->getAllMenuByRole($roleId, '1')], '', false);
	}


	/**
	 * 获取适配zTree的角色菜单
	 * @return string zTree格式的菜单JSON字符串
	 */
	public function getRoleMenuForZtree()
	{
		$roleId = inputGet('roleId', 1, 1);
		$rtn = [];
		$allPermission = [];

		$menuList = MenuModel::order('sort')->select()->toArray();
		$permissionList = RolePermissionModel::field('menu_id')
			->where('role_id', $roleId)
			->select()
			->toArray();

		// 二维转一维数组
		foreach ($permissionList as $permissionInfo) {
			array_push($allPermission, $permissionInfo['menu_id']);
		}

		foreach ($menuList as $key => $info) {
			$rtn[$key]['id'] = $info['id'];
			$rtn[$key]['pId'] = $info['father_id'];
			$rtn[$key]['menuIcon'] = $info['icon'];
			$rtn[$key]['menuName'] = $info['name'];
			$rtn[$key]['uri'] = $info['uri'];
			$rtn[$key]['type'] = $info['type'];
			$rtn[$key]['name'] = $info['name'];
			$rtn[$key]['sort'] = $info['sort'];
			$rtn[$key]['checked'] = in_array($info['id'], $allPermission) ? true : false;
			$rtn[$key]['createTime'] = $info['create_time'];
			$rtn[$key]['updateTime'] = $info['update_time'];
		}

		return packApiData(200, 'success', ['node' => $rtn], '', false);
	}


	/**
	 * 获取可适配zTree的所有菜单列表
	 * @param string $isZtree (HTTP-GET)是否为ztree所需
	 */
	public function getList()
	{
		$isZtree = inputGet('isZtree', 1, 1);
		$list = MenuModel::order('sort')->select();

		if ($isZtree == 1) {
			$key = [];

			foreach ($list as $key => $info) {
				$rtn[$key]['id'] = $info['id'];
				$rtn[$key]['pId'] = $info['father_id'];
				$rtn[$key]['menuIcon'] = $info['icon'];
				$rtn[$key]['menuName'] = $info['name'];
				$rtn[$key]['uri'] = $info['uri'];
				$rtn[$key]['type'] = $info['type'];
				$rtn[$key]['name'] = $info['name'];
				$rtn[$key]['sort'] = $info['sort'];
				$rtn[$key]['createTime'] = $info['create_time'];
				$rtn[$key]['updateTime'] = $info['update_time'];
			}

			return packApiData(200, 'success', ['node' => $rtn], '', false);
		} else {
			return packApiData(200, 'success', ['list' => $list], '', false);
		}
	}
}
