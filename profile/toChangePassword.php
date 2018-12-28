<?php
/**
 * @name 生蚝科技统一身份认证平台-处理修改密码
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-28
 * @version 2018-12-28
 */

require_once '../include/public.func.php';

$userId=getSess("user_id");
$oldPwd=isset($_POST['oldPwd'])&&$_POST['oldPwd']!=""?$_POST['oldPwd']:die(returnAjaxData(0,"lackParam"));
$newPwd=isset($_POST['newPwd'])&&$_POST['newPwd']!=""?$_POST['newPwd']:die(returnAjaxData(0,"lackParam"));

$query1=PDOQuery($dbcon,"SELECT password,salt FROM user WHERE id=?",[$userId],[PDO::PARAM_INT]);

if($query1[1]!=1){
	die(returnAjaxData(403,"failed To Auth"));
}else{
	$vaild=checkPassword($oldPwd,$query1[0][0]['password'],$query1[0][0]['salt']);

	if($vaild!=TRUE){
		die(returnAjaxData(403,"failed To Auth"));
	}
}

$salt=getRanSTR(8);
$hashPassword=sha1(md5($newPwd).$salt);

// 修改密码
$query2=PDOQuery($dbcon,"UPDATE user SET salt=?,password=? WHERE id=?",[$salt,$hashPassword,$userId],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
if($query2[1]==1){
	die(returnAjaxData(200,"success"));
}else{
	die(returnAjaxData(2,"changeError"));
}
