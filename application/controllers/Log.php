<?php
/**
 * @name 生蚝科技RBAC开发框架-C-Log日志
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-26
 * @version 2019-06-12
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();

		$this->safe->checkPermission();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function toList()
	{
		$query=$this->db->get('log');
		$list=$query->result_array();
		
		$this->load->view('admin/sys/log/list',['list'=>$list]);
	}
	
	
	public function toTruncate()
	{
		$pwd=inputPost('pwd',0,1);
		$userStatus=$this->user->validateUser($pwd,$this->nowUserId);
		
		if($userStatus!="200"){
			returnAjaxData(403,"Invaild password");
		}
		
		$this->db->truncate('log');
		
		$logInsert=$this->Log_model->create("日志","清空日志");
		
		if($logInsert==true){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"Failed to truncate");
		}
	}
}
