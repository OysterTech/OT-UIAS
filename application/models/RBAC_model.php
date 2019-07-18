<?php
/**
 * @name 生蚝科技RBAC开发框架-M-RBAC
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-06
 * @version 2019-07-17
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 根据URI获取菜单Id
	 * @param String URI
	 * @return INT 菜单Id
	 */
	public function getMenuId($uri)
	{
		$sql="SELECT id FROM menu WHERE uri=?";
		$query=$this->db->query($sql,[$uri]);
		
		if($query->num_rows()!=1){
			return null;
		}
		
		$list=$query->result_array();
		$info=$list[0];
		
		return $info['id'];
	}
	
	
	/**
	 * 根据角色获取所有权限的菜单Id
	 * @param String 角色Id
	 * @return Array 菜单Id
	 */
	public function getAllPermissionByRole($roleId)
	{
		$rtn=array();
		
		$sql="SELECT menu_id FROM role_permission WHERE role_id=?";
		$query=$this->db->query($sql,[$roleId]);
		$list=$query->result_array();
		
		foreach($list as $info){
			array_push($rtn,$info['menu_id']);
		}
		
		return $rtn;
	}


	/**
	 * 获取角色信息
	 * @return Array 角色信息
	 */
	public function getRole($id='')
	{
		if($id!='') $this->db->where('id', $id);
		$query=$this->db->get('role');
		$list=$query->result_array();
		return $list;
	}
	
	
	/**
	 * 根据角色获取所有父菜单
	 * @param String 角色Id
	 * @return Array 父菜单信息
	 */
	public function getFatherMenuByRole($roleId)
	{
		$sql='SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=0';
		$query=$this->db->query($sql,[$roleId]);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	/**
	 * 根据角色和父菜单Id获取子菜单信息
	 * @param String 角色Id
	 * @param String 父菜单Id
	 * @return Array 子菜单信息
	 */
	public function getChildMenuByRole($roleId,$fatherId)
	{
		$sql="SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=?";
		$query=$this->db->query($sql,[$roleId,$fatherId]);
		$list=$query->result_array();
		
		return $list;
	}
	
	
	/**
	 * 根据角色获取所有菜单详细信息
	 * @param String 角色Id
	 * @return Array 菜单详细信息
	 */
	public function getAllMenuByRole($roleId)
	{
		$allMenu=array();
		
		$allMenu=self::getFatherMenuByRole($roleId);
		$allMenu_total=count($allMenu);
		
		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherId=$allMenu[$i]['id'];
			$child_list=self::getChildMenuByRole($roleId,$fatherId);
			
			if($child_list==null){
				// 没有二级菜单
				$allMenu[$i]['hasChild']='0';
				$allMenu[$i]['child']=array();
			}else{
				// 有二级菜单
				$allMenu[$i]['hasChild']='1';
				$allMenu[$i]['child']=$child_list;

				// 二级菜单的数量
				$child_list_total=count($child_list);

				// 搜寻三级菜单
				for($j=0;$j<$child_list_total;$j++){
					$father2Id=$child_list[$j]['id'];
					$child2_list=self::getChildMenuByRole($roleId,$father2Id);

					if($child2_list==null){
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']='0';
						$allMenu[$i]['child'][$j]['child']=array();
					}else{
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']='1';
						$allMenu[$i]['child'][$j]['child']=$child2_list;
					}
				}
			}
		}

		return $allMenu;
	}
	

	/**
	 * 获取所有父菜单
	 * @return Array 父菜单信息
	 */
	public function getFatherMenu()
	{
		$query=$this->db->get_where('menu',['father_id'=>0]);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	/**
	 * 根据父菜单Id获取子菜单
	 * @param String 父菜单Id
	 * @return Array 子菜单信息
	 */
	public function getChildMenu($fatherId)
	{
		$query=$this->db->get_where('menu',['father_id'=>$fatherId]);
		
		return $query->result_array();
	}
	
	
	/**
	 * 获取所有菜单
	 * @return Array 菜单详细信息
	 */
	public function getAllMenu()
	{
		$allMenu=array();
		
		$allMenu=self::getFatherMenu();
		$allMenu_total=count($allMenu);
		
		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherId=$allMenu[$i]['id'];
			$child_list=self::getChildMenu($fatherId);
			
			if($child_list==null){
				// 没有二级菜单
				$allMenu[$i]['hasChild']='0';
				$allMenu[$i]['child']=array();
			}else{
				// 有二级菜单
				$allMenu[$i]['hasChild']='1';
				$allMenu[$i]['child']=$child_list;

				// 二级菜单的数量
				$child_list_total=count($child_list);

				// 搜寻三级菜单
				for($j=0;$j<$child_list_total;$j++){
					$father2Id=$child_list[$j]['id'];
					$child2_list=self::getChildMenu($father2Id);

					if($child2_list==null){
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']='0';
						$allMenu[$i]['child'][$j]['child']=array();
					}else{
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']='1';
						$allMenu[$i]['child'][$j]['child']=$child2_list;
					}
				}
			}
		}

		return $allMenu;
	}
}
	 