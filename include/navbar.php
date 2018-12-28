<?php
/**
 * @name 生蚝科技统一身份认证平台-平台导航栏
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-22
 * @version 2018-12-28
 */
 
function getGreeting(){
	$nowHour=date("H");
	if($nowHour>=0 && $nowHour<6) $ret="晚安！";
	elseif($nowHour>=6 && $nowHour<10) $ret="早安！";
	elseif($nowHour>=10 && $nowHour<12) $ret="上午好！";
	elseif($nowHour>=12 && $nowHour<15) $ret="中午好！";
	elseif($nowHour>=15 && $nowHour<17) $ret="下午好！";
	elseif($nowHour>=17 && $nowHour<20) $ret="傍晚好！";
	elseif($nowHour>=20 && $nowHour<24) $ret="晚上好！";

	return $ret;
}

$role=getSess("role");
switch($role){
	case "0":
		$roleName="普通会员";
		break;
	case "1":
		$roleName="开发者";
		break;
	case "2":
		$roleName="管理员";
		break;
}

?>
<nav class="navbar navbar-default navbar-fixed-top"> 
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=ROOT_PATH;?>main.php">生蚝科技统一身份认证平台</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?=ROOT_PATH;?>">首页</a></li>
				<li><a href="<?=ROOT_PATH;?>notice/list.php"><i class="fa fa-bullhorn" aria-hidden="true"></i> 通知中心</a></li>
				<li><a href="<?=ROOT_PATH;?>app/list.php"><i class="fa fa-window-restore" aria-hidden="true"></i> 应用列表</a></li>
				<li><a href="<?=ROOT_PATH;?>log/list.php"><i class="fa fa-list" aria-hidden="true"></i> 登录记录</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code" aria-hidden="true"></i> 开发者中心 <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php if($role<1){ ?>
						<li><!--a href="<?=ROOT_PATH;?>developer/joinIn.php"><i class="fa fa-check-circle-o" aria-hidden="true"></i> 申请加入</a--><a onclick="alert('暂未对外开放申请！敬请期待！');"><i class="fa fa-check-circle-o" aria-hidden="true"></i> 申请加入(暂未开放)</a></li>
						<?php }else{ ?>
						<li><a href="<?=ROOT_PATH;?>developer/toMyAppList.php"><i class="fa fa-list-alt" aria-hidden="true"></i> 我的应用列表</a></li>
						<li><a href="<?=ROOT_PATH;?>developer/help.php"><i class="fa fa-info-circle" aria-hidden="true"></i> 帮助中心</a></li>
						<?php } ?>
					</ul>
				</li>
				<?php if($role>=2){ ?>			
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lock" aria-hidden="true"></i> 管理后台 <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=ROOT_PATH;?>admin/user/list.php"><i class="fa fa-users" aria-hidden="true"></i> 用户列表</a></li>
						<li><a href="<?=ROOT_PATH;?>admin/app/list.php"><i class="fa fa-window-restore" aria-hidden="true"></i> 所有应用</a></li>
						<li><a href="<?=ROOT_PATH;?>admin/log/list.php"><i class="fa fa-list-alt" aria-hidden="true"></i> 操作记录列表</a></li>
					</ul>
				</li>
				<?php } ?>
			</ul>
		
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle" aria-hidden="true"> </i><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0)"><font color="green"><b><?=getSess("nickName");?></b></font>，<?=getGreeting(); ?></a></li>
						<li><a href="javascript:void(0)">角色：<font color="#F57C00"><b><?=$roleName;?></b></font></a></li>
						<li class="divider"></li>
						<li><a href="<?=ROOT_PATH;?>profile/index.php"><i class="fa fa-user" aria-hidden="true"></i> 个人中心</a></li>
						<li><a onclick="changePassword();"><i class="fa fa-key" aria-hidden="true"></i> 修改密码</a></li>
						<li><a href="<?=ROOT_PATH;?>safe/index.php"><i class="fa fa-shield" aria-hidden="true"></i> 安全防护中心</a></li>
						<li class="divider"></li>
						<li><a onclick="logout();"><i class="fa fa-sign-out" aria-hidden="true"></i> 登出</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>

<script>
function logout(){
	if(confirm("确认要退出统一认证平台吗？\n退出后将会登出所有系统！")){
		window.location.href="<?=ROOT_PATH;?>logout.php";
	}
}
</script>

<div class="modal fade" id="changePasswordModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">修改密码</h3>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning">
					<center>
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 新密码需包含6~20位的字符！
					</center>
				</div>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-unlock" aria-hidden="true"></i></span>
					<input id="oldPwd" type="password" class="form-control" onkeyup='if(event.keyCode==13)$("#newPwd").focus();' placeholder="请输入原密码">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
					<input id="newPwd" type="password" class="form-control" onkeyup='if(event.keyCode==13)$("#surePwd").focus();' placeholder="请输入新密码">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-shield" aria-hidden="true"></i></span>
					<input id="surePwd" type="password" class="form-control" placeholder="请再次输入新密码">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">&lt; 取消</button> <button type="button" class="btn btn-success" onclick="toChangePassword();">确认修改 &gt;</button>
			</div>
		</div>
	</div>
</div>

<script>
function changePassword(){
	$("#changePasswordModal").modal("show");
}


function toChangePassword(){
	lockScreen();
	oldPwd=$("#oldPwd").val();
	newPwd=$("#newPwd").val();
	surePwd=$("#surePwd").val();

	if(oldPwd==""){
	 unlockScreen();
	 showModalTips("请正确输入旧密码！");
	 return false;
	}
	if(newPwd.length<6 || newPwd.length>20){
	 unlockScreen();
	 showModalTips("请输入6~20位的新密码！");
	 return false;
	}
	if(surePwd!=newPwd){
	 unlockScreen();
	 showModalTips("两次输入的新密码不相符！");
	 return false;
	}
	if(oldPwd==newPwd){
	 unlockScreen();
	 showModalTips("新密码与旧密码相同！");
	 return false;
	}
	
	$.ajax({
		url:"<?=ROOT_PATH;?>profile/toChangePassword.php",
		type:"post",
		dataType:"json",
		data:{"oldPwd":oldPwd,"newPwd":newPwd},
		error:function(e){
			unlockScreen();
			console.log(JSON.stringify(e));
			showModalTips("服务器错误！<br>请将错误码["+e.readyState+"."+e.status+"]至技术支持");
			return false;
		},
		success:function(ret){
			unlockScreen();
		
			if(ret.code==200){
				$("#changePasswordModal").modal("hide");
				showModalTips("修改密码成功！");
				return true;
			}else if(ret.code==403){
				showModalTips("旧密码错误！");
				return true;
			}else if(ret.code==2){
				showModalTips("修改错误！");
				return true;
			}else{
				console.log(JSON.stringify(ret));
				showModalTips("系统错误！<br>请将错误码["+ret.code+"]至技术支持");
				return false;
			}
		}
	});
}
</script>

