<?php
/**
 * @name 生蚝科技统一身份认证平台-C-小程序API
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-25
 * @version 2019-02-14
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_WXMP extends CI_Controller {

	public $sessPrefix;
	const APP_ID="";
	const APP_SECRET="";


	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function getAccessToken($method='')
	{
		if($method=='api'){
			$getAccessTokenUrl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::APP_ID.'&secret='.self::APP_SECRET;
			$getAccessToken=curl($getAccessTokenUrl);
			$accessToken=json_decode($getAccessToken,true);
			echo $accessToken=$accessToken['access_token'];
			return;
		}elseif($method=='server'){
			if($this->session->userdata($this->sessPrefix.'wxmp_accessToken')!=null && time()<=$this->session->userdata($this->sessPrefix.'wxmp_accessTokenExpireTime')){
				$accessToken=$this->session->userdata($this->sessPrefix.'wxmp_accessToken');
			}else{
				$getAccessTokenUrl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::APP_ID.'&secret='.self::APP_SECRET;
				$getAccessToken=curl($getAccessTokenUrl);
				$accessToken=json_decode($getAccessToken,true);
				$accessToken=$accessToken['access_token'];
				$this->session->set_userdata($this->sessPrefix.'wxmp_accessToken',$accessToken);
				$this->session->set_userdata($this->sessPrefix.'wxmp_accessTokenExpireTime',time()+7200);
			}

			return $accessToken;
		}else{
			show_404();
			return;
		}
	}


	public function getQrCode($sessionId='',$appId="",$width=0)
	{
		session_id($sessionId);
		$ticket=md5(mt_rand(1234,9876).time().mt_rand(1234,9876));

		if($appId==""){
			$appId=$this->setting->get("SSOUCAppId");
		}

		$accessToken=self::getAccessToken('server');

		$getCodeUrl='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$accessToken;
		$getCodeData=array(
			'scene'=>$ticket,
			'page'=>'pages/SSOScanLogin/auth'
		);
		if($width!=0) $getCodeData['width']=$width;
		$getCode=curl($getCodeUrl,'post',$getCodeData,'json');

		if(is_array(json_decode($getCode,true))!=1){
			$this->db->where('expire_time<',time());
			$this->db->delete('qr_login_token');

			$query=$this->db->insert('qr_login_token',['ticket'=>$ticket,'app_id'=>$appId,'session_id'=>$sessionId,'ip'=>getIP(),'expire_time'=>time()+120]);

			if($query==true){
				$this->session->set_userdata($this->sessPrefix.'wxmp_qrTicket',$ticket);
				header('content-type:image/jpeg');
				echo $getCode;
			}else{
				echo "新增记录失败！";
			}
		}else{
			$getCode=json_decode($getCode,true);
			echo "生成小程序码失败";
		}

	}


	public function getOpenId()
	{
		$code=inputGet('code',0,1);

		$url='https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code&appid='.self::APP_ID.'&secret='.self::APP_SECRET.'&js_code='.$code;
		$rtn=curl($url);

		$rtn=json_decode($rtn,true);

		if(isset($rtn['openid'])) returnAjaxData(200,'success',['openId'=>$rtn['openid']]);
		else returnAjaxData(500,'failed to Request Wechat Server');
	}


	public function getUserInfo()
	{
		$openId=inputGet('openId',0,1);

		$infoQuery=$this->db->query('SELECT a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,a.extra_param AS extraParam,a.create_time AS createTime,a.last_login AS lastLogin FROM user a,third_user b WHERE a.id=b.user_id AND b.third_id=?',[$openId]);
		$info=$infoQuery->result_array();

		if(count($info)==1){
			returnAjaxData(200,'success',['userInfo'=>$info[0]]);
		}else{
			returnAjaxData(403,'no User');
		}
	}
	
	
	public function updateUserInfo()
	{
		$unionId=inputPost('unionId',0,1);
		$postData=inputPost('postData',0,1);
		$postData=json_decode($postData,true);
		$updateData=array();

		if(isset($postData['userName'])){
			$this->db->where('union_id!=', $unionId);
			$this->db->where('user_name', $postData['userName']);
			$query=$this->db->get('user');
			if($query->num_rows()!=0){
				returnAjaxData(1,'have UserName');
			}
		}
		if(isset($postData['nickName'])){
			$this->db->where('union_id!=', $unionId);
			$this->db->where('nick_name', $postData['nickName']);
			$query=$this->db->get('user');
			if($query->num_rows()!=0){
				returnAjaxData(2,'have NickName');
			}
		}
		if(isset($postData['phone'])){
			$this->db->where('union_id!=', $unionId);
			$this->db->where('phone', $postData['phone']);
			$query=$this->db->get('user');
			if($query->num_rows()!=0){
				returnAjaxData(3,'have Phone');
			}
		}

		$userInfo=$this->user->getInfo(['union_id'=>$unionId]);

		if(isset($postData['oldPassword']) && isset($postData['newPassword'])){
			$checkPassword=$this->safe->checkPassword($postData['oldPassword'],$userInfo['password'],$userInfo['salt']);

			if($checkPassword===true){
				$salt=getRanSTR(8);
				$hash=sha1(md5($postData['newPassword']).$salt);

				$updateData['password']=$hash;
				$updateData['salt']=$salt;
			}else{
				returnAjaxData(403,'invaild Old Password');
			}
		}

		if(isset($postData['userName'])) $updateData['user_name']=$postData['userName'];
		if(isset($postData['nickName'])) $updateData['nick_name']=$postData['nickName'];
		if(isset($postData['phone'])) $updateData['phone']=$postData['phone'];
		if(isset($postData['email'])) $updateData['email']=$postData['email'];

		$this->db->where('union_id', $unionId);
		$this->db->update('user', $updateData);

		if($this->db->affected_rows()==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'database Error');
	}


	public function checkStatus(){
		$ticket=$this->session->userdata($this->sessPrefix.'wxmp_qrTicket')!=null?$this->session->userdata($this->sessPrefix.'wxmp_qrTicket'):returnAjaxData(403,'no Ticket');

		$this->db->where('ticket', $ticket);
		$qrInfoQuery=$this->db->get('qr_login_token');
		$qrInfo=$qrInfoQuery->result_array();

		if(count($qrInfo)!=1){
			returnAjaxData(404,'ticket Not Found');
		}else{
			$qrInfo=$qrInfo[0];

			if(time()>$qrInfo['expire_time']){
				$this->db->where('ticket', $ticket);
				$this->db->update('qr_login_token',['status'=>0]);
				returnAjaxData(200,'success',['status'=>0]);
			}else{
				returnAjaxData(200,'success',['status'=>$qrInfo['status']]);
			}
		}
	}


	public function handler()
	{
		$mod=inputPost('mod',0,1);
		$ticket=inputPost('ticket',0,1);

		$this->db->where('ticket', $ticket);
		$qrInfoQuery=$this->db->get('qr_login_token');
		$qrInfo=$qrInfoQuery->result_array();

		if(count($qrInfo)!=1){
			returnAjaxData(404,'ticket Not Found');
		}else{
			$qrInfo=$qrInfo[0];

			// 切换回浏览器的session
			session_destroy();
			session_id($qrInfo['session_id']);
			session_start();

			if(time()>$qrInfo['expire_time']){
				returnAjaxData(1,'ticket Expired');
			}else{
				$appId=$qrInfo['app_id'];
				$appInfo=$this->app->get($appId);
				$appName=$appInfo['name'];
			}
		}

		switch($mod){
			case 'scan':
				$this->db->where('ticket',$ticket);
				$this->db->update('qr_login_token',['status'=>2]);

				if($this->db->affected_rows()===1){
					returnAjaxData(200,'success',['appName'=>$appName]);
				}else{
					returnAjaxData(500,'database Error');
				}

				break;
			case 'login':
				$openId=inputPost('openId',0,1);

				$this->db->where('third_id', $openId);
				$userInfo=$this->db->get('third_user');
				$userInfo=$userInfo->result_array();

				if(count($userInfo)>=1){
					$userId=$userInfo[0]['user_id'];
					$userInfo=$this->user->getInfo(['id'=>$userId]);
					$token=sha1(md5($appId).time());
					$sessionData=array(
						$this->sessPrefix.'isLogin'=>1,
						$this->sessPrefix.'token'=>$token,
						$this->sessPrefix.'userName'=>$userInfo['user_name'],
						$this->sessPrefix.'nickName'=>$userInfo['nick_name'],
						$this->sessPrefix.'role_id'=>$userInfo['role_id'],
						$this->sessPrefix.'user_id'=>$userInfo['id'],
						$this->sessPrefix.'unionId'=>$userInfo['union_id']
					);
					$this->session->set_userdata($sessionData);
				}else{
					returnAjaxData(4031,'user Not Found');
				}

				$appPermission=explode(",",$userInfo['app_permission']);
				$appInfo=$this->app->get($appId);
				
				if($appId!="" && $appInfo==array()){
					// 没有此应用
					$this->db->where('ticket',$ticket);
					$this->db->update('qr_login_token',['status'=>4]);
					returnAjaxData(4032,"permission Denied");
				}elseif($appId!="" && in_array($appInfo['id'],$appPermission)!=true){
					// 没有权限访问
					$this->db->where('ticket',$ticket);
					$this->db->update('qr_login_token',['status'=>4]);
					returnAjaxData(4032,"permission Denied");
				}else{
					// 有权限，跳转回应用登录页
					$tokenQuery=$this->user->addLoginToken($token,$userInfo['id']);
					if($tokenQuery===true){
						$this->db->where('user_id',$userId);
						$this->db->where('method','wxmp');
						$this->db->update('third_user',['last_login'=>date('Y-m-d H:i:s')]);
						
						$this->db->where('ticket',$ticket);
						$this->db->update('qr_login_token',['status'=>3,'session_id'=>session_id()]);
						returnAjaxData(200,"success");
					}else{
						returnAjaxData(500,"database Error");
					}
				}
			case 'cancel':
				$this->db->where('ticket',$ticket);
				$this->db->update('qr_login_token',['status'=>-1]);

				if($this->db->affected_rows()==1){
					returnAjaxData(200,'success');
				}else{
					returnAjaxData(500,'database Error');
				}

				break;
			default:
				returnAjaxData(2,'invaild Mod');
				break;
		}
	}


	public function bindUser()
	{
		$openId=inputPost('openId',0,1);
		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);

		$userInfo=$this->user->getInfoByUserName($userName);

		if($userInfo==array()){
			returnAjaxData(404,'no User');
		}else{
			$checkPassword=$this->safe->checkPassword($password,$userInfo['password'],$userInfo['salt']);

			if($checkPassword===true){
				$userId=$userInfo['id'];
				$extraParam=$userInfo['extra_param'];
				$extraParam=json_decode($extraParam,true);

				$ret = array(
					'unionId'=>$userInfo['union_id'],
					'userName'=>$userInfo['user_name'],
					'nickName'=>$userInfo['nick_name'],
					'phone'=>$userInfo['phone'],
					'email'=>$userInfo['email'],
					'extraParam'=>$extraParam,
					'createTime'=>$userInfo['create_time'],
					'lastLogin'=>$userInfo['last_login'],
				);

				$this->db->where('user_id', $userId);
				$this->db->where('method', 'wxmp');
				$query=$this->db->get('third_user');

				if($query->num_rows()>=1){
					$this->db->where('user_id', $userId);
					$this->db->where('method', 'wxmp');
					$this->db->update('third_user',['third_id'=>$openId]);
					returnAjaxData(200,'success',['userInfo'=>$ret]);
				}else{
					$this->db->insert('third_user',['user_id'=>$userId,'method'=>'wxmp','third_id'=>$openId]);
					returnAjaxData(200,'success',['userInfo'=>$ret]);
				}
			}else{
				returnAjaxData(403,'invaild Password');
			}
		}
	}


	public function cancelBindUser()
	{
		$openId=inputPost('openId',0,1);

		$this->db->where('third_id', $openId);
		$query=$this->db->delete('third_user');

		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(500,'database Error');
		}
	}


	public function toSendTemplate()
	{
		$accessToken=inputPost('accessToken',0,1);
		$templateId=inputPost('templateId',0,1);
		$openId=inputPost('openId',0,1);
		$formId=inputPost('formId',0,1);
		$data=inputPost('data',0,1);
		$page=inputPost('page',1,1);
		$emphasisKeyword=inputPost('emphasisKeyword',1,1);

		$data=json_decode($data,TRUE);
		$postData=array('touser'=>$openId,'template_id'=>$templateId,'page'=>$page,'form_id'=>$formId,'data'=>$data);
		$postData=json_encode($postData,JSON_UNESCAPED_UNICODE);

		$url='https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$accessToken;
		$query=curl($url,'post',$postData);
		$query=json_decode($query,true);
		
		if($query['errcode']==0) returnAjaxData(200,'success');
		else returnAjaxData(500,'system Error',['wxServerError'=>$query]);
	}
}
