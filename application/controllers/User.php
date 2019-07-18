<?php
/**
 * @name 生蚝科技RBAC开发框架-C-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-19
 * @version 2019-07-18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $forgetPwdUserId;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		$this->load->helper('string');

		$this->sessPrefix=$this->safe->getSessionPrefix();
		
		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}
	
	
	public function toChangeRole()
	{
		$roleId=inputPost('roleId',0,1);
		$_SESSION[$this->sessPrefix.'roleId']=$roleId;
		
		returnAjaxData(200,'success');
	}


	public function updateProfile()
	{

		$query=$this->db->get_where('user',array('id'=>$this->nowUserId));
		$info=$query->first_row('array');
		$this->load->view('user/updateProfile',['info'=>$info]);
	}


	public function toUpdateProfile()
	{
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);

		$sql='UPDATE user SET nick_name=?,phone=?,email=? WHERE id=?';
		$query=$this->db->query($sql,[$nickName,$phone,$email,$this->nowUserId]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(1,'failed to Update');
		}
	}


	public function login()
	{
		$redirectUrl=isset($_GET['redirectUrl'])&&$_GET['redirectUrl']!=''?urldecode($_GET['redirectUrl']):base_url('dashborad');
		$appId=inputGet('appId',1);
		$appInfo=$this->app->get($appId,$redirectUrl);

		if($appInfo==array()){
			if($appId!="" && $redirectUrl!=base_url('dashborad')){
				gotoUrl(base_url('error/appInfo'));
			}else{
				$appId=$this->setting->get('SSOUCAppId');
				$appName='生蚝科技用户中心';
			}
		}else{
			$appName=$appInfo['name'];
		}

		$this->session->set_userdata($this->sessPrefix.'appId',$appId);
		$this->session->set_userdata($this->sessPrefix.'redirectUrl',$redirectUrl);

		if($this->session->userdata($this->sessPrefix.'userId')>=1){
			$token=sha1(md5($appId).time());
			$this->session->set_userdata($this->sessPrefix.'token',$token);
			
			$tokenQuery=$this->user->addLoginToken($token,$this->session->userdata($this->sessPrefix.'userId'));
			if($tokenQuery===true) gotoUrl($redirectUrl.'?token='.$token);
		}

		$this->load->view('user/login',['appId'=>$appId,'appName'=>$appName,'redirectUrl'=>$redirectUrl]);
	}


	public function toLogin()
	{
		$appId=inputPost('appId',0,1);
		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);

		$validate=$this->user->validateUser($password,0,$userName);

		if($validate==200){
			$userInfo=$this->user->getUserInfoByUserName($userName);
			$userId=$userInfo['id'];
			$unionId=$userInfo['union_id'];
			$nickName=$userInfo['nick_name'];
			$roleId=$userInfo['role_id'];
			$status=$userInfo['status'];
			
			if($status==0){
				returnAjaxData(1,'user Forbidden');
			}elseif($status==2){
				returnAjaxData(3,'user Not Active');
			}
			
			// 获取角色名称
			$roleIds=explode(',',$roleId);
			$allRoleInfo=array();
			
			$this->db->where_in('id',$roleIds);
			$roleQuery=$this->db->get('role');

			if($roleQuery->num_rows()<1){
				returnAjaxData(2,'no Role Info');
			}else{
				$roleList=$roleQuery->result_array();
				
				foreach($roleList as $roleInfo){
					$allRoleInfo[$roleInfo['id']]=$roleInfo['name'];
				}
			}
			
			$token=sha1(md5($appId).time());

			// 将用户信息存入session
			$this->session->set_userdata($this->sessPrefix.'userId',$userId);
			$this->session->set_userdata($this->sessPrefix.'token',$token);
			$this->session->set_userdata($this->sessPrefix.'nickName',$nickName);
			$this->session->set_userdata($this->sessPrefix.'userName',$userName);
			$this->session->set_userdata($this->sessPrefix.'unionId',$unionId);
			$this->session->set_userdata($this->sessPrefix.'roleId',$roleIds[0]);
			$this->session->set_userdata($this->sessPrefix.'roleName',reset($allRoleInfo));
			$this->session->set_userdata($this->sessPrefix.'allRoleInfo',$allRoleInfo);

			$this->db->query('UPDATE user SET last_login=? WHERE id=?',[date('Y-m-d H:i:s'),$userId]);

			$this->load->helper('JWT');
			$jwtToken=jwt_helper::create(['userId'=>$userId,'allRoleInfo'=>$allRoleInfo]);
			
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
				if($tokenQuery===true) returnAjaxData(200,"success",['redirectUrl'=>$this->session->userdata($this->sessPrefix.'redirectUrl')."?token=".$token,'allRoleInfo'=>json_encode($allRoleInfo),'jwtToken'=>$jwtToken]);
				else returnAjaxData(500,"database Error");
			}
		}elseif($validate==-1){
			returnAjaxData(1,"user Forbidden");
		}else{
			returnAjaxData(4031,"invaild Password");
		}
	}
	

	public function logout()
	{
		session_destroy();
		header("Location:".base_url('user/login'));
	}


	public function register()
	{
		$this->load->view('user/register');
	}
	
	
	public function toRegister()
	{
		$userName=$this->input->post('userName');
		$nickName=$this->input->post('nickName');
		$pwd=$this->input->post('pwd');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		
		// 检查用户名手机邮箱是否已存在
		$sql1='SELECT id FROM user WHERE user_name=?';
		$query1=$this->db->query($sql1,[$userName]);
		if($query1->num_rows()!=0){
			returnAjaxData(1,'have UserName');
		}
		$sql2='SELECT id FROM user WHERE phone=?';
		$query2=$this->db->query($sql2,[$phone]);
		if($query2->num_rows()!=0){
			returnAjaxData(2,'have Phone');
		}
		$sql3='SELECT id FROM user WHERE email=?';
		$query3=$this->db->query($sql3,[$email]);
		if($query3->num_rows()!=0){
			returnAjaxData(3,'have Email');
		}
		$salt=random_string('alnum');
		$hashPwd=sha1(md5($pwd).$salt);
		$roleQuery=$this->db->query('SELECT id FROM role WHERE is_default=1');
		if($roleQuery->num_rows()!=1){
			returnAjaxData(4,'no Original Role Info');
		}
		$roleList=$roleQuery->result_array();
		$roleId=$roleList[0]['id'];
	
		$sql='INSERT INTO user(user_name,nick_name,password,salt,role_id,phone,email,status) VALUES (?,?,?,?,?,?,?,1)';
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$roleId,$phone,$email]);
		
		/*$emailToken=md5(random_string('alnum',16).session_id());
		$emailURL=base_url('user/reg/verify/').$emailToken;
		$message='Email注册验证 / '.$this->setting->get("systemName").'<hr>';
		$message.='尊敬的'.$userName.'用户，感谢您注册'.$this->setting->get("systemName").'！为验证您的身份，请点击下方链接以完成注册激活：<br>';
		$message.='<a href="'.$emailURL.'">'.$emailURL.'</a><br><br>';
		$message.='此链接15分钟内有效，请尽快激活哦！若无法点击，请复制至浏览器访问。<br><br>';
		$message.='如您本人没有申请过注册本系统，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>&copy; 生蚝科技 2014-'.date('Y').'</center>';
		
		$this->load->library('email');
		$this->email->from($this->config->item('smtp_user'),'生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->setting->get("systemName").'] 注册邮箱认证');
		$this->email->message($message);
		$mailSend=$this->email->send();
		
		if($this->db->affected_rows()==1 && $mailSend==true){
			$ip=$this->input->ip_address();
			$param=array('userName'=>$userName);
			$param=json_encode($param);
			$expireTime=time()+900;
			$sql2='INSERT INTO send_mail(email,token,param,expire_time,ip) VALUES (?,?,?,?,?)';
			$query2=$this->db->query($sql2,[$email,$emailToken,$param,$expireTime,$ip]);
			returnAjaxData(200,'success');*/
		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else if($this->db->affected_rows()==1){
			returnAjaxData(5001,'success to Register But Failed to Send Active Email');
		}else{
			returnAjaxData(5002,'failed to Register');
		}
	}
	
	
	/*public function verifyRegister($token)
	{
		$sql1='SELECT expire_time,param,status FROM send_mail WHERE token=?';
		$query1=$this->db->query($sql1,[$token]);
		
		if($query1->num_rows()!=1){
			die('<script>alert("激活链接无效！\n请通过正确途径激活邮箱！");window.location.href="'.base_url().'";</script>');
		}
		
		$list=$query1->result_array();
		$info=$list[0];
		$expire_time=$info['expire_time'];
		$param=$info['param'];
		$status=$info['status'];
		
		if($expire_time<=time()){
			die('<script>alert("激活链接已过期！");window.location.href="'.base_url().'";</script>');
		}
		if($status!=0){
			die('<script>alert("激活链接已被使用！");window.location.href="'.base_url().'";</script>');
		}
		
		$param=json_decode($param,TRUE);
		$userName=$param['userName'];
		
		$sql2='UPDATE user SET status=1 WHERE user_name=?';
		$query2=$this->db->query($sql2,[$userName]);
		
		if($this->db->affected_rows()==1){
			$sql3='UPDATE send_mail SET status=1 WHERE token=?';
			$query3=$this->db->query($sql3,[$token]);
			if($this->db->affected_rows()==1){
				die('<script>alert("激活成功！请重新登录！");window.location.href="'.base_url().'";</script>');
			}else{
				die('<script>alert("激活失败！请联系管理员！");window.location.href="'.base_url().'";</script>');
			}
		}else{
			die('<script>alert("用户激活失败！请联系管理员！");window.location.href="'.base_url().'";</script>');
		}
	}*/
	
	
	public function forgetPassword()
	{
		$this->load->view('user/forgetPassword');
	}
	
	
	public function forgetPasswordSendCode()
	{
		$email=inputPost('email',0,1);
		$sql="SELECT id,user_name,nick_name FROM user WHERE email=?";
		$query=$this->db->query($sql,[$email]);
		
		if($query->num_rows()!=1){
			returnAjaxData(404,"no User");
		}else{
			$list=$query->result_array();
			$id=$list[0]['id'];
			$userName=$list[0]['user_name'];
			$nickName=$list[0]['nick_name'];
		}
		
		$verifyCode=mt_rand(123456,987654);
		$expireTime=time()+600;
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_mailInfo',array('email'=>$email,'verifyCode'=>$verifyCode,'expireTime'=>$expireTime));
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_userId',$id);
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_nickName',$nickName);
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_userName',$userName);
		
		$message='Email验证 / '.$this->setting->get("systemName").'<hr>';
		$message.='尊敬的'.$nickName.'用户，您正在申请重置密码，请填写此验证码以继续重置密码：'.$verifyCode.'<br><br>';
		$message.='此验证码15分钟内有效，请尽快填写哦！<br><br>';
		$message.='如您本人没有申请过重置密码，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>&copy; 生蚝科技 2014-'.date('Y').'</center>';
		
		$this->load->library('email');

		$this->email->from($this->config->item('smtp_user'),'生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->setting->get("systemName").'] 重置密码邮箱认证');
		$this->email->message($message);

		$mailSend=$this->email->send();
		
		if($mailSend==TRUE){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"failed to Send Email");
		}
	}
	
	
	public function forgetPasswordVerifyCode()
	{
		$email=inputPost('email',0,1);
		$verifyCode=inputPost('verifyCode',0,1);
		$info=$this->session->userdata($this->sessPrefix.'forgetPwd_mailInfo');
		
		if($email!=$info['email']){
			returnAjaxData(1,'invalid Mail');
		}elseif($verifyCode!=$info['verifyCode']){
			returnAjaxData(2,'invalid Code');
		}elseif($info['expireTime']<=time()){
			returnAjaxData(3,'expired');
		}else{
			$this->session->unset_userdata($this->sessPrefix.'forgetPwd_mailInfo');
			returnAjaxData(200,"success",base_url('user/resetPassword'));
		}
	}

	
	public function resetPassword()
	{
		if($this->session->userdata($this->sessPrefix.'forgetPwd_userId')<1 || $this->session->userdata($this->sessPrefix.'forgetPwd_mailInfo')!=NULL){
			die('<script>alert("非法访问！");window.location.href="'.base_url('user/forgetPassword').'";</script>');
		}else{
			$this->load->view('user/resetPwd');
		}		
	}
	
	
	public function toResetPassword()
	{
		$id=$this->session->userdata($this->sessPrefix.'forgetPwd_userId');
		$userName=inputPost('userName',0,1);
		$pwd=inputPost('pwd',0,1);
		
		$salt=random_string('alnum');
		$hashPwd=sha1(md5($pwd).$salt);
		
		$nowTime=date('Y-m-d H:i:s');
		$sql='UPDATE user SET password=?,salt=?,update_time=? WHERE id=?';
		$query=$this->db->query($sql,[$hashPwd,$salt,$nowTime,$id]);
		
		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(1,'failed to Reset Password');
		}
	}
}
