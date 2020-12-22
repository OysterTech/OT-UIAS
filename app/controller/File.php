<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-文件
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-22
 * @version 2020-11-14
 */

namespace app\controller;

use think\facade\Request;

class File
{
	public function upload()
	{
		if (!\think\facade\Session::has('userInfo.id')) {
			if (!isset($_SERVER['HTTP_REFERER'])) return packApiData(400, 'Invalid request', [], '非法请求');
			elseif (substr($_SERVER['HTTP_REFERER'], 0, 44) !== 'https://servicewechat.com/wx613c2639c4eb779a') return packApiData(4031, 'User have no permission', [], '用户无权限上传文件');
		}

		$type = inputPost('type', 0, 1);
		$name = inputPost('name', 0, 1);
		$end = inputPost('end', 0, 1);
		$extraParam = json_decode(inputPost('extraParam', 1, 1), true);

		if (!isset($_FILES['fileInfo'])) return packApiData(4001, 'No file', [], '未选择要上传的文件');
		else $file = $_FILES['fileInfo'];

		$fileExtension = substr($name, strrpos($name, '.') + 1);
		$check = self::checkFile($type);

		if ($check === 1) {
			return packApiData(4002, 'Invalid upload type', [], '非法的上传路径，请联系管理员');
		} else {
			if (substr($type, -13) == 'idCard/front_' || substr($type, -12) == 'idCard/back_' || substr($type, -9) == 'personal/') {
				if (isset($extraParam['userName']) && isset($extraParam['idCard'])) $newName = $extraParam['userName'] . $extraParam['idCard'] . '_' . substr(md5(time()), 5, 8) . '.' . $fileExtension;
				else return packApiData(0, 'Lack Extra Parameter', [], '缺失重要参数');
			} else {
				$newName = sha1(date('YmdHis') . mt_rand(123456, 987654)) . '.' . $fileExtension;
			}
		}

		$location = 'file/' . $type . $name;

		if (!file_exists(public_path() . $location)) {
			// 第一块上传
			move_uploaded_file($file['tmp_name'], public_path() . $location);
		} else {
			file_put_contents(public_path() . $location, file_get_contents($file['tmp_name']), FILE_APPEND);
		}

		// 到了最后一块，把名字改为随机数
		if ($end == 'true') {
			$newLocation = 'file/' . $type . $newName;

			copy(public_path() . $location, public_path() . $newLocation);
			unlink(public_path() . $location);
		}

		return packApiData(200, 'success', [
			'url' => ($end == 'true') ? Request::root(true) . '/public/' . $newLocation : '上传中 Uploading...'
		]);
	}


	private static function checkFile($type)
	{
		$setting = json_decode(getSetting('fileUploadSetting'), true);

		if (!in_array($type, $setting)) return 1; // 不支持的上传路径
		else return 0;
	}
}
