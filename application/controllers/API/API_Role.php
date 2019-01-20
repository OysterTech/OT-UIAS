<?php
/**
 * @name 生蚝科技统一身份认证平台-C-API-角色
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-20
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_Role extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}

	public function getRoleInfo($roleId=0)
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) $this->ajax->returnData(403,"no Permission");

		if($roleId>=1) $this->db->where('id',$roleId);

		$query=$this->db->get('role');

		$this->ajax->returnData(200,"success",['list'=>$query->result_array()]);
	}


	public function getUserMenu()
	{
		$roleId=$this->session->userdata($this->sessPrefix."role_id");
		if($roleId>=1) $this->ajax->returnData(200,"success",['treeData'=>$this->getAllMenuByRole($roleId)]);
		else $this->ajax->returnData(403,"failed To Auth");
	}


	private function getFatherMenuByRole($roleId)
	{
		$sql='SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id="0"';
		$query=$this->db->query($sql,[$roleId]);
		$list=$query->result_array();

		return $list;
	}


	private function getChildMenuByRole($roleId,$fatherId)
	{
		$sql="SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=?";
		$query=$this->db->query($sql,[$roleId,$fatherId]);
		$list=$query->result_array();

		return $list;
	}


	private function getAllMenuByRole($roleId)
	{
		$allMenu=array();

		$fatherMenu_list=$this->getFatherMenuByRole($roleId);
		$allMenu=$fatherMenu_list;
		$allMenu_total=count($allMenu);

		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherId=$allMenu[$i]['id'];
			$child_list=$this->getChildMenuByRole($roleId,$fatherId);

			if($child_list==null){
			// 没有二级菜单
				$allMenu[$i]['hasChild']="0";
				$allMenu[$i]['child']=array();
			}else{
			// 有二级菜单
				$allMenu[$i]['hasChild']="1";
				$allMenu[$i]['child']=$child_list;

				// 二级菜单的数量
				$child_list_total=count($child_list);

				// 搜寻三级菜单
				for($j=0;$j<$child_list_total;$j++){
					$father2Id=$child_list[$j]['id'];
					$child2_list=$this->getChildMenuByRole($roleId,$father2Id);

					if($child2_list==null){
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']="0";
						$allMenu[$i]['child'][$j]['child']=array();
					}else{
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']="1";
						$allMenu[$i]['child'][$j]['child']=$child2_list;
					}
				}
			}
		}

		return $allMenu;
	}


	public function getRoleMenuForZtree($roleId=0)
	{
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
			$rtn[$key]['name']=urlencode($info['name']);
			if(in_array($info['id'],$allPermission)) $rtn[$key]['checked']=true;
		}

		die(urldecode(json_encode($rtn)));
	}
}
