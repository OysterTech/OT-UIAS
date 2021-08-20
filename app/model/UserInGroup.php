<?php

/**
 * @name 生蚝科技统一身份认证平台-M-用户与用户组
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2021-08-04
 * @version V3.0 2021-08-04
 */

namespace app\model;

use think\Model;

class UserInGroup extends Model
{
	protected $pk = 'id';
	protected $convertNameToCamel = true;
}
