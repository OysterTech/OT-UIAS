<?php
/**
 * @name 生蚝科技统一身份认证平台-C-登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-02-16
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;
	public $CAPTCHA_ID="a3b962b474e6b1d40ae2c19bc621088a";
	public $PRIVATE_KEY="0a45afb6d67750f8d8d6570686c68f9f";

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}

	public function login($appId='',$returnUrl='')
	{
		$returnUrl=$returnUrl!=''?urldecode($returnUrl):base_url('dashborad');
		$appInfo=$this->app->get($appId,$returnUrl);

		if($appInfo==array()){
			if($appId!="" && $returnUrl!=base_url('dashborad')){
				gotoUrl(base_url('error/appInfo'));
			}else{
				$appId=$this->setting->get('SSOUCAppId');
				$appName='生蚝科技用户中心';
			}
		}else{
			$appName=$appInfo['name'];
		}

		$this->session->set_userdata($this->sessPrefix.'appId',$appId);
		$this->session->set_userdata($this->sessPrefix.'returnUrl',$returnUrl);

		if($this->session->userdata($this->sessPrefix.'isLogin')==1){
			$token=sha1(md5($appId).time());
			$this->session->set_userdata($this->sessPrefix.'token',$token);
			
			$tokenQuery=$this->user->addLoginToken($token,$this->session->userdata($this->sessPrefix.'user_id'));
			if($tokenQuery===true) gotoUrl($returnUrl.'?token='.$token);
		}

		$this->load->view('login',['appId'=>$appId,'appName'=>$appName,'returnUrl'=>$returnUrl]);
	}


	public function toLogin()
	{
		$appId=inputPost('appId',0,1);
		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);

		$userInfo=$this->user->getInfoByUserName($userName);
		
		if($userInfo==array()){
			returnAjaxData(4031,"failed To Auth");
		}else{
			$checkPwd=$this->safe->checkPassword($password,$userInfo['password'],$userInfo['salt']);
			
			// 判断密码有效性
			if($checkPwd!==true){
				returnAjaxData(4031,"failed To Auth");
			}else{
				$this->db->where('user_name',$userName);
				$this->db->update('user',['last_login'=>date('Y-m-d H:i:s')]);
				
				$token=sha1(md5($appId).time());
				$sessionData=array(
					$this->sessPrefix.'isLogin'=>1,
					$this->sessPrefix.'token'=>$token,
					$this->sessPrefix.'userName'=>$userName,
					$this->sessPrefix.'nickName'=>$userInfo['nick_name'],
					$this->sessPrefix.'role_id'=>$userInfo['role_id'],
					$this->sessPrefix.'user_id'=>$userInfo['id'],
					$this->sessPrefix.'unionId'=>$userInfo['union_id']
				);
				$this->session->set_userdata($sessionData);
				
				$appPermission=explode(",",$userInfo['app_permission']);
				$appInfo=$this->app->get($appId);

				if($appId!="" && $appInfo==array()){
					// 没有此应用
					returnAjaxData(4032,"permission Denied");
				}elseif($appId!="" && in_array($appInfo['id'],$appPermission)!=true){
					// 没有权限访问
					returnAjaxData(4032,"permission Denied");
				}else{
					// 有权限，跳转回应用登录页
					$tokenQuery=$this->user->addLoginToken($token,$userInfo['id']);
					if($tokenQuery===true) returnAjaxData(200,"success",['returnUrl'=>$this->session->userdata($this->sessPrefix.'returnUrl')."?token=".$token]);
					else returnAjaxData(500,"database Error");
				}
			}
		}
	}


	public function logout($appId='',$returnUrl='',$service='')
	{
		session_destroy();
		$url=base_url('login');

		if($service!="") $url.='/?service='.urlencode($service);

		if($appId!='' && $returnUrl!=''){
			$url.='/'.$appId.'/'.$returnUrl;
		}

		gotoUrl($url);
	}


	/*public function register_step1()
	{
		include 'application/libraries/Geetestlib.php';
		$GtSdk = new GeetestLib($this->CAPTCHA_ID,$this->PRIVATE_KEY);
		$data = array(
			"user_id" => getIP(),
			"client_type" => "web",
			"ip_address" => getIP()
		);

		$status=$GtSdk->pre_process($data,1);
		$_SESSION['gtserver'] = $status;
		$_SESSION['user_id']=$data['user_id'];

		$this->load->view('register_step1',['GtSdk'=>$GtSdk]);
	}


	public function toRegister_step1(){
		$phone=isset($_POST['phone'])&&strlen($_POST['phone'])==11?$_POST['phone']:returnAjaxData(0,"lack Param");
		$messageCode=isset($_POST['messageCode'])&&$_POST['messageCode']!=""?$_POST['messageCode']:returnAjaxData(0,"lack Param");

		if($phone==$this->session->userdata($this->sessPrefix.'reg_msgPhone') && $messageCode==$this->session->userdata($this->sessPrefix.'reg_msgCode') && time()<=$this->session->userdata($this->sessPrefix.'reg_msgExpireTime')){
			$ticket=sha1($phone.time());
			
			$this->session->set_userdata($this->sessPrefix.'reg_phone',$phone);
			$this->session->set_userdata($this->sessPrefix.'reg_ticket',$ticket);

			returnAjaxData(200,"success",['url'=>base_url('register_step2/'.$ticket)]);
		}else{
			if($this->session->userdata($this->sessPrefix.'reg_msgExpireTime')!=NULL && time()>$this->session->userdata($this->sessPrefix.'reg_msgExpireTime')){
				returnAjaxData(1,"expire Time");
			}else{
				returnAjaxData(403,"invaild Code");
			}
		}
	}


	public function register_step2($ticket='')
	{
		if($ticket=='' || $this->session->userdata($this->sessPrefix.'reg_ticket')!=$ticket){
			gotoUrl(base_url());
		}else{
			$this->load->view('register_step2');
		}
	}


	public function toRegister_step2()
	{
		$accountType=isset($_POST['accountType'])&&$_POST['accountType']!=""?$_POST['accountType']:returnAjaxData(0,"lack Param");
		$userName=isset($_POST['userName'])&&$_POST['userName']!=""?$_POST['userName']:returnAjaxData(0,"lack Param");
		$password=isset($_POST['password'])&&$_POST['password']!=""?$_POST['password']:returnAjaxData(0,"lack Param");
		$nickName=isset($_POST['nickName'])&&$_POST['nickName']!=""?$_POST['nickName']:returnAjaxData(0,"lack Param");
		$email=isset($_POST['email'])&&$_POST['email']!=""?$_POST['email']:returnAjaxData(0,"lack Param");

		if($accountType=="person"){
			$school=isset($_POST['school'])&&$_POST['school']!=""?$_POST['school']:returnAjaxData(0,"lack Param");
			$extraParam="{}";
			$originIntegral=$this->setting->get('originIntegral_User');
			$originSpecialIntegral=$this->setting->get('originSpecialIntegral_User');
		}elseif($accountType=="organization"){
			$phone2=isset($_POST['phone2'])&&$_POST['phone2']!=""?$_POST['phone2']:returnAjaxData(0,"lack Param");
			$school=isset($_POST['organization'])&&$_POST['organization']!=""?$_POST['organization']:returnAjaxData(0,"lack Param");
			$extraParam=json_encode(['phone2'=>$phone2]);
			$originIntegral=$this->setting->get('originIntegral_Org');
			$originSpecialIntegral=$this->setting->get('originSpecialIntegral_Org');
		}


		// 扣除积分池余额
		$integralPoolBalance=$this->setting->get('integralPoolBalance');
		$integralPoolBalance=$integralPoolBalance-$originIntegral-$originSpecialIntegral;
		$this->setting->save('integralPoolBalance',$integralPoolBalance);

		// 再次校验用户名是否已存在
		$this->db->select('id');
		$query1=$this->db->get_where('user',array('user_name',$userName));
		if($query1->num_rows()>=1){
			returnAjaxData(1,"have UserName");
		}

		// 再次校验手机号是否已存在
		$this->db->select('id');
		$query2=$this->db->get_where('user',array('phone',$this->session->userdata($this->sessPrefix.'reg_phone')));
		if($query2->num_rows()>=1){
			returnAjaxData(2,"have Phone");
		}

		// 获取默认角色资料
		$this->db->select('id');
		$this->db->where('is_default=',1);
		if($accountType=="organization") $this->db->where('is_org=',1);
		else $this->db->where('is_org=',0);
		$roleInfoQuery=$this->db->get('role');
		if($roleInfoQuery->num_rows()!=1){
			returnAjaxData(3,"no Role Info");
		}else{
			$roleInfo=$roleInfoQuery->result_array();
			$roleId=$roleInfo[0]['id'];
		}			

		// 获取初始应用权限
		$appPermission=array();
		$appPermissionQuery=$this->db->get_where('app',['status'=>1]);
		$appPermissionList=$appPermissionQuery->result_array();
		foreach($appPermissionList as $appInfo){
			array_push($appPermission,$appInfo['id']);
		}
		$appPermissionStr=implode(",",$appPermission);

		$unionId=substr(strtoupper(sha1(getRanSTR(8))),mt_rand(10,32),8);
		$salt=getRanSTR(8);
		$hash=sha1(md5($password).$salt);

		$data=array(
			'union_id'=>$unionId,
			'user_name'=>$userName,
			'nick_name'=>$nickName,
			'password'=>$hash,
			'salt'=>$salt,
			'app_permission'=>$appPermissionStr,
			'role_id'=>$roleId,
			'integral'=>$originIntegral,
			'special_integral'=>$originSpecialIntegral,
			'phone'=>$this->session->userdata($this->sessPrefix.'reg_phone'),
			'email'=>$email,
			'school'=>$school,
			'extra_param'=>$extraParam
		);

		$query=$this->db->insert('user',$data);

		if($query==true) returnAjaxData(200,"success");
		else returnAjaxData(500,"database Error");
	}


	public function gtVerify()
	{
		include 'application/libraries/Geetestlib.php';
		$GtSdk=new GeetestLib($this->CAPTCHA_ID,$this->PRIVATE_KEY);
		$data=array(
			"user_id" => $_SESSION['user_id'],
			"client_type" => "web",
			"ip_address" => getIP()
		);

		if($_SESSION['gtserver']==1){
			$result=$GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}else{
			if($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
				echo '1';
			}else{
				echo '0';
			}
		}
	}


	public function toSendMessageCode()
	{
		$phone=isset($_POST['phone'])&&strlen($_POST['phone'])==11?$_POST['phone']:returnAjaxData(0,"lack Param");

		if($this->session->userdata($this->sessPrefix.'reg_msgNextTime')>=time()){
			returnAjaxData(1,"send Frequently");
		}

		$this->db->select('id');
		$query=$this->db->get_where('user',array('phone',$phone));
		if($query->num_rows()>=1){
			returnAjaxData(2,"have Phone");
		}

		$code=mt_rand(123456,987654);
		$nextTime=time()+60;
		$expireTime=time()+3*60;

		$MessageContent='【ITRClub】尊敬的用户：您的注册验证码为['.$code.']。感谢您的支持！';
		$postData = array();
		$postData['userid'] = 1660;
		$postData['account'] = 'itrclub';
		$postData['password'] = '52itr52itr';
		$postData['content'] = $MessageContent;
		$postData['mobile'] = $phone;
		$postData['sendtime'] = '';

		$url='http://119.23.126.199:8888/sms.aspx?action=send';
		$o='';

		foreach ($postData as $k=>$v)
		{
			$o.="$k=".urlencode($v).'&';
		}

		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

		$data=array('phone'=>$phone,'content'=>'注册验证码');
		$this->db->insert('send_message',$data);
		
		$sessionData=array(
			$this->sessPrefix.'reg_msgPhone'=>$phone,
			$this->sessPrefix.'reg_msgCode'=>$code,
			$this->sessPrefix.'reg_msgNextTime'=>$nextTime,
			$this->sessPrefix.'reg_msgExpireTime'=>$expireTime
		);
		$this->session->set_userdata($sessionData);

		if(stripos($result,"Success")==true){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(400,"bad Request",[$result]);
		}
	}*/
}
