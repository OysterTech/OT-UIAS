<?php

/**
 * @name 生蚝科技统一身份认证平台-C-管理应用
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2021-08-05
 * @version 2021-08-05
 */

namespace app\controller\app;

use app\model\App as AppModel;
use app\BaseController;

class Admin extends BaseController
{
	public function index()
	{
		return view('/app/admin/index', [
			'pageName' => '所有应用管理',
			'pagePath' => []
		]);
	}


	public function getList()
	{
		$page = inputPost('page', 1, 1) ?: 1;
		$perPage = inputPost('perPage', 1, 1) ?: 25;
		$filterData = inputPost('filterData', 1, 1) ?: [];

		$countQuery = new AppModel;
		$query = AppModel::field('*');

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


	public function toCU()
	{
		$cuInfo = inputPost('cuInfo', 0, 1);
		$cuType = inputPost('type', 0, 1);

		if ($cuType == 'update') {
			$query = AppModel::where('id', $cuInfo['id'])
				->find()
				->allowField(['name', 'main_page', 'redirect_url', 'status', 'is_show'])
				->save($cuInfo);

			if ($query === true) return packApiData(200, 'success', [], '', false);
			else return packApiData(500, 'Database error', ['e' => $query], '数据库错误');
		} elseif ($cuType == 'create') {
			$cuInfo['app_id'] = 'otsso_' . getRandomStr(14, 3);
			$cuInfo['app_secret'] = sha1(time() . $cuInfo['app_id'] . getRandomStr(20));

			AppModel::create($cuInfo, ['app_id', 'app_secret', 'name', 'main_page', 'redirect_url', 'status', 'is_show']);

			return packApiData(200, 'success', [], '', false);
		} else {
			return packApiData(5002, 'Invalid cu type', [], '非法操作行为');
		}
	}


	public function switchShowStatus()
	{
		$isShow = (int)inputPost('isShow', 0, 1);
		$id = (int)inputPost('id', 0, 1);
		$query = AppModel::where('id', $id)->save(['is_show' => $isShow]);

		if ($query === 1) return packApiData(200, 'success', [], '', false);
		else return packApiData(500, 'Database error', ['e' => $query], '数据库修改失败');
	}


	public function toDelete()
	{
		$deleteInfo = inputPost('deleteInfo', 0, 1);
		$query = AppModel::where('id', $deleteInfo['id'])->where('app_id', $deleteInfo['appId'])->save(['status' => 0]);

		if ($query === 1) return packApiData(200, 'success');
		else return packApiData(500, 'Database error', ['error' => $query], '删除应用失败');
	}
}
