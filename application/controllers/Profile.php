<?php
/**
 * @name 生蚝科技RBAC开发框架-C-资料
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-03-22
 * @version 2019-03-22
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
		$userId=$this->session->userdata($this->sessPrefix.'userId');
		$oldPwd=inputPost('oldPwd',0,1);
		$newPwd=inputPost('newPwd',0,1);
		
		$checkPwd=$this->user->validateUser($oldPwd,$userId);

		// 判断密码有效性
		if($checkPwd!==200){
			returnAjaxData($checkPwd,"failed To Auth");
		}else{
			$salt=getRanSTR(8);
			$hash=sha1(md5($newPwd).$salt);
			$query=$this->db->update('user',array('password'=>$hash,'salt'=>$salt),array('id'=>$userId));

			if($query==true){
				returnAjaxData(200,"success");
			}else{
				returnAjaxData(500,"database error");
			}
		}
	}
}
