<?php
/**
 * @name 生蚝科技SSO系统-API-获取用户信息
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-03
 * @version 2018-12-03
 */

require_once '../include/public.func.php';
$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:die(returnAjaxData(0,"lack Param"));

if(getSess("token")==$token){
	die(returnAjaxData(200,"success",['userName'=>getSess("userName")]));
}else{
	die(returnAjaxData(403,"failed To Auth"));
}
?>
