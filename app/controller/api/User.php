<?php

/**
 * @name 生蚝科技统一身份认证平台-C-用户接口
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version V3.0 2020-08-03
 */

namespace app\controller\api;

use think\facade\Session;
use app\model\App as AppModel;
use app\model\User as UserModel;
use app\model\LoginToken as LoginTokenModel;

class User
{
	public function getCurrentUserInfo()
	{
		if (Session::has('userInfo')) return packApiData(200, 'success', ['userInfo' => Session::get('userInfo')]);
		else return packApiData(403, 'User not login', [], '用户尚未登录');
	}


	public function getList()
	{
		return packApiData(200, 'success', ['list' => UserModel::select()->hidden(['password', 'salt'])]);
	}


	public function getUserInfoByToken()
	{
		$token = inputPost('token', 0, 1);
		$appId = inputPost('appId', 0, 1);
		$appSecret = inputPost('appSecret', 0, 1);

		if (strlen($token) !== 40) return packApiData(4001, 'Invalid token', [], '无效的接口令牌', false);

		// 先判断应用是否存在
		$appQuery = AppModel::field('name')
			->where('app_id', $appId)
			->where('app_secret', $appSecret)
			->whereRaw('(status=1 OR status=2)')
			->find();

		if (!isset($appQuery['name'])) return packApiData(4002, 'Invalid app id or app secret', [], '非法的应用ID或应用密钥', false);

		// 查token对应的用户ID
		$tokenQuery = LoginTokenModel::field('user_id')
			->where('token', $token)
			->where('ip', getIP())
			->where('status', 1)
			->whereTime('expire_time', '>', date('Y-m-d H:i:s'))
			->find();

		if (!isset($tokenQuery['user_id'])) return packApiData(401, 'User not login', [], '用户尚未登录', false);
		else $userId = $tokenQuery['user_id'];

		// 一码一用&删除已超时码
		LoginTokenModel::whereOr('token', $token)
			->whereTime('expire_time', '<', date('Y-m-d H:i:s'), 'OR')
			->delete();

		// 查用户具体信息
		$userQuery = UserModel::alias('u')
			->field('u.*,r.name AS role_name')
			->join('role r', 'u.role_id=r.id', 'left')
			->where('u.id', $userId)
			->where('u.status', 1)
			->find();

		if ($userQuery !== null) $userInfo = $userQuery->hidden(['key_id', 'password', 'salt', 'update_time'])->toArray();
		else return packApiData(4041, 'User not found', [], '用户已登录，但查找用户信息失败', false);

		foreach ($userInfo as $field => $value) {
			unset($userInfo[$field]);
			$userInfo[camelize($field)] = $value;
		}

		if ($userInfo['extraParam'] !== null) $userInfo['extraParam'] = json_decode($userInfo['extraParam']);
		else unset($userInfo['extraParam']);

		return packApiData(200, 'success', ['userInfo' => $userInfo], '成功', false);
	}
}
