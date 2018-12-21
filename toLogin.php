<?php
/**
 * @name 生蚝科技统一身份认证平台-处理登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-20
 * @version 2018-12-21
 */

require_once 'include/public.func.php';

$appId=isset($_GET['appId'])?$_GET['appId']:die(returnAjaxData(0,"lack Param"));
$userName=isset($_GET['userName'])&&$_GET['userName']!=""?$_GET['userName']:die(returnAjaxData(0,"lack Param"));
$password=isset($_GET['password'])&&$_GET['password']!=""?$_GET['password']:die(returnAjaxData(0,"lack Param"));
$userQuery=PDOQuery($dbcon,"SELECT nick_name,password,salt,app_permission FROM user WHERE user_name=?",[$userName],[PDO::PARAM_STR]);

if($userQuery[1]!=1){
	die(returnAjaxData(4031,"failed To Auth"));
}else{
	$userInfo=$userQuery[0][0];
	$checkPwd=checkPassword($password,$userInfo['password'],$userInfo['salt']);
	
	// 判断密码有效性
	if($checkPwd===true){
		$token=sha1(md5($appId).time());
		setSess(['isLogin'=>1,'token'=>$token,'userName'=>$userName]);

		// 校验是否有权限
		$appPermission=explode(",",$userInfo['app_permission']);
		$appInfo=PDOQuery($dbcon,"SELECT id FROM app WHERE app_id=?",[$appId],[PDO::PARAM_STR]);
		
		if($appInfo[1]!=1){
			// 没有此应用
			die(returnAjaxData(4032,"permission Denied"));
		}elseif(in_array($appInfo[0][0]['id'],$appPermission)!=true){
			// 没有权限访问
			die(returnAjaxData(4032,"permission Denied"));
		}else{
			// 有权限，跳转回应用登录页
			die(returnAjaxData(200,"success",['returnUrl'=>getSess("returnUrl")."?token=".$token]));
		}
	}else{
		die(returnAjaxData(4031,"failed To Auth"));
	}
}
?>
