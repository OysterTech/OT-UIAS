<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-20
 * @version 2020-01-07
 */
?>

<!DOCTYPE html>
<html>

<head>
	<title>登录 / 生蚝科技统一身份认证平台</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="生蚝科技,生蚝科技统一身份认证平台,生蚝科技用户中心">
	<meta name="description" content="生蚝科技统一身份认证平台">
	<meta name="author" content="生蚝科技 Oyster Tech">
	<link rel="shortcut icon" href="<?=base_url('resource/image/favicon.ico');?>">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css" rel="stylesheet">
	<link href="<?=base_url('resource/css/loginOld.css');?>" rel="stylesheet">

	<!-- Baidu tongji-->
	<script>
		var _hmt = _hmt || [];
		(function() {
			var hm = document.createElement("script");
			hm.src = "https://hm.baidu.com/hm.js?57f935b2ea561c6b84f8ea26dd96fe5a";
			var s = document.getElementsByTagName("script")[0]; 
			s.parentNode.insertBefore(hm, s);
		})();
	</script>
	<style>
	.qrCodeFilter {
		position: absolute;
		left:0;
		right:0;
		top:0;
		bottom: 0;
		background: white;
		opacity: 0.9;
		filter:alpha(opacity=90)
	}
	.info{
		color:#222;
		display: inline-block;
		position: absolute;
		height: 110px;
		top:50%;
		margin-top: -50px;
		width: 115px;
		left:50%;
		margin-left:-57px;
	}
	</style>
</head>

<body style="background-color: #66CCFF;">
<div class="wrapper fadeInDown">
	<div id="formContent">
		<a id="changeLoginMethodBtn" class="loginMethodButton" style="background-position:0 0;" onclick="changeLoginMethod();"></a>

		<h2 class="active" style="line-height:32px;">欢迎登录 生蚝科技统一身份认证平台<br>正在通过统一认证平台快速登录：<?=$appName;?> </h2>

		<div id="userIcon" class="fadeIn first">
			<?php include 'loginPageSvg.php'; ?>
		</div>

		<div id="loginForm" class="form-signin">
			<input type="hidden" id="<?=$this->sessPrefix;?>appId" value="<?=$appId;?>">
			<input type="text" id="<?=$this->sessPrefix;?>userName" class="fadeIn second" maxlength="20" placeholder="键入您的通行证账号" onkeyup='if(event.keyCode==13)$("#<?=$this->sessPrefix;?>password").focus();'>
			<input type="password" id="<?=$this->sessPrefix;?>password" class="fadeIn third" maxlength="30" placeholder="键入您账号对应的密码" onkeyup='if(event.keyCode==13)toLogin();'>
			
			<button class="fadeIn fourth" style="font-size:18px;background-color:#56baed;border:none;color:#fff;padding:15px 12%;text-align:center;text-decoration:none;display:inline-block;text-transform:uppercase;-webkit-box-shadow:0 10px 30px 0 rgba(95,186,233,.4);box-shadow:0 10px 30px 0 rgba(95,186,233,.4);-webkit-border-radius:5px 5px 5px 5px;border-radius:5px 5px 5px 5px;margin:5px 5px 40px 5px;-webkit-transition:all .3s ease-in-out;-moz-transition:all .3s ease-in-out;-ms-transition:all .3s ease-in-out;-o-transition:all .3s ease-in-out;transition:all .3s ease-in-out" onclick="toReg()">注 册</button>
			<button class="fadeIn fourth" style="font-size:18px;background-color:#9ccc65;border:none;color:#fff;padding:15px 12%;text-align:center;text-decoration:none;display:inline-block;text-transform:uppercase;-webkit-box-shadow:0 10px 30px 0 rgba(95,186,233,.4);box-shadow:0 10px 30px 0 rgba(95,186,233,.4);-webkit-border-radius:5px 5px 5px 5px;border-radius:5px 5px 5px 5px;margin:5px 5px 40px 5px;-webkit-transition:all .3s ease-in-out;-moz-transition:all .3s ease-in-out;-ms-transition:all .3s ease-in-out;-o-transition:all .3s ease-in-out;transition:all .3s ease-in-out" onclick="toLogin()">登 录</button>

			<br>

			<p style="text-align:left;padding-left:25px;font-size:17px;">
				第三方登录：
				<!--a href="https://github.com/login/oauth/authorize?client_id=637c1fd18f219c7987aa&scope=user" style="color:#24292e"><i class="fa fa-2x fa-github" aria-hidden="true"></i></a>&nbsp;&nbsp;-->
				<a onclick="alert('敬请期待！\n\n预计于2020年2月1日前上线！')" style="color:#24292e">
					<i class="fa fa-2x fa-github" aria-hidden="true"></i>
				</a>&nbsp;&nbsp;
				<a onclick="changeLoginMethod();" style="color:#2ba245">
					<i class="fa fa-2x fa-weixin" aria-hidden="true"></i>
				</a>&nbsp;&nbsp;
				<a onclick="alert('敬请期待！\n\n预计于2020年2月1日前上线！')">
					<img src="https://id.xshgzs.com/resource/images/gzlib_origin.jpg" style="width:40px;height:40px;border-radius:50%;margin-top: -12px;">
				</a>&nbsp;&nbsp;
				<a onclick="alert('敬请期待！\n\n预计于2020年2月1日前上线！')">
					<img src="https://id.xshgzs.com/resource/images/weixinWork.png" style="width:40px;height:40px;margin-top: -12px;">
				</a>&nbsp;&nbsp;
			</p>
			<p style="line-height:6px;">&nbsp;</p>
		</div>

		<!-- ▼ 微信小程序扫码登录 ▼ -->
		<div id="loginQrcode" style="display:none;">
			<center>
			<img id="qrCodeImg" style='width:100%;height:100%;'></img>
			<br>
			请使用微信扫描此小程序码 <a onclick="stopCheckStatus();loadWxLoginQrCode();"><i class="fa fa-refresh" aria-hidden="true"></i> 刷新</a>
			</center>
			<div class="weui_msg" id="wxCodeStatusDiv" style="display:none;">
				<div class="weui_icon_area"><i id="wxCodeStatusIcon" class="weui_icon_warn weui_icon_msg"></i></div>
				<div class="weui_text_area">
					<h4 id="wxCodeStatusContent" class="weui_msg_title"></h4>
				</div>
			</div>
		</div>
		<!-- ▲ 微信小程序扫码登录 ▲ -->

		<div id="formFooter">
			<span style="color: #56baed;line-height:28px;">本系统已整合统一身份认证<br>单点登录服务由 <a href="https://www.xshgzs.com?from=sso" target="_blank">生蚝科技</a> 提供</span>
		</div>
	</div>
