<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-修改个人资料
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-19
 * @version 2019-06-12
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('include/header');?>
	<title>修改个人资料 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar');?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'修改个人资料','path'=>[['修改个人资料','',1]]]);?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();' value="<?=$info['user_name'];?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label for="realName">昵称</label>
					<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#oldPwd").focus();' value="<?=$info['nick_name'];?>">
				</div>
				<br>
				<div class="form-group">
					<label for="phone">手机号</label>
					<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();' value="<?=$info['phone'];?>">
				</div>
				<br>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input type="email" class="form-control" id="email" value="<?=$info['email'];?>" onkeyup='if(event.keyCode==13)updateProfile();'>
				</div>

				<hr>

				<div class="form-group">
					<label>注册时间</label>
					<input class="form-control" value="<?=$info['create_time'];?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label>最后修改时间</label>
					<input class="form-control" value="<?=$info['update_time'];?>" disabled>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='updateProfile()'>确 认 修 改 用 户 &gt;</button>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
function updateProfile(){
	lockScreen();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	phone=$("#phone").val();
	email=$("#email").val();

	if(userName==""){
		unlockScreen();
		showModalTips("请输入用户名！");
		return false;
	}
	if(userName.length<1 || userName.length>20){
		unlockScreen();
		showModalTips("请输入 1-20字 的用户名！");
		return false;
	}
	if(nickName==""){
		unlockScreen();
		showModalTips("请输入昵称！");
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
		url:"./toUpdateProfile",
		type:"post",
		data:{"userName":userName,"nickName":nickName,"phone":phone,"email":email},
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
				alert("修改成功！");
				history.go(-1);
				return true;
			}else if(ret.code==1){
				showModalTips("修改失败！！！");
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

</body>
</html>
