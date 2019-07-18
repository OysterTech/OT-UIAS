<?php
/**
 * @name 生蚝科技RBAC开发框架-C-API-角色
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-07-17
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_Role extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}

	
	/**
	 * 获取角色信息
	 * @return array 角色信息
	 */
	public function getRoleInfo()
	{
		$roleId=inputGet('id',1,1);
		returnAjaxData(200,'success',['list'=>$this->rbac->getRole($roleId)]);
	}


	/**
	 * 获取当前用户的权限菜单
	 * @return array 权限菜单数组
	 */
	public function getUserMenu()
	{
		$roleId=$this->session->userdata($this->sessPrefix.'roleId');
		
		if(strlen($roleId)==6) returnAjaxData(200,'success',['treeData'=>$this->rbac->getAllMenuByRole($roleId)]);
		else returnAjaxData(403,'failed To Auth');
	}


	/**
	 * 获取适配zTree的角色菜单
	 * @return string zTree格式的菜单JSON字符串
	 */
	public function getRoleMenuForZtree()
	{
		$roleId=inputGet('roleId',0);
		$rtn=array();
		$allPermission=array();

		$menuQuery=$this->db->get('menu');
		$menuList=$menuQuery->result_array();

		$permissionQuery=$this->db->get_where('role_permission',['role_id'=>$roleId]);
		$permissionList=$permissionQuery->result_array();

		foreach($permissionList as $permissionInfo){
			array_push($allPermission,$permissionInfo['menu_id']);
		}

		foreach($menuList as $key=>$info){
			$rtn[$key]['id']=(int)$info['id'];
			$rtn[$key]['pId']=(int)$info['father_id'];
			$rtn[$key]['menuIcon']=$info['icon'];
			$rtn[$key]['menuName']=$info['name'];
			$rtn[$key]['uri']=$info['uri'];
			$rtn[$key]['type']=$info['type'];
			$rtn[$key]['name']=$info['type']==1?urlencode($info['name']):($info['type']==2?'(按钮)'.urlencode($info['name']):'(接口)'.urlencode($info['name']));
			if(in_array($info['id'],$allPermission)) $rtn[$key]['checked']=true;
		}

		die(urldecode(json_encode($rtn)));
	}
}
