<?php
/**
 * @name 生蚝科技统一身份认证平台-处理修改资料
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-28
 * @version 2018-12-28
 */

require_once '../include/public.func.php';

$userId=getSess("user_id");
$userName=isset($_POST['userName'])&&$_POST['userName']!=""?$_POST['userName']:die(returnAjaxData(0,"lackParam"));
$nickName=isset($_POST['nickName'])&&$_POST['nickName']!=""?$_POST['nickName']:die(returnAjaxData(0,"lackParam"));
$phone=isset($_POST['phone'])&&$_POST['phone']!=""?$_POST['phone']:die(returnAjaxData(0,"lackParam"));
$email=isset($_POST['email'])&&$_POST['email']!=""?$_POST['email']:die(returnAjaxData(0,"lackParam"));

// 检查是否有重复
$query1=PDOQuery($dbcon,"SELECT id FROM user WHERE user_name=? AND id!=?",[$userName,$userId],[PDO::PARAM_STR,PDO::PARAM_INT]);
if($query1[1]!=0){
	die(returnAjaxData(1,"have UserName"));
}
$query2=PDOQuery($dbcon,"SELECT id FROM user WHERE nick_name=? AND id!=?",[$nickName,$userId],[PDO::PARAM_STR,PDO::PARAM_INT]);
if($query2[1]!=0){
	die(returnAjaxData(2,"have NickName"));
}
$query3=PDOQuery($dbcon,"SELECT id FROM user WHERE phone=? AND id!=?",[$phone,$userId],[PDO::PARAM_STR,PDO::PARAM_INT]);
if($query3[1]!=0){
	die(returnAjaxData(3,"have Phone"));
}
$query4=PDOQuery($dbcon,"SELECT id FROM user WHERE email=? AND id!=?",[$email,$userId],[PDO::PARAM_STR,PDO::PARAM_INT]);
if($query4[1]!=0){
	die(returnAjaxData(4,"have Email"));
}

// 修改资料
$query5=PDOQuery($dbcon,"UPDATE user SET user_name=?,nick_name=?,phone=?,email=? WHERE id=?",[$userName,$nickName,$phone,$email,$userId],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
if($query5[1]==1){
	die(returnAjaxData(200,"success"));
}else{
	die(returnAjaxData(5,"changeError"));
}
