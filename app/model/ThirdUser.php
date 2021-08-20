<?php

/**
 * @name 生蚝科技统一身份认证平台-M-用户第三方登录
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-08-01
 * @version V3.0 2021-02-22
 */

namespace app\model;

use think\Model;

class ThirdUser extends Model
{
	static public function getUserInfo($platform = '', $thirdId = '')
	{
		$info = self::alias('tu')
			->field('tu.id AS thirdKey,tu.user_id AS id,u.user_name AS userName,u.nick_name AS nickName,u.status,u.role_id AS roleId,u.phone,u.email,u.create_time AS createTime')
			->where('platform', $platform)
			->where('third_id', $thirdId)
			->join('user u', 'u.id=tu.user_id')
			->find();

		if ($info != null) return $info->toArray();
		else return [];
	}
}
