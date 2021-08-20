<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-用户管理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-11
 * @version 2021-08-20
 */

namespace app\controller\system;

use app\BaseController;
use app\model\User as UserModel;

class User extends BaseController
{
	public function index()
	{
		return view('/system/user/index', [
			'pageName' => '用户管理',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];

		$countQuery = new UserModel;
		$query = UserModel::field('*');

		foreach ($filterData as $key => $value) {
			if ($key === 'role_id') {
				$countQuery = $countQuery->where('role_id', 'in', $value);
				$query = $query->where('role_id', 'in', $value);
			} elseif (in_array($key, ['user_name', 'nick_name'])) {
				$countQuery = $countQuery->whereLike($key, $value . '%');
				$query = $query->whereLike($key, $value . '%');
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

		return packApiData(200, 'success', ['total' => $countQuery, 'list' => $query->hidden(['password', 'salt'])], '', false);
	}


	public function toDelete()
	{
		$deleteInfo = inputPost('deleteInfo', 0, 1);
		$query = UserModel::where('id', $deleteInfo['id'])->delete();

		if ($query === 1) return packApiData(200, 'success');
		else return packApiData(500, 'Database error', ['error' => $query], '删除用户失败');
	}


	public function toCU()
	{
		$cuInfo = inputPost('cuInfo', 0, 1);
		$cuType = $cuInfo['operate_type'];

		if ($cuType == 'update') {
			$cuInfo['update_time'] = date('Y-m-d H:i:s');

			UserModel::update($cuInfo, ['id' => $cuInfo['id']], ['user_name', 'nick_name', 'phone', 'email', 'role_id', 'update_time']);
			return packApiData(200, 'success');
		} elseif ($cuType == 'create') {
			$cuInfo['id'] = makeUUID();
			$password = mt_rand(100000, 999999);
			$cuInfo['salt'] = getRandomStr(10);
			$cuInfo['password'] = sha1($cuInfo['salt'] . md5($cuInfo['user_name'] . $password) . $password);

			UserModel::create($cuInfo, ['id', 'user_name', 'nick_name', 'password', 'salt', 'phone', 'email', 'role_id']);
			return packApiData(200, 'success', ['initialPassword' => $password]);
		} else {
			return packApiData(5002, 'Invalid cu type', [], '非法操作行为');
		}
	}


	public function toResetPassword()
	{
		$userId = inputPost('userId', 0, 1);
		$userName = inputPost('userName', 0, 1);
		$password = inputPost('password', 0, 1);

		$salt = getRandomStr(10);
		$hash = sha1($salt . md5($userName . $password) . $password);

		UserModel::update(['password' => $hash, 'salt' => $salt, 'update_time' => date('Y-m-d H:i:s')], ['id' => $userId]);
		return packApiData(200, 'success', ['initialPassword' => $password]);
	}


	public function toBanOrOpen()
	{
		$info = inputPost('info', 0, 1);
		$info['update_time'] = date('Y-m-d H:i:s');

		UserModel::update($info, ['id' => $info['id']], ['status', 'update_time']);
		return packApiData(200, 'success');
	}
}