</div>

<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?=base_url('resource/js/utils.js');?>"></script>

<center>
	<!-- 页脚版权 -->
	<p style="font-weight:bold;font-size:20px;line-height:26px;">
		&copy; <a href="https://www.xshgzs.com?from=rbac" target="_blank" style="font-size:21px;">生蚝科技</a> 2014-2019
		<a style="color:#07C160" onclick='showWXCode()'><i class="fa fa-weixin fa-lg" aria-hidden="true"></i></a>
		<a style="color:#FF7043" onclick='launchQQ()'><i class="fa fa-qq fa-lg" aria-hidden="true"></i></a>
		<a style="color:#29B6F6" href="mailto:master@xshgzs.com"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></a>
		<a style="color:#AB47BC" href="https://github.com/OysterTech" target="_blank"><i class="fa fa-github fa-lg" aria-hidden="true"></i></a>
		
		<br>
		
		All Rights Reserved.<br>
		<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:black;">粤ICP备19018320号-1</a><br><br>
	</p>
	<!-- ./页脚版权 -->
</center>

<script>
var nowLoginMethod="password";
var pwdMethodBtnStyle="background-position:0 -57;";
var qrMethodBtnStyle="background-position:0 0;";
var checkQrStatus;
var isAjaxing=0;

function launchQQ(){
	if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
		window.location.href="mqqwpa://im/chat?chat_type=wpa&uin=571339406";
	}else{
		window.open("http://wpa.qq.com/msgrd?v=3&uin=571339406");
	}
}

function showWXCode(){
	$("#wxModal").modal('show');
}

// 监听模态框关闭事件
$(function (){
	$('#tipsModal').on('hidden.bs.modal',function (){
		isAjaxing=0;
	});
});

window.onload=function(){
	/********** ▼ 记住密码 ▼ **********/
	Remember=getCookie("<?=$this->sessPrefix;?>RmUN");
	if(Remember!=null){
		$("#userName").val(Remember);
		$("#pwd").focus();
		$("#Remember").attr("checked",true);
	}else{
		$("#userName").focus();
	}
	/********** ▲ 记住密码 ▲ **********/

	localStorage.removeItem("allRoleInfo");
	localStorage.removeItem("jwtToken");
}

function changeLoginMethod(){
	if(nowLoginMethod=="password"){
		nowLoginMethod="qr";
		loadWxLoginQrCode();
		$("#changeLoginMethodBtn").attr("style",pwdMethodBtnStyle);
		$("#userIcon").attr("style","display:none");
		$("#loginForm").attr("style","display:none");
		$("#loginQrcode").attr("style","font-size:17px;");
	}else if(nowLoginMethod=="qr"){
		nowLoginMethod="password";
		stopCheckStatus();
		$("#changeLoginMethodBtn").attr("style",qrMethodBtnStyle);
		$("#userIcon").attr("style","");
		$("#loginForm").attr("style","");
		$("#loginQrcode").attr("style","display:none");
	}
}


