<?php
/**
 * @name 生蚝科技统一身份认证平台-C-第三方登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-25
 * @version 2019-01-26
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class ThirdLogin extends CI_Controller {

	public $sessPrefix;
	const WXMP_APP_ID="";
	const WXMP_APP_SECRET="";

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function wxMP_getQrCode($appId="",$width=0)
	{
		$ticket=md5(mt_rand(1234,9876).time().mt_rand(1234,9876));

		if($appId==""){
			$appId=$this->setting->get("SSOUCAppId");
		}

		if($this->session->userdata($this->sessPrefix.'wxMP_accessToken')!=null && time()<=$this->session->userdata($this->sessPrefix.'wxMP_accessTokenExpireTime')){
			$accessToken=$this->session->userdata($this->sessPrefix.'wxMP_accessToken');
		}else{
			$getAccessTokenUrl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::WXMP_APP_ID.'&secret='.self::WXMP_APP_SECRET;
			$getAccessToken=curl($getAccessTokenUrl);
			$accessToken=json_decode($getAccessToken,true);
			$accessToken=$accessToken['access_token'];
			$this->session->set_userdata($this->sessPrefix.'wxMP_accessToken',$accessToken);
			$this->session->set_userdata($this->sessPrefix.'wxMP_accessTokenExpireTime',time()+7200);
		}

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

			$query=$this->db->insert('qr_login_token',['ticket'=>$ticket,'app_id'=>$appId,'ip'=>getIP(),'expire_time'=>time()+5]);

			if($query==true){
				$this->session->set_userdata($this->sessPrefix.'wxMP_qrTicket',$ticket);
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


	public function wxMP_getOpenId()
	{
		$code=isset($_GET['code'])&&strlen($_GET['code'])>=1?$_GET['code']:$this->ajax->returnData(0,'lack Param');

		$url='https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code&appid='.self::WXMP_APP_ID.'&secret='.self::WXMP_APP_SECRET.'&js_code='.$code;
		$rtn=curl($url);

		$rtn=json_decode($rtn,true);

		if(isset($rtn['openid'])) $this->ajax->returnData(200,'success',['openId'=>$rtn['openid']]);
		else $this->ajax->returnData(500,'failed to Request Wechat Server');
	}


	public function wxMP_checkStatus(){
		$ticket=$this->session->userdata($this->sessPrefix.'wxMP_qrTicket')!=null?$this->session->userdata($this->sessPrefix.'wxMP_qrTicket'):$this->ajax->returnData(403,'no Ticket');

		$this->db->where('ticket', $ticket);
		$qrInfoQuery=$this->db->get('qr_login_token');
		$qrInfo=$qrInfoQuery->result_array();

		if(count($qrInfo)!=1){
			$this->ajax->returnData(404,'ticket Not Found');
		}else{
			$qrInfo=$qrInfo[0];

			if(time()>$qrInfo['expire_time']){
				$this->db->where('ticket', $ticket);
				$this->db->update('qr_login_token',['status'=>0]);
				$this->ajax->returnData(200,'success',['status'=>0]);
			}else{
				$this->ajax->returnData(200,'success',['status'=>$qrInfo['status']]);
			}
		}
	}


	public function wxMP_handler()
	{
		$mod=isset($_POST['mod'])&&$_POST['mod']!=""?$_POST['mod']:$this->ajax->returnData(0,'lack Param');
		$ticket=isset($_POST['ticket'])&&strlen($_POST['ticket'])==32?$_POST['ticket']:$this->ajax->returnData(0,'lack Param');

		$this->db->where('ticket', $ticket);
		$this->db->where('status!=', 0);
		//if($mod=='scan') $this->db->where('status', 1);
		$qrInfoQuery=$this->db->get('qr_login_token');
		$qrInfo=$qrInfoQuery->result_array();

		if(count($qrInfo)!=1){
			$this->ajax->returnData(404,'ticket Not Found');
		}else{
			$qrInfo=$qrInfo[0];

			if(time()>$qrInfo['expire_time']){
				$this->ajax->returnData(1,'ticket Expired');
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
					$this->ajax->returnData(200,'success',['appName'=>$appName]);
				}else{
					$this->ajax->returnData(500,'database Error');
				}

				break;
			case 'login':
				$openId=isset($_POST['openId'])&&strlen($_POST['openId'])>=1?$_POST['openId']:$this->ajax->returnData(0,'lack Param');
				$userInfo=$this->user->getInfo(['wx_open_id'=>$openId]);

				if(count($userInfo)>=1){
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
					$this->ajax->returnData(4031,'user Not Found');
				}

				$appPermission=explode(",",$userInfo['app_permission']);
				$appInfo=$this->app->get($appId);
				
				if($appId!="" && $appInfo==array()){
					// 没有此应用
					$this->db->where('ticket',$ticket);
					$this->db->update('qr_login_token',['status'=>4]);
					$this->ajax->returnData(4032,"permission Denied");
				}elseif($appId!="" && in_array($appInfo['id'],$appPermission)!=true){
					// 没有权限访问
					$this->db->where('ticket',$ticket);
					$this->db->update('qr_login_token',['status'=>4]);
					$this->ajax->returnData(4032,"permission Denied");
				}else{
					// 有权限，跳转回应用登录页
					$tokenQuery=$this->user->addLoginToken($token,$userInfo['id']);
					if($tokenQuery===true){
						$this->db->where('ticket',$ticket);
						$this->db->update('qr_login_token',['status'=>3]);
						$this->ajax->returnData(200,"success");
					}else{
						$this->ajax->returnData(500,"database Error");
					}
				}
			case 'cancel':
				$this->db->where('ticket',$ticket);
				$this->db->update('qr_login_token',['status'=>-1]);

				if($this->db->affected_rows()==1){
					$this->ajax->returnData(200,'success');
				}else{
					$this->ajax->returnData(500,'database Error');
				}

				break;
			default:
				$this->ajax->returnData(2,'invaild Mod');
				break;
		}
	}
}
