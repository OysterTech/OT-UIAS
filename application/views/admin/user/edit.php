<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-修改用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-07-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>修改用户 / <?=$this->setting->get('systemName');?></title>
	<script src="<?=base_url('resource/js/select.js');?>"></script>
	<link rel="stylesheet" href="<?=base_url('resource/css/select.css');?>">
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'修改用户','path'=>[['用户列表',base_url('admin/user/list')],['修改用户','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<input type="hidden" id="userId" value="<?=$userId; ?>">

		<div class="panel panel-default">
			<div class="panel-heading">修改用户</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();' value="<?=$info['user_name']; ?>">
					<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
				</div>
				<br>
				<div class="form-group">
					<label for="nickName">昵称</label>
					<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#phone").focus();' value="<?=$info['nick_name']; ?>">
				</div>
				<br>
				<div class="form-group">
					<label for="phone">手机号</label>
					<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();' value="<?=$info['phone']; ?>">
					<p class="help-block">目前仅支持中国大陆的手机号码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input type="email" class="form-control" id="email" value="<?=$info['email']; ?>">
				</div>
				<br>
				<div class="form-group">
					<label>角色</label>
					<div class="mainSelect">
						<div id="roleSelect"></div>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label>注册时间</label>
					<input class="form-control" value="<?=$info['create_time']; ?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label>最后修改时间</label>
					<input class="form-control" value="<?=$info['update_time']; ?>" disabled>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='edit()'>确 认 修 改 用 户 &gt;</button>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var vm = new Vue({
	el:'#app',
	data:{
		nowRoleId:"<?=$info['role_id']; ?>".split(','),
		roleList:[{
			"name": "全选",
			"list": []
		}],
		option:{
			el: 'roleSelect',//容器名称
			type: 'more',//插件类型
			width: $("#email").css('width'),//内容显示宽度
			height: '40px',//内容显示高度
			background: '#FFFFFF',//默认背景色
			color: '#000000',//默认字体颜色
			selectBackground: '#337AB7',//选中背景色
			selectColor: '#FFFFFF',//选中字体颜色
			show: 'false',//是否展开
			content: '请选择用户角色',//要显示的内容
		}
	},
	methods:{
		getAllRole:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"role/get",
				dataType:'json',
				error:function(e){
					console.log(JSON.stringify(e));
					unlockScreen();
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						for(i in ret.data['list']){
							pushData={"optionName":ret.data['list'][i]['name'],"optionId":ret.data['list'][i]['id']};
							$.inArray(ret.data['list'][i]['id'],vm.nowRoleId)>=0 ? pushData.selected=true : '';
							vm.roleList[0].list.push(pushData);
						}

						vm.option.data=vm.roleList;
						selectTool.initialize(vm.option);
						$("#maincontent").attr('style','width:'+$("#email").css('width'));
						$("#maincontent").hide();
						return true;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		}
	}
});

vm.getAllRole();

function edit(){
	lockScreen();
	userId=$("#userId").val();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	phone=$("#phone").val();
	email=$("#email").val();
	roleId=$("#roleSelect #selectValue").val();

	if(userName==""){
		unlockScreen();
		showModalTips("请输入用户名！");
		return false;
	}
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		showModalTips("请输入 4-20字 的用户名！");
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
	if(roleId.length<6){
		unlockScreen();
		showModalTips("请选择角色！");
		return false;
	}

	$.ajax({
		url:"<?=base_url('admin/user/toEdit'); ?>",
		type:"post",
		data:{'userId':userId,"userName":userName,"nickName":nickName,"phone":phone,"email":email,"roleId":roleId},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
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
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>
</body>
</html>
