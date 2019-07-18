<?php 
/**
 * @name V-用户重置密码
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-24
 * @version V1.0 2018-08-07
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>重置密码 / <?=$this->Setting_model->get('systemName'); ?></title>
	<style>
	body{
		padding-top: 40px;
	}
	</style>
</head>

<body>
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">忘记密码</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" value="<?=$this->session->userdata($this->sessPrefix.'forgetPwd_userName'); ?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label for="userName">昵称</label>
					<input class="form-control" id="userName" value="<?=$this->session->userdata($this->sessPrefix.'forgetPwd_nickName'); ?>" disabled>
				</div>

				<hr>

				<div class="form-group">
					<label for="newPwd">新密码</label>
					<input type="password" class="form-control" id="newPwd" onkeyup='if(event.keyCode==13)$("#checkPwd").focus();'>
					<p class="help-block">请输入<font color="green">6</font>-<font color="green">20</font>字的密码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="checkPwd">确认密码</label>
					<input type="password" class="form-control" id="checkPwd" onkeyup='if(event.keyCode==13)resetPwd();'>
					<p class="help-block">请再次输入密码</p>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='resetPwd();'>确 认 重 置 密 码 &gt;</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
function resetPwd(){
	lockScreen();
	newPwd=$("#newPwd").val();
	checkPwd=$("#checkPwd").val();

	if(newPwd==""){
		unlockScreen();
		$("#tips").html("请输入新密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(checkPwd==""){
		unlockScreen();
		$("#tips").html("请再次输入新密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(newPwd.length<6 || newPwd.length>20){
		unlockScreen();
		$("#tips").html("请输入 6~20 字的密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(newPwd!=checkPwd){
		unlockScreen();
		$("#tips").html("两次输入的密码不相同！<br>请重新输入！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?=site_url('user/toResetPwd'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"pwd":newPwd},
		dataType:'json',
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("成功重置密码！\n请牢记您的新密码！\n\n即将跳转至登录页面！");
				window.location.href="<?=site_url('user/login'); ?>";
				return true;
			}else if(ret.message=="resetFailed"){
				$("#tips").html("重置密码失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				$("#tips").html("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				$("#tipsModal").modal('show');
				return false;
			}
		}
	});
}
</script>

<?php $this->load->view('include/tipsModal'); ?>

</body>
</html>
