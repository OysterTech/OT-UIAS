<?php
/**
 * @name XX应用-SSO登录处理页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-01
 * @version 2018-12-28
 */
require_once 'include/public.func.php';
?>
<html>
<head>
	<title>测试2SSO登录 / 生蚝科技</title>
	<?php include 'include/header.php'; ?>
</head>

<body>
<p id="loginTips">正在登录，请稍候……</p>
<a id="logout_a" href="http://ssouc.xshgzs.com/logout.php" style="display:none;">登出</a>
<div id="footer" style="display:none;"><?php include 'include/footer.php'; ?></div>

<script>
var appId="otsa_a8868b87c9ea27d624c4";
var returnUrl="<?=ROOT_PATH;?>ssoLogin.php";
var ssoLoginUrl=OTSSO.ssoServiceUrl+"login.php?appId="+appId+"&returnUrl="+encodeURIComponent(returnUrl);
var token=getURLParam("token");

if(token=="" || token==null){
	delCookie("OTSSO_userInfo");
	window.location.href=ssoLoginUrl;
}else{
	$("#loginTips").attr("style","display:none;");
	$("#logout_a").attr("style","");
	$("#footer").attr("style","");
	OTSSO.getUserInfo(token,appId,returnUrl);
	alert(getCookie("OTSSO_userInfo"));
}
</script>

<div class="modal fade" id="tipsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bold;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
