<?php
/**
 * @name 生蚝科技统一身份认证平台-C-应用
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-07-18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function list()
	{
		// 获取用户权限的应用信息
		$this->db->select('app_permission')
		         ->where('union_id',$this->session->userdata($this->sessPrefix.'unionId'));
		$appPermissionQuery=$this->db->get('user');
		$appPermission=$appPermissionQuery->first_row();
		$appPermission=explode(",",$appPermission->app_permission);

		$this->db->select('name,main_page,redirect_url')
		         ->where('status !=',0)
		         ->where('status !=',3)
		         ->where('is_show',1)
		         ->where_in('id',$appPermission);
		$appInfoQuery=$this->db->get('app');

		$this->load->view('app/list',['appInfos'=>$appInfoQuery->result_array()]);
	}
}
