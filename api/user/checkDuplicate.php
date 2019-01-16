<?php
/**
 * @name 生蚝科技统一身份认证平台-API-检查用户信息重复性
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-13
 * @version 2019-01-13
 */

require_once '../../include/public.func.php';

$method=isset($_GET['method'])&&$_GET['method']!=""?$_GET['method']:die(returnAjaxData(0,"lack Param"));
$value=isset($_GET['value'])&&$_GET['value']!=""?$_GET['value']:die(returnAjaxData(0,"lack Param"));
$sql="SELECT id FROM user WHERE ";

if($method=="userName"){
	$sql.="user_name=?";
}else if($method=="phone"){
	$sql.="phone=?";
}else{
	die(returnAjaxData(2,"Invaild Method"));
}

$query=PDOQuery($dbcon,$sql,[$value],[PDO::PARAM_STR]);

if($query[1]!=1){
	die(returnAjaxData(200,"no User"));
}else{
	die(returnAjaxData(403,"have User"));
}
