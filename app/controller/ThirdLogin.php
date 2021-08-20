<?php

/**
 * @name 生蚝科技统一身份认证平台-C-第三方登录
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-08-03
 * @version V3.0 2021-08-20
 */

namespace app\controller;

require_once root_path() . 'extend/CAS-1.3.5/CAS.php';

use app\model\ThirdUser as ThirdUserModel;
use app\model\User as UserModel;
use app\model\Role as RoleModel;
use app\model\LoginToken as LoginTokenModel;
use think\facade\Session;

class ThirdLogin
{
	public function checkBind($type, $id = '')
	{
		$query = ThirdUserModel::where('platform', $type)
			->where('third_id', $id)
			->find();

		if ($query == []) {
			Session::set('thirdBind_platform', $type);
			Session::set('thirdBind_thirdId', $id);
		} else {
		}
	}


	public function gzlib()
	{
		\phpCAS::client(CAS_VERSION_2_0, 'login.gzlib.org.cn', 80, 'sso-server');
		\phpCAS::setLang(PHPCAS_LANG_CHINESE_SIMPLIFIED);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::handleLogoutRequests(); // 接受同步推出请求

		if (\phpCAS::isAuthenticated()) {
			$idcard = \phpCAS::getUser(); // 获取CAS用户标识（UID）
			$userInfo = ThirdUserModel::getUserInfo('gzlib', $idcard);

			if ($userInfo == []) return $this->goBind('gzlib', $idcard);
			else return $this->login($userInfo);
		} else {
			return \phpCAS::forceAuthentication(); // 重定向到CAS登录
		}
	}


