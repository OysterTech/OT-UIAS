<?php
/**
 * @name 生蚝科技统一身份认证平台-首页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-21
 */

require_once 'include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

echo "token:".getSess("token")."<br>";
?>
<html>
<head>
	<title>首页 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="padding:20px 0 0 20px;">

<?php var_dump($_SESSION);?>
<a href="<?=ROOT_PATH;?>logout.php">登出</a><br>

<?php include 'include/footer.php'; ?>

</body>
</html>
