<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-RBAC核心
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-10-22
 */

namespace app\controller;

use app\model\Menu as MenuModel;
use app\model\RolePermission as RolePermissionModel;

class Rbac
{
	/**
	 * 根据URI获取菜单Id
	 * @param string URI
	 * @return int 菜单Id
	 */
	public function getMenuId($uri = '')
	{
		$query = MenuModel::field('id')
			->where('uri', $uri)
			->find();

		return (isset($query['id'])) ? $query['id'] : null;
	}


	/**
	 * 根据角色获取所有权限的菜单Id
	 * @param string 角色Id
	 * @return array 菜单Id
	 */
	public function getAllPermissionByRole($roleId = '')
	{
		$list = RolePermissionModel::field('menu_id')
			->where('role_id', $roleId)
			->select()
			->toArray();
		$rtn = [];

		foreach ($list as $info) {
			array_push($rtn, $info['menu_id']);
		}

		return $rtn;
	}


	/**
	 * 根据角色和父菜单Id获取子菜单信息
	 * @param string 角色Id
	 * @param string 父菜单Id
	 * @param string 限定菜单类型
	 * @return array 子菜单信息
	 */
	public function getChildMenuByRole($roleId = '', $fatherId = '', $type = '')
	{
		return MenuModel::getListByRole($roleId, $fatherId, $type);
	}


	/**
	 * 根据角色获取所有菜单详细信息
	 * @param string 角色Id
	 * @param string 限定菜单类型
	 * @return array 菜单详细信息
	 */
	public function getAllMenuByRole($roleId = '', $type = '')
	{
		$allMenu = array();

		$allMenu = $this->getChildMenuByRole($roleId, '0', $type);
		$allMenu_total = count($allMenu);

		// 搜寻二级菜单
		for ($i = 0; $i < $allMenu_total; $i++) {
			$fatherId = $allMenu[$i]['id'];
			$child_list = $this->getChildMenuByRole($roleId, $fatherId, $type);

			if ($child_list == null) {
				// 没有二级菜单
				$allMenu[$i]['hasChild'] = '0';
				$allMenu[$i]['child'] = array();
			} else {
				// 有二级菜单
				$allMenu[$i]['hasChild'] = '1';
				$allMenu[$i]['child'] = $child_list;

				// 二级菜单的数量
				$child_list_total = count($child_list);

				// 搜寻三级菜单
				for ($j = 0; $j < $child_list_total; $j++) {
					$father2Id = $child_list[$j]['id'];
					$child2_list = $this->getChildMenuByRole($roleId, $father2Id, $type);

					if ($child2_list == null) {
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild'] = '0';
						$allMenu[$i]['child'][$j]['child'] = array();
					} else {
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild'] = '1';
						$allMenu[$i]['child'][$j]['child'] = $child2_list;
					}
				}
			}
		}

		return $allMenu;
	}


	/**
	 * 获取所有父菜单
	 * @return array 父菜单信息
	 */
	public function getFatherMenu()
	{
		$list = MenuModel::where('father_id', 0)
			->select();

		return $list->toarray();
	}


	/**
	 * 根据父菜单Id获取子菜单
	 * @param string 父菜单Id
	 * @return array 子菜单信息
	 */
	public function getChildMenu($fatherId = '')
	{
		$list = MenuModel::where('father_id', $fatherId)
			->select();

		return $list->toarray();
	}


	/**
	 * 获取所有菜单
	 * @return array 菜单详细信息
	 */
	public function getAllMenu()
	{
		$allMenu = array();

		$allMenu = $this->getFatherMenu();
		$allMenu_total = count($allMenu);

		// 搜寻二级菜单
		for ($i = 0; $i < $allMenu_total; $i++) {
			$fatherId = $allMenu[$i]['id'];
			$child_list = $this->getChildMenu($fatherId);

			if ($child_list == null) {
				// 没有二级菜单
				$allMenu[$i]['hasChild'] = '0';
				$allMenu[$i]['child'] = array();
			} else {
				// 有二级菜单
				$allMenu[$i]['hasChild'] = '1';
				$allMenu[$i]['child'] = $child_list;

				// 二级菜单的数量
				$child_list_total = count($child_list);

				// 搜寻三级菜单
				for ($j = 0; $j < $child_list_total; $j++) {
					$father2Id = $child_list[$j]['id'];
					$child2_list = $this->getChildMenu($father2Id);

					if ($child2_list == null) {
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild'] = '0';
						$allMenu[$i]['child'][$j]['child'] = array();
					} else {
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild'] = '1';
						$allMenu[$i]['child'][$j]['child'] = $child2_list;
					}
				}
			}
		}

		return $allMenu;
	}
}
