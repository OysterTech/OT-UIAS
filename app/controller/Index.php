<?php

/**
 * @name 生蚝科技TP6-RBAC开发框架-C-首页
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version 2020-07-16
 */

namespace app\controller;

use think\facade\Session;
use app\model\User as UserModel;
use app\model\Role as RoleModel;
use PHPMailer\PHPMailer\PHPMailer;

class Index
{
	public function index()
	{
		return view('/index/index', [
			'pageName' => '首页',
			'pagePath' => []
		]);
	}


	public function logout()
	{
		Session::clear();
		gotourl('/login');
	}


	public function login()
	{
		return view('/index/login');
	}


	public function toLogin()
	{
		$userName = inputPost('userName', 0, 1);
		$password = inputPost('password', 0, 1);

		if (checkPassword($userName, $password) !== true) {
			return packApiData(403, 'Invalid userName or password', [], '用户名或密码错误');
		}

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

		// 设置用户信息session
		$userInfo['userName'] = $userName;
		Session::set('userInfo', $userInfo);

		// 更新用户最后登录时间
		UserModel::update(['last_login' => date('Y-m-d H:i:s')], ['user_name' => $userName]);

		return packApiData(200, 'success', [
			'url' => \think\facade\Config::get('app.app_host'),
			'userInfo' => $userInfo
		]);
	}


	public function forgetPassword()
	{
		return view('/index/forgetPassword');
	}


	public function toResetPassword()
	{
		if (Session::getId() !== Session::get('forgetPwd_sessionId')) {
			return packApiData(4001, 'Invalid session', [], '非法的会话状态<br>请刷新重试');
		}
		if (time() > Session::get('forgetPwd_expireTime')) {
			return packApiData(4002, 'Code expired', [], '验证码已失效<br>请重新获取<hr>请注意需在15分钟内完成操作');
		}

		$address = inputPost('address', 0, 1);
		$code = inputPost('code', 0, 1);
		$password = inputPost('password', 0, 1);

		if ($address !== Session::get('forgetPwd_address')) {
			return packApiData(4003, 'Invalid address', [], '此邮箱未获取验证码<br>请刷新重试');
		}
		if ($code != Session::get('forgetPwd_code')) {
			return packApiData(4004, 'Invalid code', [], '无效的邮箱验证码<br>请重新获取');
		}

		$userInfo = Session::get('forgetPwd_userInfo');
		$salt = getRandomStr(10);
		$hash = sha1($salt . md5($userInfo['user_name'] . $password) . $password);

		$query = UserModel::where('email', $address)
			->update([
				'salt' => $salt,
				'password' => $hash,
				'update_time' => date('Y-m-d H:i:s')
			]);

		return packApiData(200, 'success');
	}


	public function sendCode2ForgetPassword()
	{
		$address = inputPost('address', 0, 1);
		$code = mt_rand(123456, 987654);
		$expireTime = time() + 900;

		$userInfo = UserModel::where('email', $address)->find();

		if (!isset($userInfo['id'])) return packApiData(4001, 'User not found', [], '无此邮箱对应的用户');

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->CharSet = 'utf8';
		$mail->Host = getSetting('smtpHost'); // 发送方的SMTP服务器地址
		$mail->SMTPAuth = true; // 是否使用身份验证
		$mail->Username = getSetting('smtpUserName'); // 发送方的邮箱用户名
		$mail->Password = getSetting('smtpPassword'); // 发送方的邮箱密码
		$mail->SMTPSecure = 'ssl'; // 使用ssl协议方式
		$mail->Port = getSetting('smtpPort'); // 163邮箱的ssl协议方式端口号是465/994

		$mail->setFrom($mail->Username, getSetting('companyName')); // 设置发件人信息
		$mail->addAddress($address); // 设置收件人信息 

		$mail->isHTML(true);
		$mail->Subject = '找回密码 / ' . getSetting('appName'); // 邮件标题
		$mail->Body = '尊敬的' . $userInfo['nick_name'] . '(' . $userInfo['user_name'] . ')用户：<br>您好！<hr>您正在请求找回密码，<br>请在' . date('Y-m-d H:i:s', $expireTime) . '前输入以下6位数字以验证您的身份：【<font color="red"><b>' . $code . '</b></font>】<hr>如不是您本人的操作，请忽略此邮件<br>感谢您的配合！<br><p style="text-align:right">' . getSetting('companyName') . '&nbsp;' . getSetting('companyCreateYear') . '-' . date('Y') . '<br>' . date('Y-m-d H:i') . '</p>';

		if (!$mail->send()) {
			return packApiData(500, 'Failed to send email', ['errorInfo' => $mail->ErrorInfo]);
		} else {
			Session::set('forgetPwd_sessionId', Session::getId());
			Session::set('forgetPwd_address', $address);
			Session::set('forgetPwd_code', $code);
			Session::set('forgetPwd_expireTime', $expireTime);
			Session::set('forgetPwd_userInfo', $userInfo);
			return packApiData(200, 'success');
		}
	}
}
