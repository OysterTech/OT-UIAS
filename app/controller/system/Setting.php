<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-系统配置管理
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-15
 * @version 2020-08-17
 */

namespace app\controller\system;

use app\BaseController;
use app\model\Setting as SettingModel;
use think\facade\Session;

class Setting extends BaseController
{
	public function index()
	{
		return view('/system/setting/index', [
			'pageName' => '系统配置管理',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];

		$countQuery = new SettingModel;
		$query = SettingModel::field('*');

		foreach ($filterData as $key => $value) {
			$countQuery = $countQuery->whereLike($key, '%' . $value . '%');
			$query = $query->whereLike($key, '%' . $value . '%');
		}

		$countQuery = $countQuery->count('id');
		$query = $query->order('name')
			->limit(($page - 1) * $perPage, $perPage)
			->select();

		return packApiData(200, 'success', ['total' => $countQuery, 'list' => $query], '', false);
	}


	public function toCU()
	{
		$cuInfo = inputPost('cuInfo', 0, 1);
		$cuType = $cuInfo['operate_type'];

		if ($cuType == 'update') {
			$cuInfo['update_ip'] = getIP();

			SettingModel::update($cuInfo, ['id' => $cuInfo['id']], ['chinese_name', 'value', 'remark', 'update_ip']);
			return packApiData(200, 'success');
		} elseif ($cuType == 'create') {
			$cuInfo['create_ip'] = getIP();
			$cuInfo['update_ip'] = getIP();

			SettingModel::create($cuInfo, ['name', 'chinese_name', 'value', 'remark', 'create_ip', 'update_ip']);
			return packApiData(200, 'success');
		} else {
			return packApiData(5002, 'Invalid cu type', [], '非法操作行为');
		}
	}


	public function toDelete()
	{
		$deleteInfo = inputPost('deleteInfo', 0, 1);

		if (!checkPassword(Session::get('userInfo.userName'), $deleteInfo['password'])) return packApiData(403, 'Invalid password', [], '密码错误，请重新输入');

		$query = SettingModel::where('id', $deleteInfo['id'])->delete();

		if ($query === 1) return packApiData(200, 'success');
		else return packApiData(500, 'Database error', ['error' => $query], '删除配置项失败');
	}
}
