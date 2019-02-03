<?php
/**
 * @name 生蚝科技统一身份认证平台-处理清空记录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-28
 * @version 2018-12-28
 */

require_once '../../include/public.func.php';

$userId=getSess("user_id");
$role=getSess("role");

if($role<2){
	die(returnAjaxData(403,"failed To Auth"));
}

$password=isset($_POST['password'])&&$_POST['password']!=""?$_POST['password']:die(returnAjaxData(0,"lackParam"));
$query1=PDOQuery($dbcon,"SELECT password,salt FROM user WHERE id=?",[$userId],[PDO::PARAM_INT]);

if($query1[1]!=1){
	die(returnAjaxData(403,"failed To Auth"));
}else{
	$vaild=checkPassword($password,$query1[0][0]['password'],$query1[0][0]['salt']);

	if($vaild!=TRUE){
		die(returnAjaxData(403,"failed To Auth"));
	}
}

// 修改资料
$query2=PDOQuery($dbcon,"TRUNCATE log");
$query3=PDOQuery($dbcon,"INSERT INTO log(user_id,app_id,method,content,ip) VALUES (?,?,?,?,?)",[$userId,0,"后台","清空记录",getIP()],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
die(returnAjaxData(200,"success"));
