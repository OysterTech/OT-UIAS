<?php
/**
 * @name XX应用-SSO登录页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-01
 * @version 2018-12-14
 */
require_once 'include/public.func.php';
?>
<html>
<head>
	<title>测试应用SSO登录 / 生蚝科技</title>
	<?php include 'include/header.php'; ?>
</head>

<body>
正在跳转至SSO登录系统，请稍后……
<script>
var appId="aid_18sj";
var returnUrl="<?=ROOT_PATH;?>ssoLogin.php";
var ssoServiceUrl="<?=SSO_SERVICE_PATH;?>";
var ssoUrl=ssoServiceUrl+"login.php?appId="+appId+"&returnUrl="+encodeURIComponent(returnUrl);

window.location.href=ssoUrl;
</script>

<?php include 'include/footer.php'; ?>

</body>
</html>
