<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-用户注册
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-22
 * @version 2019-06-12
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>注册 / <?=$this->setting->get('systemName');?></title>
	<style>
	body{
		padding-top: 20px;
	}
	</style>
</head>

<body style="background-color: #66CCFF;">
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">欢迎注册 <?=$this->setting->get('systemName');?></h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();'>
					<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
				</div>
				<br>
				<div class="form-group">
					<label for="nickName">昵称</label>
					<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#pwd").focus();'>
				</div>

				<hr>

				<div class="form-group">
					<label for="pwd">密码</label>
					<input type="password" class="form-control" id="pwd" onkeyup='if(event.keyCode==13)$("#checkPwd").focus();'>
					<p class="help-block">请输入<font color="green">6</font>-<font color="green">20</font>字的密码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="checkPwd">确认密码</label>
					<input type="password" class="form-control" id="checkPwd" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
					<p class="help-block">请再次输入密码</p>
				</div>

				<hr>

				<div class="form-group">
					<label for="phone">手机号</label>
					<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();'>
					<p class="help-block">目前仅支持中国大陆的手机号码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input type="email" class="form-control" id="email" onkeyup='if(event.keyCode==13)reg();'>
				</div>
				<button class="btn btn-lg btn-success btn-block" onclick='reg();'>注册 Register &gt;</button>
			</div>
		</div>
	</div>
</div>

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
</script>

<script>
function reg(){
	lockScreen();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	pwd=$("#pwd").val();
	checkPwd=$("#checkPwd").val();
	phone=$("#phone").val();
	email=$("#email").val();

	if(userName==""){
		unlockScreen();
		showModalTips("请输入用户名！");
		return false;
	}
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		showModalTips("请输入 4~20字 的用户名！");
		return false;
	}
	if(nickName==""){
		unlockScreen();
		showModalTips("请输入昵称！");
		return false;
	}
	
	if(pwd==""){
		unlockScreen();
		showModalTips("请输入密码！");
		return false;
	}
	if(checkPwd==""){
		unlockScreen();
		showModalTips("请再次输入密码！");
		return false;
	}
	if(pwd.length<6 || pwd.length>20){
		unlockScreen();
		showModalTips("请输入 6~20 字的密码！");
		return false;
	}
	if(pwd!=checkPwd){
		unlockScreen();
		showModalTips("两次输入的密码不相同！<br>请重新输入！");
		return false;
	}
	
	if(phone==""){
		unlockScreen();
		showModalTips("请输入手机号！");
		return false;
	}
	if(phone.length!=11){
		unlockScreen();
		showModalTips("请正确输入手机号！");
		return false;
	}
	if(email==""){
		unlockScreen();
		showModalTips("请输入邮箱！");
		return false;
	}

	$.ajax({
		url:"./toRegister",
		type:"post",
		data:{"userName":userName,"nickName":nickName,"phone":phone,"email":email,"pwd":pwd},
		dataType:'json',
		error:function(e){
			console.log(e);
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				alert("注册成功！即将跳转至登录页面！");
				window.location.href="<?=base_url('user/login');?>";
				return true;
			}else if(ret.code==5001){
				showModalTips("注册成功！<br>发送激活邮件失败！");
				return false;
			}else if(ret.code==5002){
				showModalTips("注册失败！！！");
				return false;
			}else if(ret.code==4){
				showModalTips("获取角色信息失败！请联系管理员！");
				return false;
			}else if(ret.code==1){
				showModalTips("此用户名已存在！<br>请输入其他用户名！");
				return false;
			}else if(ret.code==2){
				showModalTips("此手机号已存在！<br>请输入其他手机号！");
				return false;
			}else if(ret.code==3){
				showModalTips("此邮箱已存在！<br>请输入其他邮箱！");
				return false;
			}else{
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

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

<div class="modal fade" id="tipsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bolder;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onclick='$("#tipsModal").modal("hide");'>返回 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
