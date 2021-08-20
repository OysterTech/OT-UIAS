<?php

/**
 * @name 生蚝科技统一身份认证平台-M-用户组
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2021-08-04
 * @version V3.0 2021-08-04
 */

namespace app\model;

use think\Model;

class UserGroup extends Model
{
	protected $pk = 'group_id';
	protected $convertNameToCamel = true;
}
