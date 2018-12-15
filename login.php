<?php
/**
 * @name 生蚝科技SSO系统-登录页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-14
 */

require_once 'include/public.func.php';

$appId=isset($_GET['appId'])?$_GET['appId']:"";
$returnUrl=isset($_GET['returnUrl'])?urldecode($_GET['returnUrl']):ROOT_PATH."main.php";

if(getSess("isLogin")!=1){
	// TODO:判断appId与回调网址是否匹配
	setSess(['appId'=>$appId,'returnUrl'=>$returnUrl]);
}else{
	// 已登录，重新生成token，防止二次使用
	$token=sha1(md5($appId).time());
	setSess(['token'=>$token]);
	
	gotoUrl($returnUrl."?token=".getSess('token'));
}

if(isset($_POST) && $_POST){
	$userName=$_POST['userName'];
	$password=$_POST['password'];
	// TODO:判断用户名密码
	
	$token=sha1(md5($appId).time());
	setSess(['isLogin'=>1,'token'=>$token,'userName'=>$userName]);
	
	gotoUrl($returnUrl."?token=".getSess('token'));
}
?>
<html>
<head>
	<title>登录 / 生蚝SSO用户集群中心</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="padding:20px 0 0 20px;">

<form method="post">
	用户名：<input name="userName" id="userName" value="1"><br>
	密码：<input name="password" id="password" value="1"><br>
	<input type="submit" value="登录">
</form>

<?php include 'include/footer.php'; ?>

</body>
</html>
