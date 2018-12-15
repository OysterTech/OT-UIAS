<?php
/**
 * @name 生蚝科技SSO系统-首页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-14
 */

require_once 'include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

echo "token:".getSess("token")."<br>";
?>
<html>
<head>
	<title>首页 / 生蚝SSO用户集群中心</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="padding:20px 0 0 20px;">

<a href="http://ssouc.xshgzs.com/testApp/login.php">测试应用</a><br>
<a href="http://ssouc.xshgzs.com/test2/login.php">测试2应用</a><br>
<a href="<?=ROOT_PATH;?>logout.php">登出</a><br>

<?php include 'include/footer.php'; ?>

</body>
</html>
