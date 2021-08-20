<?php

/**
 * @name 生蚝科技统一身份认证平台-C-首页
 * @author Oyster Cheung <master@xshgzs.com>
 * @since 2020-07-12
 * @version V3.0 2021-08-16
 */

namespace app\controller;

use think\facade\Session;
use app\model\User as UserModel;
use app\model\Role as RoleModel;
use app\model\App as AppModel;
use app\model\LoginToken as LoginTokenModel;
use app\model\ActiveTicket as ActiveTicketModel;
use PHPMailer\PHPMailer\PHPMailer;
use think\facade\Route;

class Index
{
	public function index()
	{
		if (!Session::has('userInfo.id')) gotourl('/logout');
		elseif (RoleModel::field('is_admin')->where('key_id', Session::has('userInfo.roleId'))->find()['is_admin'] == 1) return view('/index/adminIndex', [
			'pageName' => '管理员首页',
			'pagePath' => []
		]);
		else return view('/index/index', [
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
		$appId = inputGet('appId', 1);
		$redirectUrl = inputGet('redirectUrl', 1);

		$showAppId = getSetting('ssoCenterAppId');
		$showAppName = getSetting('appName');

		// 如果已经有存过登录的应用信息，而且这次登录的和session里的相同
		if (Session::has('loginAppInfo') && Session::get('loginAppInfo.id') == $appId) {
			$showAppId = Session::get('loginAppInfo.id');
			$showAppName = Session::get('loginAppInfo.name');
			$redirectUrl = Session::get('loginAppInfo.redirectUrl');
		} else {
			// 如果有指定appId
			if (strlen($appId) == 20) {
				$appQuery = AppModel::field('name,redirect_url')->where('app_id', $appId)->find();

				if (isset($appQuery['name'])) {
					if (urldecode($redirectUrl) !== $appQuery['redirect_url']) die(gotourl('/error/appInfo'));

					$showAppId = $appId;
					$showAppName = $appQuery['name'];
				}
			} else {
				$redirectUrl = (string) url('/')->domain(true);
			}

			Session::set('loginAppInfo', ['id' => $showAppId, 'name' => $showAppName, 'redirectUrl' => $redirectUrl]);
		}

		// 已登录，生成token并直接跳转
		if (Session::has('userInfo.id')) {
			$token = sha1(getRandomStr(10));
			LoginTokenModel::insert([
				'token' => $token,
				'ip' => getIP(),
				'status' => 1,
				'user_id' => Session::get('userInfo.id'),
				'expire_time' => date('Y-m-d H:i:s', strtotime('+2 hours'))
			]);
			die(header('location:' . $redirectUrl . '?token=' . $token));
		}

		return view('/index/login', ['appId' => $showAppId, 'appName' => $showAppName]);
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

		// 账户被禁用或未激活
		if ($userInfo['status'] == 2) return packApiData(1, 'Current account is not actived', [], '当前账号尚未激活<hr>请登录注册时的邮箱<br>并点击“激活通行证”按钮进行激活');
		elseif ($userInfo['status'] !== 1) return packApiData(1, 'Current account is locked', [], '当前账号被锁定');

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

		$token = sha1(getRandomStr(10));
		LoginTokenModel::insert([
			'token' => $token,
			'ip' => getIP(),
			'status' => 1,
			'user_id' => $userInfo['id'],
			'expire_time' => date('Y-m-d H:i:s', strtotime('+2 hours'))
		]);

		$url = (Session::has('loginAppInfo.redirectUrl')) ? Session::get('loginAppInfo.redirectUrl') . '?token=' . $token : \think\facade\Config::get('app.app_host');

		return packApiData(200, 'success', [
			'url' => $url,
			'userInfo' => $userInfo
		]);
	}


	public function register()
	{
		return view('/index/register');
	}


	public function toRegister()
	{
		$userName = inputPost('user_name', 0, 1);
		$nickName = inputPost('nick_name', 0, 1);
		$password = inputPost('password', 0, 1);
		$phone = inputPost('phone', 0, 1);
		$email = inputPost('email', 0, 1);

		$realPassword = '';
		openssl_private_decrypt(base64_decode($password), $realPassword, openssl_pkey_get_private(file_get_contents(root_path() . 'pkey/rsa_private_key_pwd.pem')), OPENSSL_PKCS1_PADDING);

		if ($realPassword == '') return packApiData(5001, 'Failed to decrypt password', [], '密码处理失败');

		$salt = getRandomStr(10);
		$defaultRoleId = RoleModel::field('id')->getByIsDefault(1);
		$data = $_POST;
		$data['id'] = \Snowflake::getInstance()->setWorkId((int)substr($phone, 0, 3))->id();
		$data['salt'] = $salt;
		$data['password'] = sha1($salt . md5($userName . $realPassword) . $realPassword);
		$data['status'] = 2;
		$data['extra_param'] = '{}';
		$data['role_id'] = $defaultRoleId['id'];

		UserModel::create($data, ['id', 'user_name', 'nick_name', 'password', 'salt', 'role_id', 'status', 'extra_param', 'phone', 'email']);
		$sendMailRes = $this->sendCode2ActivePassport($data['id'], $nickName, $email, time());

		if ($sendMailRes[0]) return packApiData(200, 'success', [], '成功', false);
		else return packApiData(5002, 'Failed to send email', ['errorInfo' => $sendMailRes[1]], '激活邮件发送失败');
	}


	public function sendCode2ActivePassport($id = 0, $nickName = '', $email = '', $time = 0)
	{
		$ticket = makeUUID();
		$expireTime = $time + 900;
		$activeUrl = Route::buildUrl('/activePassport?ticket=' . $ticket)->domain(true);

		ActiveTicketModel::create(['ticket' => $ticket, 'user_id' => $id, 'ip' => getIP(), 'expire_time' => $expireTime]);

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
		$mail->addAddress($email); // 设置收件人信息 

		$mail->isHTML(true);
		$mail->Subject = '激活通行证 / ' . getSetting('appName'); // 邮件标题
		$mail->Body = '<div style="font-family: ' . "'" . 'Source Sans Pro' . "','" . 'Helvetica Neue' . "'" . ',Helvetica,Arial,sans-serif;font-size:20px;">
		<p>尊敬的' . $nickName . '用户：</p>
		您好！<hr>
		<p>感谢您于' . date('Y-m-d H:i:s', $time) . '注册了生蚝科技通行证！</p>
		<p>为保证用户正常使用我们的系统，请您点击下方的链接以帮助我们确认您的电子邮件地址有效：</p>
		<a style="margin-top:25px;background-color: #07c160;position: relative;display: block;width: 184px;margin-left: auto;margin-right: auto;padding: 8px 24px;box-sizing: border-box;font-weight: 700;font-size: 17px;text-align: center;text-decoration: none;color: #fff;line-height: 1.41176471;border-radius: 4px;-webkit-tap-highlight-color: rgba(0,0,0,0);overflow: hidden;" href="' . $activeUrl . '">激活通行证</a><br>
		<p style="font-size:15px;">(如点击无响应，请复制此链接到浏览器访问：' . $activeUrl . ')</p><hr>
		如不是您本人的操作，请忽略此邮件<br>感谢您的配合！<br>
		<p style="text-align:right">由' . getSetting('companyName') . '总部发送 ♥<br>' . date('Y-m-d') . '</p>
		</div>';

		if ($mail->send()) return [true, []];
		else return [false, $mail->ErrorInfo];
	}


	public function activePassport()
	{
		$ticket = inputGet('ticket');

		if (strlen($ticket) !== 36) die(gotourl('/error?a=error&t=非法激活凭证&b=0'));
		else $info = ActiveTicketModel::getByTicket($ticket);

		if ($info === null) gotourl('/error?a=warning&t=激活凭证不存在&b=0');
		else $info = $info->toArray();

		if (time() > $info['expire_time']) gotourl('/error?a=waiting&t=激活凭证已过期&b=0');
		if (UserModel::field('status')->getById($info['user_id'])['status'] !== 2) gotourl('/error?a=warning&b=1&t=您的通行证已成功激活<br>无需再次激活');

		UserModel::update([
			'status' => 1,
			'update_time' => date('Y-m-d H:i:s')
		], ['id' => $info['user_id']]);
		ActiveTicketModel::where('ticket', $ticket)->whereOr('expire_time', '<', time())->delete();

		return view('/index/activePassport');
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
