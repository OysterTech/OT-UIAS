<?php

/**
 * @name 生蚝科技统一身份认证平台-A-用户接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version V3.0 2021-08-04
 */

namespace app\controller\api\v3;

use think\facade\Session;
use app\model\App as AppModel;
use app\model\User as UserModel;
use app\model\LoginToken as LoginTokenModel;
use app\model\UserGroupAppPermission as UserGroupAppPermissionModel;

class User
{
	public function getCurrentUserInfo()
	{
		if (Session::has('userInfo')) return packApiData(200, 'success', ['userInfo' => Session::get('userInfo')], '', false);
		else return packApiData(403, 'User not login', [], '用户尚未登录', false);
	}


	public function getList()
	{
		return packApiData(200, 'success', ['list' => UserModel::select()->hidden(['password', 'salt'])], '', false);
	}


	public function getUserInfoByToken()
	{
		$token = inputPost('token', 0, 1);
		$appId = inputPost('appId', 0, 1);
		$appSecret = inputPost('appSecret', 0, 1);

		if (strlen($token) !== 40) return packApiData(4001, 'Invalid token', [], '无效的接口令牌', false);

		// 先判断应用是否存在
		$appQuery = AppModel::field('name,status')
			->where('app_id', $appId)
			->where('app_secret', $appSecret)
			->whereRaw('(status=1 OR status=2)')
			->find();

		if (!isset($appQuery['name'])) return packApiData(4002, 'Invalid app id or app secret', [], '非法的应用ID或应用密钥', false);

		// 查token对应的用户ID
		$tokenQuery = LoginTokenModel::field('user_id')
			->where('token', $token)
			//->where('ip', getIP())
			->where('status', 1)
			->whereTime('expire_time', '>', date('Y-m-d H:i:s'))
			->find();

		if (!isset($tokenQuery['user_id'])) return packApiData(404, 'Token not found', [], 'Token不存在', false);
		else $userId = $tokenQuery['user_id'];

		// 查用户具体信息
		$userQuery = UserModel::alias('u')
			->field('u.*,r.name AS role_name')
			->join('role r', 'u.role_id=r.id', 'left')
			->where('u.id', $userId)
			->where('u.status', 1)
			->find();

		if ($userQuery !== null) $userInfo = $userQuery->hidden(['key_id', 'password', 'salt', 'update_time'])->toArray();
		else return packApiData(4041, 'User not found', [], '用户信息查询失败', false);

		// 查询用户所属的用户组
		$userInfo['user_group'] = $userQuery->userGroup
			->where('status', 1)
			->hidden(['status', 'create_time', 'update_time'])
			->toArray();
		$userInfo['user_group_ids'] = array_map('array_shift', $userInfo['user_group']);

		// 检查用户组有无权限访问此应用
		if ($appQuery['status'] == 2) {
			$permissionQuery = UserGroupAppPermissionModel::field('status')
				->where('app_id', $appId)
				->whereIn('group_id', $userInfo['user_group_ids'])
				->find();

			if (isset($permissionQuery['status'])) {
				if ($permissionQuery['status'] !== 1) return packApiData(403, 'User do not have permission', [], '当前用户无权限访问此应用');
			} else {
				return packApiData(403, 'User do not have permission', [], '当前用户无权限访问此应用');
			}
		}

		// 美化回调给应用的用户信息数组
		foreach ($userInfo as $field => $value) {
			unset($userInfo[$field]);
			$userInfo[camelize($field)] = $value;
		}

		if ($userInfo['extraParam'] !== null) $userInfo['extraParam'] = json_decode($userInfo['extraParam'], true);
		else unset($userInfo['extraParam']);

		// 一码一用&删除已超时码
		LoginTokenModel::whereOr('token', $token)
			->whereTime('expire_time', '<', date('Y-m-d H:i:s'), 'OR')
			->delete();

		return packApiData(200, 'success', ['userInfo' => $userInfo], '成功', false);
	}
}
