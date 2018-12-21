<?php
/**
 * @name 生蚝科技统一身份认证平台-API-获取用户信息
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-03
 * @version 2018-12-20
 */

require_once '../include/public.func.php';

$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:die(returnAjaxData(0,"lack Param"));
$appId=isset($_GET['appId'])&&$_GET['appId']!=""?$_GET['appId']:die(returnAjaxData(0,"lack Param"));
$returnUrl=isset($_GET['returnUrl'])&&$_GET['returnUrl']!=""?$_GET['returnUrl']:die(returnAjaxData(0,"lack Param"));

if(getSess("appId")==$appId && getSess("returnUrl")==$returnUrl && getSess("token")==$token){
	$userName=getSess("userName");
	$query=PDOQuery($dbcon,"SELECT union_id AS unionId,nick_name AS nickName,phone,email FROM user WHERE user_name=?",[$userName],[PDO::PARAM_STR]);
	
	if($query[1]!=1){
		die(returnAjaxData(1,"no User"));
	}else{
		die(returnAjaxData(200,"success",['userInfo'=>$query[0][0]]));
	}
}else{
	die(returnAjaxData(403,"failed To Auth"));
}
?>
