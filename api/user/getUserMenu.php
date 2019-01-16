<?php
/**
 * @name 生蚝科技统一身份认证平台-API-获取用户权限菜单
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-05
 * @version 2019-01-05
 */

require_once '../../include/public.func.php';

$role=getSess("role");
if($role>=1) die(returnAjaxData(200,"success",['treeData'=>getAllMenuByRole($dbcon,getSess("role"))]));
else die(returnAjaxData(403,"failed To Auth"));


function getFatherMenuByRole($dbcon,$roleID)
{
	$sql='SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id="0"';
	$query=PDOQuery($dbcon,$sql,[$roleID],[PDO::PARAM_INT]);
	$list=$query[0];

	return $list;
}


function getChildMenuByRole($dbcon,$roleID,$fatherID)
{
	$sql="SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=?";
	$query=PDOQuery($dbcon,$sql,[$roleID,$fatherID],[PDO::PARAM_INT,PDO::PARAM_INT]);
	$list=$query[0];

	return $list;
}


function getAllMenuByRole($dbcon,$roleID)
{
	$allMenu=array();

	$fatherMenu_list=getFatherMenuByRole($dbcon,$roleID);
	$allMenu=$fatherMenu_list;
	$allMenu_total=count($allMenu);

	// 搜寻二级菜单
	for($i=0;$i<$allMenu_total;$i++){
		$fatherID=$allMenu[$i]['id'];
		$child_list=getChildMenuByRole($dbcon,$roleID,$fatherID);

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
				$father2ID=$child_list[$j]['id'];
				$child2_list=getChildMenuByRole($dbcon,$roleID,$father2ID);

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
