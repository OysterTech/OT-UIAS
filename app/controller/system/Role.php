<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-角色管理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-13
 * @version 2020-07-21
 */

namespace app\controller\system;

use app\BaseController;
use app\model\Role as RoleModel;
use app\model\RolePermission as RolePermissionModel;
use app\model\User as UserModel;

class Role extends BaseController
{
	public function index()
	{
		return view('/system/role/index', [
			'pageName' => '角色管理',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];

		$countQuery = new RoleModel;
		$query = RoleModel::field('*');

		foreach ($filterData as $key => $value) {
			if ($key === 'idLast5') {
				// 根据ID最后5位查询，方便用户快查
				$countQuery = $countQuery->whereRaw('RIGHT(id,5)=:id', ['id' => $value]);
				$query = $query->whereRaw('RIGHT(id,5)=:id', ['id' => $value]);
			} else {
				// 其他条件
				$countQuery = $countQuery->where($key, $value);
				$query = $query->where($key, $value);
			}
		}

		$countQuery = $countQuery->count('id');
		$query = $query->order('create_time', 'desc')
			->limit(($page - 1) * $perPage, $perPage)
			->select();

		return packApiData(200, 'success', ['total' => $countQuery, 'list' => $query], '', false);
	}


	public function toDelete()
	{
		$deleteInfo = inputPost('deleteInfo', 0, 1);
		$roleId = $deleteInfo['id'];

		// 检查是否有用户为此角色
		$checkUser = UserModel::field('user_name,nick_name')
			->where('role_id', $roleId)
			->select()
			->toArray();

		if (count($checkUser) > 0) {
			$html = '当前角色已被' . count($checkUser) . '个用户绑定<br>如需继续删除<br>请先修改下列用户的角色<hr>';

			foreach ($checkUser as $info) {
				$html .= '<font color="#098ce8">' . $info['nick_name'] . ' (' . $info['user_name'] . ')</font><br>';
			}

			return packApiData(4001, 'This role is bound by users', ['list' => $checkUser], $html);
		}

		$query = RoleModel::where('id', $roleId)->delete();

		if ($query === 1) {
			// 还要删除此角色的权限
			RolePermissionModel::where('role_id', $roleId)->delete();
			return packApiData(200, 'success');
		} else {
			return packApiData(500, 'Database error', ['error' => $query], '删除角色失败');
		}
	}


	public function toCU()
	{
		$cuInfo = inputPost('cuInfo', 0, 1);
		$cuType = $cuInfo['operate_type'];

		if ($cuType == 'update') {
			RoleModel::update($cuInfo, ['id' => $cuInfo['id']], ['name', 'remark']);
			return packApiData(200, 'success');
		} elseif ($cuType == 'create') {
			$cuInfo['id'] = makeUUID();
			RoleModel::create($cuInfo, ['id', 'name', 'remark']);
			return packApiData(200, 'success');
		} else {
			return packApiData(5002, 'Invalid cu type', [], '非法操作行为');
		}
	}


	public function toSetDefault()
	{
		$id = inputPost('id', 0, 1);

		// 先将原默认角色设为非默认
		RoleModel::update(['is_default' => 0], ['is_default' => 1]);
		// 再修改指定角色为默认角色
		RoleModel::update(['is_default' => 1], ['id' => $id]);

		return packApiData(200, 'success');
	}


	public function toSetPermission()
	{
		$info = inputPost('permissionInfo', 0, 1);

		$roleId = $info['id'];
		$node = $info['node'];

		// 清空原有权限
		$clearOrigin = RolePermissionModel::where('role_id', $roleId)->delete();

		if ($clearOrigin <= 0) return packApiData(4001, 'Failed to clear original permission', [], '清空此角色原有权限失败');

		$insertData = [];
		foreach ($node as $menuId) {
			array_push($insertData, [
				'role_id' => $roleId,
				'menu_id' => $menuId
			]);
		}

		$modelObj = new RolePermissionModel;
		$query = $modelObj->saveAll($insertData);

		if (count($node) === count($query)) return packApiData(200, 'success', ['total' => count($query)]);
		else return packApiData(4002, 'Success-total cannot match node-total', ['success' => count($query), 'node' => count($node)], '成功授权数与原请求节点数不相符<hr>成功数：' . count($query) . '<br>原请求节点数：' . count($node));
	}
}
