<?php
/**
 * @name 生蚝科技统一身份认证平台-C-应用
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
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
		$this->db->select('app_permission');
		$this->db->where('union_id',$this->session->userdata($this->sessPrefix.'unionId'));
		$appPermissionQuery=$this->db->get('user');
		$appPermission=$appPermissionQuery->result_array();
		$appPermission=explode(",",$appPermission[0]['app_permission']);

		$sql='SELECT name,main_page,return_url FROM app WHERE status!=0 AND status!=3 AND is_show=1 AND (';
		foreach($appPermission as $appId){
			$sql.="id='{$appId}' OR ";
		}
		$sql=substr($sql,0,strlen($sql)-4).")";
		$appInfoQuery=$this->db->query($sql);
		$appInfos=$appInfoQuery->result_array();
		$this->load->view('app/list',['appInfos'=>$appInfos]);
	}
}
