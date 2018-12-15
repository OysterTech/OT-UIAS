<?php
/**
 * @name 生蚝科技SSO系统-登出
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-13
 * @version 2018-12-14
 */

require_once 'include/public.func.php';
session_destroy();

$url=ROOT_PATH."login.php";

if(isset($_GET['appId']) && isset($_GET['returnUrl'])){
	$url.="?appId=".$_GET['appId']."&returnUrl=".$_GET['returnUrl'];
}

gotoUrl($url);
?>
<html>
<head>
	<title>登出 / 生蚝SSO用户集群中心</title>
	<?php include 'include/header.php'; ?>
</head>
</html>
