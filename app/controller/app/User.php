<?php

/**
 * @name 生蚝科技统一身份认证平台-C-用户应用
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-08-05
 * @version 2021-08-04
 */

namespace app\controller\app;

use think\facade\Db;
use think\facade\Session;
use app\BaseController;
use app\model\App as AppModel;
use app\model\User as UserModel;
use app\model\UserGroupAppPermission as UserGroupAppPermissionModel;

class User extends BaseController
{
	public function index()
	{
		return view('/app/user/index', [
			'pageName' => '我的应用列表',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];
		$myGroupIds = UserModel::field('id')
			->where('id', Session::get('userInfo.id'))
			->find()
			->userGroup
			->where('status', 1)
			->toArray();
		$groupIds = array_map('array_shift', $myGroupIds);

		$permissionQuery = UserGroupAppPermissionModel::alias('ugap')
			->field('DISTINCT ugap.app_id,a.name,a.main_page,a.redirect_url')
			->join('app a', 'a.app_id=ugap.app_id')
			->where('a.is_show', 1)
			->where('ugap.status', 1)
			->whereIn('ugap.group_id', $groupIds);
		$openAppQuery = AppModel::field('app_id,name,main_page,redirect_url')
			->where('status', 1)
			->where('is_show', 1);

		// @todo 还是不能模糊查询
		if (isset($filterData['name']) && $filterData['name']) {
			$permissionQuery = $permissionQuery->whereLike('a.name', $filterData['name'] . '%');
			$openAppQuery = $openAppQuery->whereLike('name', $filterData['name'] . '%');
		}

		$permissionQuery = $permissionQuery->select()->toArray();
		$openAppQuery = $openAppQuery->select()->toArray();
		$appList = array_merge($permissionQuery, $openAppQuery);

		return packApiData(200, 'success', ['total' => count($appList), 'list' => $appList], '', false);
	}
}
