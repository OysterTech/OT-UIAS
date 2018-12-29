<?php
/**
 * @name 生蚝科技统一身份认证平台-API-获取用户信息
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-03
 * @version 2018-12-28
 */

require_once '../include/public.func.php';

$token=isset($_POST['token'])&&$_POST['token']!=""?$_POST['token']:die(returnAjaxData(0,"lack Param"));
$query=PDOQuery($dbcon,"SELECT user_id FROM login_token WHERE token=?",[$token],[PDO::PARAM_STR]);

if($query[1]==1){
	$userId=$query[0][0]['user_id'];
	$query=PDOQuery($dbcon,"SELECT union_id AS unionId,nick_name AS nickName,phone,email FROM user WHERE id=?",[$userId],[PDO::PARAM_INT]);
	
	if($query[1]!=1){
		die(returnAjaxData(1,"no User"));
	}else{
		die(returnAjaxData(200,"success",['userInfo'=>$query[0][0]]));
	}
}else{
	die(returnAjaxData(403,"failed To Auth",[$token,getIP()]));
}
?>
