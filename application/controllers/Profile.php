<?php
/**
 * @name 生蚝科技统一身份认证平台-C-资料
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-02-16
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function index()
	{
		$this->load->view('profile/index');
	}


	public function toChangePassword()
	{
		$userName=$this->session->userdata($this->sessPrefix.'userName');
		$oldPwd=inputPost('oldPwd',0,1);
		$newPwd=inputPost('newPwd',0,1);
		$userInfo=$this->user->getInfoByUserName($userName);
		
		if($userInfo==array()){
			returnAjaxData(403,"failed To Auth");
		}else{
			$checkPwd=$this->safe->checkPassword($oldPwd,$userInfo['password'],$userInfo['salt']);

			// 判断密码有效性
			if($checkPwd!==true){
				returnAjaxData(403,"failed To Auth");
			}else{
				$salt=getRanSTR(8);
				$hash=sha1(md5($newPwd).$salt);
				$query=$this->db->update('user',array('password'=>$hash,'salt'=>$salt),array('user_name'=>$userName));

				if($query==true){
					returnAjaxData(200,"success");
				}else{
					returnAjaxData(500,"database error");
				}
			}
		}
	}
}