	public function yuque()
	{
		$set = '{"id":"9TJNmOaEl8lmt2ZFqbKd","secret":"x6nqPnTv1uzc199eEoZwG43LSzdoVcCtT33elKoS","redirectUrl":"https%3A%2F%2Fid.xshgzs.com%2FthirdLogin%2Fyuque"}';
		$setting = json_decode($set, true);

		$oauthUrl = 'https://www.yuque.com/oauth2/authorize?client_id=' . $setting['id'] . '&response_type=code&redirect_uri=' . $setting['redirectUrl'] . '&state=' . sha1($_SERVER['SERVER_NAME'] . getIP());

		if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] != sha1($_SERVER['SERVER_NAME'] . getIP())) die(header('location:' . $oauthUrl));
		else $code = $_GET['code'];

		$tokenQuery = curl('https://www.yuque.com/oauth2/token', 'post', 'json', [], [
			'client_id' => $setting['id'],
			'client_secret' => $setting['secret'],
			'code' => $code,
			'grant_type' => 'authorization_code'
		], 'json');

		if (isset($tokenQuery['access_token'])) $token = $tokenQuery['access_token'];
		else die(header('location:' . $oauthUrl));

		$userInfoQuery = curl('https://www.yuque.com/api/v2/user', 'get', 'json', ['X-Auth-Token:' . $token]);

		if (isset($userInfoQuery['data']['account_id'])) {
			$userInfo = ThirdUserModel::getUserInfo('yuque', $userInfoQuery['data']['account_id']);

			if ($userInfo == []) return $this->goBind('yuque', $userInfoQuery['data']['account_id']);
			else return $this->login($userInfo);
		} else {
			return view('/error', [
				'status' => 'error',
				'content' => '获取语雀用户信息失败<br>请尝试其他方式登录',
				'canBack' => '1',
				'hideTitle' => '1'
			]);
		}
	}


	public function github()
	{
		if (inputGet('code', 1) == null) die(header('location: https://github.com/login/oauth/authorize?client_id=' . env('github.client_id') . '&redirect_uri=' . urlencode(env('github.redirect_uri')) . '&state=' . md5(getRandomStr(6))));
		else $postData = [
			'code' => inputGet('code'),
			'client_id' => env('github.client_id'),
			'client_secret' => env('github.client_secret'),
		];

		$query = curl('https://github.com/login/oauth/access_token', 'post', 'json', ['Accept: application/json'], $postData,  'json', 10);

		if (!isset($query['access_token'])) {
			if ($query['error_description'] === 'The code passed is incorrect or expired.') {
				die(header('location: https://github.com/login/oauth/authorize?client_id=' . env('github.client_id') . '&redirect_uri=' . urlencode(env('github.redirect_uri')) . '&state=' . md5(getRandomStr(6))));
			} else {
				return view('/error', [
					'status' => 'error',
					'content' => 'Github用户凭证无效<br>请尝试其他方式登录<br>' . $query['error_description'],
					'canBack' => '1',
					'hideTitle' => '1'
				]);
			}
		}

		$userInfoQuery = curl('https://api.github.com/user', 'get', 'json', ['Authorization: token ' . $query['access_token']]);

		if (isset($userInfoQuery['id'])) {
			$userInfo = ThirdUserModel::getUserInfo('github', $userInfoQuery['id']);

			if ($userInfo == []) return $this->goBind('github', $userInfoQuery['id']);
			else return $this->login($userInfo);
		} else {
			return view('/error', [
				'status' => 'error',
				'content' => '获取Github用户信息失败<br>请尝试其他方式登录',
				'canBack' => '1',
				'hideTitle' => '1'
			]);
		}
	}


	public function workWeixin()
	{
		if (inputGet('code', 1) == null) die(header('location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . env('workweixin.corp_id') . '&redirect_uri=' . urlencode(env('workweixin.redirect_uri')) . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect'));
		else $userIdQuery = curl('https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=' . getWWAT() . '&code=' . inputGet('code'), 'get', 'json');

		if (isset($userIdQuery['UserId'])) {
			$userInfo = ThirdUserModel::getUserInfo('workweixin', $userIdQuery['UserId']);

			if ($userInfo == []) return $this->goBind('workweixin', $userIdQuery['UserId']);
			else return $this->login($userInfo);
		} else {
			return view('/error', [
				'status' => 'error',
				'content' => '获取企业微信员工工号失败<br>请尝试其他方式登录<br>错误码: '.$userIdQuery['errcode'],
				'canBack' => '1',
				'hideTitle' => '1'
			]);
		}
	}


	private function goBind($platform = '', $thirdId = '')
	{
		$bindToken = md5($platform . time() . $thirdId);
		Session::set('bindTokenInfo', ['token' => $bindToken, 'platform' => $platform, 'thirdId' => $thirdId]);

		header('location: bind?token=' . $bindToken);
	}


	public function bind()
	{
		$bindToken = inputGet('token');

		return view('bindUser', [
			'bindToken' => $bindToken
		]);
	}


	public function toBind()
	{
		$token = inputPost('token', 0, 1);
		$userName = inputPost('userName', 0, 1);
		$password = inputPost('password', 0, 1);

		if (!Session::has('bindTokenInfo.token') || Session::get('bindTokenInfo.token') !== $token) {
			return packApiData(4001, 'Invalid token', [], 'token无效');
		} else {
			$tokenInfo = Session::get('bindTokenInfo');
		}

		$checkDuplicate = ThirdUserModel::where('platform', $tokenInfo['platform'])
			->where('third_id', $tokenInfo['thirdId'])
			->count();

		if ($checkDuplicate > 0) return packApiData(4002, 'This third party account has bound the passport', [], '此第三方帐号已绑定通行证', false);

		if (checkPassword($userName, $password) !== true) {
			return packApiData(403, 'Invalid userName or password', [], '用户名或密码错误');
		} else {
			$userId = UserModel::field('id')->where('user_name', $userName)->find()['id'];
			$userInfo = UserModel::field('id,nick_name AS nickName,role_id AS roleId,phone,email,status,create_time AS createTime')
				->where('user_name', $userName)
				->find()
				->toArray();

			// 账户被禁用
			if ($userInfo['status'] !== 1) return packApiData(1, 'Current account is locked', [], '当前账号被锁定');

			// 获取角色名称
			$roleInfo = RoleModel::field('name')
				->where('id', $userInfo['roleId'])
				->find();

			if (isset($roleInfo['name']) && $roleInfo['name']) $userInfo['roleName'] = $roleInfo['name'];
			else return packApiData(2, 'Role info not found', ['roleId' => $userInfo['roleId']], '查询角色信息失败');
		}

		$query = ThirdUserModel::create([
			'user_id' => $userId,
			'platform' => $tokenInfo['platform'],
			'third_id' => $tokenInfo['thirdId']
		], ['user_id', 'platform', 'third_id']);

		if (!$query['id'] > 0) return packApiData(500, 'Database error', ['e' => $query], '数据库出错');

		// 设置用户信息session
		$userInfo['userName'] = $userName;
		Session::set('userInfo', $userInfo);

		// 更新用户最后登录时间
		UserModel::update(['last_login' => date('Y-m-d H:i:s')], ['user_name' => $userName]);

		// 创建登录token
		$token = sha1(getRandomStr(10));
		LoginTokenModel::insert([
			'token' => $token,
			'ip' => getIP(),
			'status' => 1,
			'user_id' => $userInfo['id'],
			'expire_time' => date('Y-m-d H:i:s', strtotime('+2 hours'))
		]);

		$url = (Session::has('loginAppInfo.redirectUrl')) ? Session::get('loginAppInfo.redirectUrl') . '?token=' . $token : \think\facade\Config::get('app.app_host');

		return packApiData(200, 'success', ['url' => $url], '绑定成功', false);
	}


	public function abandonBind()
	{
		Session::delete('bindTokenInfo');
		gotourl('/login');
	}


	private function login($userInfo = [])
	{
		ThirdUserModel::update(['last_login' => date('Y-m-d H:i:s')], ['id' => $userInfo['thirdKey']]);
		UserModel::update(['last_login' => date('Y-m-d H:i:s')], ['id' => $userInfo['id']]);

		// 账户被禁用
		if ($userInfo['status'] !== 1) return view('error', [
			'status' => 'error',
			'content' => '当前账号被锁定<br>用户ID：' . $userInfo['id'],
			'canBack' => '1',
			'hideTitle' => '1',
			'bindToken' => ''
		]);

		// 获取角色名称
		$roleInfo = RoleModel::field('name')
			->where('id', $userInfo['roleId'])
			->find();

		if (isset($roleInfo['name']) && $roleInfo['name']) $userInfo['roleName'] = $roleInfo['name'];
		else return view('error', [
			'status' => 'error',
			'content' => '查询角色信息失败<br>角色ID：' . $userInfo['roleId'],
			'canBack' => '0',
			'hideTitle' => '1',
			'bindToken' => ''
		]);

		// 设置用户信息入session
		Session::set('userInfo', $userInfo);

		// 生成token给APP用
		$token = sha1(getRandomStr(10));
		LoginTokenModel::insert([
			'token' => $token,
			'ip' => getIP(),
			'status' => 1,
			'user_id' => $userInfo['id'],
			'expire_time' => date('Y-m-d H:i:s', strtotime('+2 hours'))
		]);

		$url = (Session::has('loginAppInfo.redirectUrl')) ? Session::get('loginAppInfo.redirectUrl') . '?token=' . $token : \think\facade\Config::get('app.app_host');

		// 千万别die，不然写不进session
		header('location:' . $url);
	}
}
