<?php
/**
 * @name 生蚝科技统一身份认证平台-登录页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-23
 */

require_once 'include/public.func.php';

$appId=isset($_GET['appId'])?$_GET['appId']:"";
$returnUrl=isset($_GET['returnUrl'])?urldecode($_GET['returnUrl']):ROOT_PATH."main.php";

// 查询应用信息
$query=PDOQuery($dbcon,"SELECT * FROM app WHERE app_id=? AND return_url=?",[$appId,$returnUrl],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($query[1]!=1){
	// 没有此应用
	if($appId!="" && $returnUrl!=ROOT_PATH."main.php"){
		gotoUrl(ROOT_PATH."noAccess.php?mod=appInfo");
	}else{
		$appName="统一认证平台用户中心";
	}
}else{
	$appName=$query[0][0]['name'];
}

setSess(['appId'=>$appId,'returnUrl'=>$returnUrl]);

if(getSess("isLogin")==1){
	// 已登录，重新生成token，防止二次使用
	$token=sha1(md5($appId).time());
	setSess(['token'=>$token]);
	
	gotoUrl($returnUrl."?token=".getSess('token'));
}
?>
<html>
<head>
	<title>登录 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
	<link href="<?=CSS_PATH;?>login.css" rel="stylesheet">
</head>
<body>
<div class="wrapper fadeInDown">
	<div id="formContent">
		<!--a id="qrbtn" href="javascript:toQrLogin();" target="_self"><div style="width: 60px; height: 60px; background-image: url(https://passport.dingstudio.cn/sso/static/images/qrCode.png); background-size: 120px 120px; background-repeat: no-repeat; background-position: 0 0; position: absolute; top: 5px; right: 5px"></div></a-->
		<!-- Tabs Titles -->
		<h2 class="active" style="line-height:32px;"> 欢迎登录 生蚝科技统一身份认证平台<br>正在通过统一认证平台快速登录：<?=$appName;?> </h2>
		<!-- Icon -->
		<div class="fadeIn first">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px"
			y="0px" width="210.25px" height="159.942px" viewBox="0 0 105.125 79.971" enable-background="new 0 0 105.125 79.971"
			xml:space="preserve">
			<g id="Icons">
				<g>
					<circle fill="#E9EAEB" cx="52" cy="48.971" r="20" />
					<path fill="none" stroke="#D8D8D8" stroke-width="2" stroke-miterlimit="10" d="M29.657,65.902    c-3.517-4.677-5.602-10.492-5.602-16.795c0-15.448,12.523-27.972,27.973-27.972C67.477,21.135,80,33.659,80,49.107    c0,6.304-2.085,12.121-5.604,16.798" />
					<g>
						<path fill="#FFFFFF" stroke="#2A5082" stroke-width="2" stroke-miterlimit="10" d="M52,54.971c2.292,0,6.438-3.063,7-4     s0.775-1.863,1-3c0.053-0.27,0.828-1.771,1-2c0.942-1.253,1-5,1-5l-1-1c0,0-0.244-3.141-0.383-4.681     c-0.575-4.498-3.674-7.319-8.617-7.319s-8.042,2.822-8.617,7.319C43.244,36.831,43,39.971,43,39.971l-1,1c0,0,0.058,3.747,1,5     c0.172,0.229,0.947,1.73,1,2c0.224,1.137,0.438,2.063,1,3S49.708,54.971,52,54.971z" />
						<path fill="#2DC1E5" stroke="#2A5082" stroke-width="2" stroke-miterlimit="10" d="M52,74.971c7.004,0,13.39-2.432,18-7v-4     c-0.561-2.002-2.162-3.026-4-4l-7-3c0,0-3.604,3-7,3s-6-3-6-3l-8,3c-1.838,0.974-3.439,1.998-4,4v4     C38.61,72.54,44.996,74.971,52,74.971z" />
						<g>
							<line fill="none" stroke="#2A5082" stroke-width="2" stroke-miterlimit="10" x1="46"
							y1="56.971" x2="46" y2="51.971" />
						</g>
						<g>
							<line fill="none" stroke="#2A5082" stroke-width="2" stroke-miterlimit="10" x1="58"
							y1="57.565" x2="58" y2="51.971" />
						</g>
					</g>
				</g>
				<ellipse fill="#EE5456" cx="12.771" cy="43.409" rx="2.104" ry="2.063" />
				<polygon fill="#EE5456" points="86.028,32.771 85.644,33.905 86.359,34.864 85.163,34.849 84.471,35.826 84.116,34.683    82.973,34.328 83.95,33.636 83.935,32.439 84.894,33.155  " />
				<polygon fill="#2EC3E6" points="29.75,14.596 29.083,14.37 28.519,14.791 28.527,14.087 27.952,13.68 28.625,13.471 28.834,12.799    29.241,13.374 29.945,13.365 29.523,13.929  " />
				<circle fill="none" stroke="#2EC3E6" stroke-miterlimit="10" cx="15.25" cy="18.471" r="1.125" />
				<circle fill="#2EC3E6" cx="89.75" cy="47.221" r="1.25" />
				<circle fill="#2EC3E6" cx="71.688" cy="18.784" r="1.688" />
				<rect x="37" y="7.971" fill="#CECECE" width="1" height="1" />
				<rect x="85" y="57.971" transform="matrix(0.9503 -0.3114 0.3114 0.9503 -14.2181 29.8919)" fill="#CECECE" width="3" height="3" />
				<rect x="14.334" y="57.805" fill="#CECECE" width="2" height="2" />
				<rect x="16" y="31.971" transform="matrix(0.6706 -0.7419 0.7419 0.6706 -18.8596 23.4739)" fill="none" stroke="#CECECE" stroke-miterlimit="10" width="2" height="2" />
				<rect x="49" y="8.971" transform="matrix(0.5866 -0.8099 0.8099 0.5866 12.397 45.2283)" fill="#2EC3E6" width="3" height="3" />
				<circle fill="#FDDB00" cx="88.834" cy="21.638" r="1.5" />
				<circle fill="#FDDB00" cx="26.583" cy="23.555" r="0.75" />
				<circle fill="#FDDB00" cx="63.625" cy="8.763" r="1.042" />
				<polygon fill="#FDDB00" points="21.118,11.668 21.705,12.087 22.385,11.841 22.167,12.53 22.61,13.099 21.889,13.104 21.485,13.703 21.256,13.018 20.562,12.819 21.143,12.389" />
				<polygon fill="#D8D8D8" points="82.083,10.138 81.728,11.087 80.728,11.254 80.083,10.472 80.438,9.522 81.438,9.355" />
				<polygon fill="#D8D8D8" points="40.083,17.305 38.819,17.826 37.736,16.992 37.917,15.638 39.181,15.117 40.264,15.95" />
				<polygon fill="#D8D8D8" points="61.417,16.221 60.548,16.394 59.964,15.727 60.249,14.888 61.118,14.716 61.702,15.382" />
				<polygon fill="#D8D8D8" points="94,36.555 92.964,37.015 92.047,36.348 92.166,35.221 93.202,34.76 94.119,35.427" />
			</g>
			<g id="Layer_3">
			</g>
			</svg>
		</div>
		<!-- Login Form -->
		<div class="form-signin">
			<input type="hidden" id="appId" value="<?=$appId;?>">
			<input type="text" id="userName" class="fadeIn second" maxlength="20" placeholder="键入您的统一身份认证账号" onkeyup='if(event.keyCode==13)$("#password").focus();'>
			<input type="password" id="password" class="fadeIn third" maxlength="30" placeholder="键入您账号对应的密码" onkeyup='if(event.keyCode==13)toLogin();'>
			<input type="button" class="fadeIn fourth" style="font-size:18px;" value="登 录" onclick="toLogin()">
		</div>
		<!-- Remind Passowrd -->
		<div id="formFooter">
			<span style="color: #56baed;line-height:28px;">本系统已整合统一身份认证<br>单点登录服务由 生蚝科技 提供</span>
		</div>
	</div>
</div>

<?php include 'include/footer.php'; ?>

<script>
function toLogin(){
	appId=$("#appId").val();
	userName=$("#userName").val();
	password=$("#password").val();
	
	$.ajax({
		url:"toLogin.php",
		//type:"post"
		data:{"appId":appId,"userName":userName,"password":password},
		dataType:"json",
		error:function(e){
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				data=ret.data;
				window.location.href=data['returnUrl'];
				return true;
			}else if(ret.code==4031){
				showModalTips("用户名或密码错误！");
				return false;
			}else if(ret.code==4032){
				alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");
				window.location.href="<?=ROOT_PATH;?>main.php";
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				showModalTips("未知错误！请提交错误码["+ret.code+"]并联系技术支持");
				return false;
			}
		}
	});
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
