<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-菜单
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-17
* @version 2019-07-18
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RbacAdmin_menu extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkAuth();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function toList()
	{
		$this->safe->checkAuth();
		$this->load->view('admin/menu/list',['list'=>$this->rbac->getAllMenu()]);
	}


	public function toOperate()
	{
		$operateType=inputPost('operateType',0,1);
		$id=inputPost('id',0,1);
		$menuType=inputPost('menuType',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',1,1);
		
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr($uri,13));
			$uri='show/jumpout/'.$jumpToURL;
		}
		
		if($operateType==2){
			$this->db->query('UPDATE menu SET type=?,name=?,icon=?,uri=? WHERE id=?',[$menuType,$name,$icon,$uri,$id]);
		}else{
			$this->db->query('INSERT INTO menu(father_id,type,name,icon,uri) VALUES (?,?,?,?,?)',[$id,$menuType,$name,$icon,$uri]);
		}

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"Failed to operate");
		}
	}


	public function toDelete()
	{
		$id=inputPost('id',0,1);
		$this->db->delete('menu',['id'=>$id]);
		
		if($this->db->affected_rows()==1){
			$this->db->delete('role_permission',['menu_id'=>$id]);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"Failed to delete");
		}
	}
}
