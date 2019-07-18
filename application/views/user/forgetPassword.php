<?php 
/**
 * @name V-用户忘记密码（邮箱验证）
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-08
 * @version 2018-08-17
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>忘记密码 / <?=$this->Setting_model->get('systemName');?></title>
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
				<h3 class="panel-title">忘记密码（邮箱验证）</h3>
			</div>
			<div class="panel-body">
				<div class="input-group">
					<input type="email" class="form-control" id="email" onkeyup='if(event.keyCode==13)sendCode();' placeholder="注册邮箱">
					<span class="input-group-btn">
						<button id="sendCode_btn" class="btn btn-primary" onclick="sendCode();">发送验证码</button>
					</span>
				</div>
				<br>
				<div class="form-group">
					<input type="number" class="form-control" id="verifyCode" onkeyup='if(event.keyCode==13)resetPwd();' placeholder="邮箱验证码">
				</div>
				
				<hr>

				<button class="btn btn-success btn-block" onclick='resetPwd();'>重置密码 &gt;</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
var countdown=60;
function timer(){
	if(countdown==0){
		$("#sendCode_btn").removeAttr("disabled");
		$("#sendCode_btn").attr("class","btn btn-primary");
		$("#sendCode_btn").html("发送验证码");
		countdown=60;
	}else{
		$("#sendCode_btn").attr("disabled",true);
		$("#sendCode_btn").attr("class","btn btn-default");
		$("#sendCode_btn").html("重新发送 ("+countdown+")");
		countdown--;
		setTimeout(function(){timer()},1000);
	}
}

function sendCode(){
	lockScreen();
	email=$("#email").val();

	if(email==""){
		unlockScreen();
		$("#tips").html("请输入邮箱！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?=base_url('user/forgetPassword/sendCode'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"email":email},
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
				timer();
				return true;
			}else if(ret.message=="sendFailed"){
				$("#tips").html("邮件发送失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="noUser"){
				$("#tips").html("此邮箱暂未注册！");
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

function resetPwd(){
	lockScreen();
	email=$("#email").val();
	verifyCode=$("#verifyCode").val();

	if(email==""){
		unlockScreen();
		$("#tips").html("请输入邮箱！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(verifyCode==""){
		unlockScreen();
		$("#tips").html("请输入邮箱验证码！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?=base_url('user/forgetPassword/verify'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"email":email,"verifyCode":verifyCode},
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
				window.location.href=ret.data;
			}else if(ret.message=="invalidCode"){
				$("#tips").html("验证码错误！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="invalidMail"){
				$("#tips").html("邮箱与验证码不匹配！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="expired"){
				$("#tips").html("验证码已过期！");
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
