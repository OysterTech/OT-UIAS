<?php
/**
 * @name 生蚝科技统一身份认证平台-首页
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-26
 * @version 2018-12-26
 */

require_once 'include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}else{
	gotoUrl(ROOT_PATH."dashborad.php");
}

?>
