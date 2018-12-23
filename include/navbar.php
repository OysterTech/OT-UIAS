<?php
function getGreeting(){
	$nowHour=date("H");
	if($nowHour>=0 && $nowHour<6) $ret="凌晨好！";
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
			<li class="active"><a href="#">首页</a></li>
			<li><a href="<?=ROOT_PATH;?>notice/toList.php"><i class="fa fa-bullhorn" aria-hidden="true"></i> 通知中心</a></li>
			<li><a href="<?=ROOT_PATH;?>app/toList.php"><i class="fa fa-window-restore" aria-hidden="true"></i> 应用列表</a></li>
			<li><a href="<?=ROOT_PATH;?>log/toUserList.php"><i class="fa fa-list" aria-hidden="true"></i> 登录记录</a></li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code" aria-hidden="true"></i> 开发者中心 <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<?php if($role<1){ ?>
					<li><a href="<?=ROOT_PATH;?>developer/joinIn.php"><i class="fa fa-check-circle-o" aria-hidden="true"></i> 申请加入</a></li>
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
					<li><a href="<?=ROOT_PATH;?>admin/app/toList.php"><i class="fa fa-window-restore" aria-hidden="true"></i> 所有应用</a></li>
					<li><a href="<?=ROOT_PATH;?>admin/log/toList.php"><i class="fa fa-list-alt" aria-hidden="true"></i> 操作记录列表</a></li>
				</ul>
			</li>
			<?php } ?>
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle" aria-hidden="true"></i> <?=getSess("nickName");?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="javascript:void(0)"><font color="green"><b><?=getSess("nickName");?></b></font>，<?=getGreeting(); ?></a></li>
					<li><a href="javascript:void(0)">角色：<font color="#F57C00"><b><?=$roleName;?></b></font></a></li>
					<li class="divider"></li>
					<li><a href="<?=ROOT_PATH;?>profile/index.php"><i class="fa fa-user" aria-hidden="true"></i> 个人中心</a></li>
					<li><a href="<?=ROOT_PATH;?>profile/toChangePassword.php"><i class="fa fa-key" aria-hidden="true"></i> 修改密码</a></li>
					<li><a href="<?=ROOT_PATH;?>safe/index.php"><i class="fa fa-shield" aria-hidden="true"></i> 安全防护中心</a></li>
					<li class="divider"></li>
					<li><a onclick="logout();"><i class="fa fa-sign-out" aria-hidden="true"></i> 登出</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>

<script>
function logout(){
	if(confirm("确认要退出统一认证平台吗？\n退出后将会登出所有系统！")){
		window.location.href="logout.php";
	}
}
</script>
