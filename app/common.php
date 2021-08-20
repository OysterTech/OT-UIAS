<?php
// 应用公共文件

use think\facade\Request;

/**
 * camelize 下划线转小驼峰
 * @param string  $uncamelized_words 待转换的字符串
 * @return string                    小驼峰式的字符串
 */
function camelize($uncamelizedWord = '')
{
	$uncamelizedWord = '_' . str_replace('_', ' ', strtolower($uncamelizedWord));
	return ltrim(str_replace(' ', '', ucwords($uncamelizedWord)), '_');
}


/**
 * uncamelize 驼峰命名转下划线命名
 * @param string $camelCaps 待转换的驼峰式字符串
 * @return string           下划线式字符串
 */
function uncamelize($camelCaps = '')
{
	return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $camelCaps));
}


/**
 * getSetting 获取数据库中的系统配置
 * @param  string $configName 配置键名
 * @return string             配置值
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-07-12
 */
function getSetting($configName = '')
{
	$info = \app\model\Setting::field('value')
		->where('name', $configName)
		->find();

	return $info['value'];
}


/**
 * getWWAT 获取企业微信AccessToken
 * @return string 企业微信AccessToken（为空则获取失败）
 * @author Oyster Cheung <oyster@easypus.com>
 * @since 2021-03-31
 * @version 2021-03-31
 */
function getWWAT()
{
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	$redis->auth('Red@EasyPus.2020');
	$redis->select(2);
	$at = $redis->get('id_ww_at');

	if ($at) return $at;
	else {
		$query = curl('https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=' . env('workweixin.corp_id') . '&corpsecret=' . env('workweixin.secret'), 'get', 'json');

		if ($query['access_token']) {
			$redis->set('id_ww_at', $query['access_token'], $query['expires_in']);
			return $query['access_token'];
		} else {
			return '';
		}
	}
}


/**
 * checkUUID 校验UUID是否符合格式
 * @param  string $string 待检测字符串
 * @return string         合法字符串（若不合法则直接die）
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-29
 * @version 2020-07-29
 */
function checkUUID($string = '')
{
	if ($string == '0') return '0';
	elseif (strlen($string) !== 36) packApiData(4001, 'Invalid ID', [], '非法ID', false, true);

	for ($i = 0; $i < 36; $i++) {
		if (($i == 8 || $i == 13 || $i == 18 || $i == 23) && $string[$i] !== '-') packApiData(4001, 'Invalid ID', [], '非法ID', false, true);
		elseif (($i !== 8 && $i !== 13 && $i !== 18 && $i !== 23) && !preg_match("/^[A-Za-z0-9]+$/", $string[$i])) packApiData(40012, 'Invalid ID', [], '非法ID', false, true);
	}

	return $string;
}


/**
 * checkPassword 校验密码的有效性
 * @param  string  $userName
 * @param  string  $password
 * @return boolean 密码是否有效
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-07-16
 */
function checkPassword($userName = '', $password = '')
{
	$userInfo = \app\model\User::where('user_name', $userName)->find();

	if (!isset($userInfo['password'])) return false;

	if (sha1($userInfo['salt'] . md5($userName . $password) . $password) === $userInfo['password']) return true;
	else return false;
}


/**
 * getRandomStr  获取随机字符串
 * @param  int     $length 欲获取的随机字符串长度
 * @param  int     $type   0:无限制|1:只要大写|2:只要小写|3:字母数字混合
 * @return string          随机字符串
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2018-09-28
 * @version 2020-07-12
 */
function getRandomStr($length, $type = 0)
{
	if ($type == 1) {
		$str = 'ZXCVBNQWERTYASDFGHJKLUPM';
	} elseif ($type == 2) {
		$str = 'qwertyasdfghzxcvbnupmjk';
	} elseif ($type == 3) {
		$str = 'qwerty123asdfgh4567zxcvbnu89pmjk0';
	} else {
		$str = 'qw$ertyZXC123%VBNasdfg4.56hQWERT*YzxcvbnAS,DFGH789-upJKLn!0mUPj_k';
	}

	$ranstr = '';
	$strlen = strlen($str) - 1;
	for ($i = 1; $i <= $length; $i++) {
		$ran = mt_rand(0, $strlen);
		$ranstr .= $str[$ran];
	}

	return $ranstr;
}


/**
 * formatTime 将秒数换为时分秒
 * @param  int    $second  秒数
 * @return string          时分秒
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-01-22
 * @version 2020-02-16
 */
function formatTime($second = 0)
{
	$hour = floor($second / 3600);
	$minute = floor(($second - $hour * 3600) / 60);
	$second = floor(($second - $hour * 3600 - $minute * 60));
	return $hour . '时' . $minute . '分' . $second . '秒';
}


/**
 * getIP 获取IP地址
 * @return string IP地址
 */
function getIP()
{
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif (!empty($_SERVER["REMOTE_ADDR"])) {
		$cip = $_SERVER["REMOTE_ADDR"];
	} else {
		$cip = "0.0.0.0";
	}
	return $cip;
}


/**
 * packApiData ajax返回统一标准json字符串
 * @param  integer  $code       状态码
 * @param  string   $message    英文提示内容
 * @param  array    $data       返回数据
 * @param  string   $tips       中文提示语
 * @param  boolean  $needLog    是否需要日志记录
 * @param  boolean  $isDie      是否直接die输出
 * @return string|die
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2020-05-30
 */
