<?php

/**
 * @name 生蚝科技统一身份认证平台-C-第三方登录
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-08-03
 * @version V3.0 2020-08-03
 */

namespace app\controller;

//use jasig\phpcas;

class ThirdLogin
{
	public function yuque()
	{
		$set = '{"id":"9TJNmOaEl8lmt2ZFqbKd","secret":"x6nqPnTv1uzc199eEoZwG43LSzdoVcCtT33elKoS","redirectUrl":"https%3A%2F%2Fid.xshgzs.com%2FthirdLogin%2Fyuque"}';
		$setting = json_decode($set, true);

		$oauthUrl = 'https://www.yuque.com/oauth2/authorize?client_id=' . $setting['id'] . '&response_type=code&redirect_uri=' . $setting['redirectUrl'] . '&state=' . sha1($_SERVER['SERVER_NAME'] . getIP());

		if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] != sha1($_SERVER['SERVER_NAME'] . getIP())) die(header('location:' . $oauthUrl));
		else $code = $_GET['code'];

		$tokenQuery = json_decode(curl('https://www.yuque.com/oauth2/token', 'post', [
			'client_id' => $setting['id'],
			'client_secret' => $setting['secret'],
			'code' => $code,
			'grant_type' => 'authorization_code'
		], 'json'), true);

		if (isset($tokenQuery['access_token'])) $token = $tokenQuery['access_token'];
		else die(header('location:' . $oauthUrl));

		$userInfoQuery = json_decode(curl('https://www.yuque.com/api/v2/user', 'get', [], 'array', ['X-Auth-Token:' . $token]), true);
		die(var_dump($userInfoQuery));
	}
}
