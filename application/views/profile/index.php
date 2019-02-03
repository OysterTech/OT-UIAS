<?php
/**
 * @name 生蚝科技统一身份认证平台-我的资料
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2019-01-05
 * @version 2019-01-20
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>我的资料 / <?=$this->setting->get('systemName');?></title>
	<?php $this->load->view('include/header'); ?>
</head>
<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>我的资料</h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">资料</li>
			<li class="active">我的资料</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				
				<div class="row">
					<div class="form-group">
						<label for="userName" class="col-sm-2 col-lg-2 control-label">通行证登录用户名 / UserName</label>
						<div class="col-sm-10 col-lg-10">
							<input class="form-control" ref="userName" v-bind:value="userInfo['userName']">
						</div>
					</div>
				</div><br>
				
				<div class="row">
					<div class="form-group">
						<label for="nickName" class="col-sm-2 col-lg-2 control-label">通行证昵称 / NickName</label>
						<div class="col-sm-10 col-lg-10">
							<input class="form-control" ref="nickName" v-bind:value="userInfo['nickName']">
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">
						<label for="phone" class="col-sm-2 col-lg-2 control-label">手机号码 / Phone</label>
						<div class="col-sm-10 col-lg-10">
							<input type="number" class="form-control" ref="phone" v-bind:value="userInfo['phone']">
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">
						<label for="email" class="col-sm-2 col-lg-2 control-label">邮箱地址 / Email</label>
						<div class="col-sm-10 col-lg-10">
							<input type="email" class="form-control" ref="email" v-bind:value="userInfo['email']">
						</div>
					</div>
				</div>

				<hr>
				
				<button class="btn btn-primary" style="width:48%" v-on:click="cancel">&lt; 取 消 操 作</button> <button class="btn btn-success" style="width:48%" v-on:click="updateUserInfo">确 认 修 改 &gt;</button>
				
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

</div>
<!-- ./wrapper -->

<script>
function testUserName(txt){
	r=new RegExp("[\\u4E00-\\u9FFF]+","g");
	if(r.test(txt)){
		return false;
	}else{
		r=new RegExp(/^[\w]+$/);
		if(!r.test(txt)){
			return false;
		}else{
			return true;
		}
	}
}

var vm = new Vue({
	el:'#app',
	data:{
		userInfo:{}
	},
	methods:{
		cancel:function(){
			history.go(-1);
		},
		getUserInfo:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"user/getUserInfo",
				type:"post",
				data:{"method":"unionId","unionId":headerVm.unionId},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						vm.userInfo=ret.data['userInfo'];
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		updateUserInfo:function(){
			lockScreen();
			userInfo_old=this.userInfo;
			userName=this.$refs.userName.value;
			nickName=this.$refs.nickName.value;
			phone=this.$refs.phone.value;
			email=this.$refs.email.value;
			
			if(userName==userInfo_old['userName']&&nickName==userInfo_old['nickName']&&phone==userInfo_old['phone']&&email==userInfo_old['email']){
				unlockScreen();
				showModalTips("请修改需要修改的信息！");
				return false;			
			}
			if(userName.length<5 || userName.length>20){
				unlockScreen();
				showModalTips("请输入5~20位的用户名");
				return false;
			}
			if(testUserName(userName)!==true){
				unlockScreen();
				showModalTips("用户名仅许含有<br>数字字母下划线");
				return false;
			}
			if(nickName.length<3 || nickName.length>20){
				unlockScreen();
				showModalTips("请输入3~20位的昵称");
				return false;
			}
			if(!(/^1[3|4|5|6|7|8|9][0-9]\d{4,8}$/.test(phone))){
				unlockScreen();
				showModalTips("请正确输入中国大陆手机号码！");
				return false;
			}
			if(!(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test(email))){
				unlockScreen();
				showModalTips("请正确输入邮箱！");
				return false;
			}
			
			$.ajax({
				url:headerVm.apiPath+"user/updateUserInfo",
				type:"post",
				data:{"userName":userName,"nickName":nickName,"phone":phone,"email":email},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						alert("修改成功！");
						history.go(-1);
					}
				}
			});
		}
	}
});

vm.getUserInfo();
</script>

</body>
</html>
