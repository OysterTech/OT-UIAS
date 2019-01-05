<?php
/**
 * @name 生蚝科技统一身份认证平台-登出
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-13
 * @version 2019-01-01
 */

require_once 'include/public.func.php';
session_destroy();

$url=ROOT_PATH."login.php";

if(isset($_GET['service']) && $_GET['service']!="") $url.="?service=".urlencode($_GET['service']);

if(isset($_GET['appId']) && isset($_GET['returnUrl'])){
	$url.="?appId=".$_GET['appId']."&returnUrl=".$_GET['returnUrl'];
}

gotoUrl($url);
?>
<html>
<head>
	<?php include 'include/header.php'; ?>
</head>
</html>