function loadWxLoginQrCode(){
	width=$("#loginForm").width();
	width-=80;
	if(width<280) width=280;
	$("#qrCodeImg").attr('src','<?=$this->API_PATH;?>wxmp/getQrCode/<?=session_id();?>/<?=$appId;?>/'+width);
	$("#qrCodeImg").attr('style','width:'+width+'px;height:'+width+'px;');
	$("#wxCodeStatusDiv").attr('style','display:none');
	startCheckStatus();
}


function startCheckStatus(){
	checkQrStatus = window.setInterval(function() {
		$.ajax({
			url:"<?=$this->API_PATH;?>wxmp/checkStatus",
			dataType:"json",
			success:function(ret){
				console.log(ret);
				if(ret.code==200){
					status=ret.data['status'];

					switch(status){
						case "-1":
							// 用户主动取消
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-warn weui-icon_msg-primary');
							$("#wxCodeStatusContent").html('用户已取消登录<br>请点击“刷新”重新扫码');
							break;
						case "0":
							// 过期
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-warn weui-icon_msg');
							$("#wxCodeStatusContent").html('小程序码已超时失效<br>请点击“刷新”重新扫码');
							break;
						case "1":
							// 正常
							break;
						case "2":
							// 已经被扫
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-info weui-icon_msg');
							$("#wxCodeStatusContent").html('已成功扫码<br>请在微信小程序授权登录');
							break;
						case "3":
							// 已授权
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-success weui-icon_msg');
							$("#wxCodeStatusContent").html('正在跳转，请稍候！');
							setTimeout(function(){
								window.location.href="<?=$redirectUrl;?>";
							},1500);
							break;
						default:
							break;
					}
				}else if(ret.code==403){
					showModalTips("暂未生成小程序码！<br>请点击“刷新”生成！");
					stopCheckStatus();
					return false;
				}else if(ret.code==404){
					showModalTips("小程序码信息不存在！<br>请点击“刷新”生成！");
					stopCheckStatus();
					return false;
				}
			}
		})
	}, 2000);
}


function stopCheckStatus(){
	window.clearInterval(checkQrStatus);
	$("#qrCodeImg").attr('src','');
}


function toLogin(){
	// 防止多次提交
	if(isAjaxing==1){
		return false;
	}

	isAjaxing=1;
	lockScreen();
	appId=$("#<?=$this->sessPrefix;?>appId").val();
	userName=$("#<?=$this->sessPrefix;?>userName").val();
	password=$("#<?=$this->sessPrefix;?>password").val();

	/********** ▼ 记住密码 ▼ **********/
	Remember=$("input[type='checkbox']").is(':checked');
	if(Remember==true){
		setCookie("<?=$this->sessPrefix;?>RmUN",userName);
	}else{
		delCookie("<?=$this->sessPrefix;?>RmUN");
	}
	/********** ▲ 记住密码 ▲ **********/
	
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		showModalTips("请正确输入用户名！");
		return false;
	}
	if(password.length<6 || password.length>20){
		unlockScreen();
		showModalTips("请正确输入密码！");
		return false;
	}


	$.ajax({
		url:"<?=base_url('user/toLogin');?>",
		type:"post",
		data:{"appId":appId,"userName":userName,"password":password},
		dataType:"json",
		error:function(e){
			unlockScreen();
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#pwd").removeAttr("disabled");

			if(ret.code==200){
				localStorage.setItem('allRoleInfo',ret.data['allRoleInfo']);
				localStorage.setItem('jwtToken',ret.data['jwtToken']);
				
				if(appId=="" && getURLParam("service")!=null){url=decodeURIComponent(getURLParam("service"));}
				else if(appId==""){url="<?=base_url('dashborad');?>";}
				else{url=ret.data['redirectUrl'];}

				window.location.href=url;
				return true;
			}else if(ret.code==1){
				showModalTips("当前用户被禁用！<br>请联系管理员！");
				return false;
			}else if(ret.code==4031){
				showModalTips("用户名或密码错误！");
				return false;
			}else if(ret.code==4032){
				unlockScreen();
				alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");
				window.location.href="<?=base_url('dashborad');?>";
				return false;
			}else if(ret.code==3){
				showModalTips("用户暂未激活！<br>请尽快进行激活！");
				return false;
			}else if(ret.code==2){
				showModalTips("获取角色信息失败！请联系管理员！");
				return false;
			}else{
				console.log(ret);
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
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
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bolder;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onclick='isAjaxing=0;$("#tipsModal").modal("hide");'>返回 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="wxModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">微信公众号二维码</h3>
			</div>
			<div class="modal-body">
				<center><img src="https://www.xshgzs.com/resource/index/images/wxOfficialAccountQRCode.jpg" style="width:85%"></center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick='$("#wxModal").modal("hide");'>关闭 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
