<?php
/**
 * @name 生蚝科技统一身份认证平台-C-角色管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-20
 * @version 2019-02-16
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Role extends CI_Controller {

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
		$this->safe->checkAuth();
		$this->load->view('admin/role/list');
	}


	public function toSetRolePermission()
	{
		$roleId=inputPost('roleId',0,1);
		$permissionId=inputPost('permissionId',1,1);
		$permissionId=explode(',',$permissionId);
		$data=array();
		
		$query=$this->db->delete('role_permission',['role_id'=>$roleId]);
		if($query===false){
			returnAjaxData(1,"failed to Delete Role Permission");
		}
		
		foreach($permissionId as $value){
			array_push($data,array('menu_id'=>$value,'role_id'=>$roleId));
		}
		
		$insert=$this->db->insert_batch('role_permission',$data);
		
		if($insert===count($permissionId)){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"database error");
		}
	}


	public function delete()
	{
		$roleId=isset($_POST['roleId'])?$_POST['roleId']:returnAjaxData(0,"lack Param");

		$query=$this->db->delete('role',array('id'=>$roleId));
		if($query==true) returnAjaxData(200,"success");
		else returnAjaxData(500,"database Error");
	}
}
