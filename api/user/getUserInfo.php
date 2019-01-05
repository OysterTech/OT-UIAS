<?php
/**
 * @name 生蚝科技统一身份认证平台-API-获取用户信息
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-03
 * @version 2019-01-05
 */

require_once '../../include/public.func.php';

$method=isset($_POST['method'])&&$_POST['method']!=""?$_POST['method']:die(returnAjaxData(0,"lack Param"));

if($method=="unionId"){
	$unionId=isset($_POST['unionId'])&&$_POST['unionId']!=""?$_POST['unionId']:die(returnAjaxData(0,"lack Param"));
	$query=PDOQuery($dbcon,"SELECT a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.union_id=? AND a.role_id=b.id",[$unionId],[PDO::PARAM_STR]);
	
	if($query[1]!=1){
		die(returnAjaxData(1,"no User",[$query]));
	}else{
		die(returnAjaxData(200,"success",['userInfo'=>$query[0][0]]));
	}
}else if($method=="api"){
	$token=isset($_POST['token'])&&$_POST['token']!=""?$_POST['token']:die(returnAjaxData(0,"lack Param"));
	$query=PDOQuery($dbcon,"SELECT user_id FROM login_token WHERE token=?",[$token],[PDO::PARAM_STR]);

	if($query[1]==1){
		$userId=$query[0][0]['user_id'];
		$query=PDOQuery($dbcon,"SELECT a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.id=? AND a.role_id=b.id",[$userId],[PDO::PARAM_INT]);

		if($query[1]!=1){
			die(returnAjaxData(1,"no User"));
		}else{
			die(returnAjaxData(200,"success",['userInfo'=>$query[0][0]]));
		}
	}else{
		die(returnAjaxData(403,"failed To Auth"));
	}
}else{
	die(returnAjaxData(2,"Invaild Method"));
}

?>
