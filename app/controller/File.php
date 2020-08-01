<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-文件
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-22
 * @version 2020-07-28
 */

namespace app\controller;

use think\facade\Request;

class File
{
	public function upload()
	{
		if (!\think\facade\Session::has('userInfo.id')) return packApiData(4031, 'User have no permission', [], '用户无权限访问');

		$type = inputPost('type', 0, 1);
		$name = inputPost('name', 0, 1);
		$end = inputPost('end', 0, 1);

		if (!isset($_FILES['fileInfo'])) return packApiData(4001, 'No file', [], '未选择要上传的文件');
		else $file = $_FILES['fileInfo'];

		$fileExtension = substr($name, strrpos($name, '.') + 1);
		$check = self::checkFile($type, $file, $fileExtension);

		if ($check === 1) {
			return packApiData(4002, 'Invalid upload type', [], '非法的上传路径，请联系管理员');
		} elseif ($check === 2) {
			//return packApiData(4003, 'File is too big', [], '文件过大，请重新选择文件', false);
		} elseif ($check === 3) {
			//return packApiData(4004, 'Not support extension', [], '不支持上传此文件类型');
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
			$newName = sha1(date('YmdHis') . mt_rand(123456, 987654)) . '.' . $fileExtension;
			$newLocation = 'file/' . $type . $newName;

			copy(public_path() . $location, public_path() . $newLocation);
			unlink(public_path() . $location);
		}

		return packApiData(200, 'success', [
			'url' => ($end == 'true') ? Request::root(true) . '/public/' . $newLocation : '上传中 Uploading...',
		]);
	}


	private static function checkFile($type, $fileInfo, $extension)
	{
		$setting = json_decode(getSetting('fileUploadSetting'), true);

		if (!isset($setting[$type])) return 1; // 不支持的上传路径
		else $setting = $setting[$type];

		if ($fileInfo['size'] > $setting['size']) return 2; // 超出大小限制
		elseif (!in_array($extension, $setting['extension'])) return 3; // 不支持此扩展名
		else return 0;
	}
}