function packApiData($code = 0, $message = '',  $data = [],  $tips = '',  $needLog = true,  $isDie = false)
{
	$reqId = makeUUID();

	$r = array(
		'code' => $code,
		'message' => $message,
		'data' => $data,
		'tips' => $tips,
		'requestTime' => time(),
		'requestId' => $reqId
	);

	if ($code !== 403001 && $needLog === true) {
		\app\model\ApiRequestLog::create([
			'request_id' => $reqId,
			'path' => Request::baseUrl(),
			'referer' => $_SERVER['HTTP_REFERER'] ?? '.',
			'ip' => getIP(),
			'code' => $code,
			'message' => $message,
			'req_data' => json_encode(Request::except(['token'])),
			'res_data' => json_encode($data),
		]);
	} else {
		unset($r['requestId']);
	}

	if ($isDie === true) die(json_encode($r));
	else return json($r);
}


/**
 * 生成随机36位UUID
 * @return string 随机UUID
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-11-17
 */
function makeUUID()
{
	$hash = sha1(time() . md5(mt_rand(123456789, time())));
	return substr($hash, 4, 8) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . substr($hash, 20, 4) . '-' . substr($hash, 24, 12);
}


/**
 * header自动跳转URL
 * @param string $path url
 */
function gotourl($path = '')
{
	$fullPath = url($path);
	header('location:' . $fullPath);
}


/**
 * 获取HTTP-Get数据
 * @param  string  $dataName  参数名称
 * @param  integer $allowNull 是否允许为空（0/1）
 * @param  integer $isAjax    是否为ajax请求（0/1）
 * @param  integer $errorCode isAjax=1时，参数缺失提醒的错误码
 * @param  string  $errorMsg  isAjax=1时，参数缺失提醒的错误内容
 * @param  string  $errorTips isAjax=1时，参数缺失提醒的错误汉字提醒
 * @return string             参数内容
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2020-05-30
 */
function inputGet($dataName = '', $allowNull = 0, $isAjax = 0, $errorCode = 0, $errorMsg = 'Lack parameter', $errorTips = '')
{
	$errorTips = $errorTips != '' ? $errorTips : '参数[' . $dataName . ']缺失';

	if (isset($_GET[$dataName])) {
		if ($allowNull != 1 && $_GET[$dataName] == "") {
			return $isAjax == 1 ? packApiData($errorCode, $errorMsg, [], $errorTips, false, true) : die();
		} else {
			return $_GET[$dataName];
		}
	} elseif ($allowNull == 1) {
		return null;
	} else {
		return $isAjax == 1 ? packApiData($errorCode, $errorMsg, [], $errorTips, false, true) : die();
	}
}


/**
 * 获取HTTP-Post数据
 * @param  string  $dataName  参数名称
 * @param  integer $allowNull 是否允许为空（0/1）
 * @param  integer $isAjax    是否为ajax请求（0/1）
 * @param  integer $errorCode isAjax=1时，参数缺失提醒的错误码
 * @param  string  $errorMsg  isAjax=1时，参数缺失提醒的错误内容
 * @param  string  $errorTips isAjax=1时，参数缺失提醒的错误汉字提醒
 * @return string|array       参数内容
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2020-01-23
 */
function inputPost($dataName = '', $allowNull = 0, $isAjax = 0, $errorCode = 0, $errorMsg = 'Lack parameter', $errorTips = '')
{
	$errorTips = $errorTips != '' ? $errorTips : '参数[' . $dataName . ']缺失';

	// 支持json格式的post
	if (strpos(Request::header('content-type'), 'application/json') !== false) {
		$content = file_get_contents('php://input');
		$postdata = json_decode($content, true);
	} else {
		$postdata = $_POST;
	}

	if (isset($postdata[$dataName])) {
		if ($allowNull != 1 && $postdata[$dataName] == "") {
			return $isAjax == 1 ? packApiData($errorCode, $errorMsg, [], $errorTips, false, true) : die();
		} else {
			return $postdata[$dataName];
		}
	} elseif ($allowNull == 1) {
		return null;
	} else {
		return $isAjax == 1 ? packApiData($errorCode, $errorMsg, [], $errorTips, false, true) : die();
	}
}


/**
 * curl请求封装函数
 * @param  string  $url          请求URL
 * @param  string  $type         请求类型(get/post)
 * @param  string  $returnType   返回数据类型(空/json)
 * @param  array   $header       自定义请求头
 * @param  array   $postData     需要POST的数据
 * @param  string  $postDataType POST数据类型(form/json)
 * @param  integer $timeout      超时秒数(单位:秒)
 * @param  string  $userAgent    UserAgent
 * @return string|array          返回结果(类型看returnType)
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2021-06-13
 */
function curl($url, $type = 'get', $returnType = '', $header = [], $postData = [], $postDataType = 'form', $timeout = 5, $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36')
{
	if ($url == '' || $timeout <= 0) {
		return false;
	}

	$ch = curl_init((string) $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, (int) $timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

	if ($type === 'post') {
		if ($postDataType === 'json') {
			$postData = json_encode($postData);
			array_push($header, 'Content-Type: application/json');
		}

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	}

	if (count($header) > 0) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

	$rtn = curl_exec($ch);
	if ($rtn === false) $rtn = 'curlError:' . curl_error($ch);
	curl_close($ch);

	$rtn = ($returnType === 'json') ? json_decode($rtn, true) : $rtn;

	return $rtn;
}
