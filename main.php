<?php
/**
 * @name 生蚝科技统一身份认证平台-平台首页
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-11-30
 * @version 2018-12-28
 */

require_once 'include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

// 查询登录记录
$logQuery=PDOQuery($dbcon,"SELECT * FROM log WHERE user_id=? AND content LIKE '登录-%' ORDER BY create_time DESC LIMIT 0,10",[getSess("user_id")],[PDO::PARAM_INT]);
$logInfos=$logQuery[0];
?>
<html>
<head>
	<title>首页 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
	<style>
	.circle{
		height: 100px;
		width: 100px;
		display: table-cell;
		text-align: center;
		vertical-align: middle;
		border-radius: 50%;
		background: #E1F5FE;
		color:#BA68C8;
	}
	td,th{
		text-align:center;
		vertical-align:middle;
	}
	</style>
</head>
<body style="padding:68px 0 0 10px;">

<?php include 'include/navbar.php'; ?>

<ol class="breadcrumb">
	<li class="active">统一认证平台用户中心</li>
	<li class="active">首页</li>
</ol>

<table style="border-collapse:separate; border-spacing:10px 10px;">
<tr>
	<td>
		<a href="app/list.php"><div class="circle">
			<i class="fa fa-3x fa-server" aria-hidden="true"></i><br>应用列表
		</div></a>
	</td>
	<td>
		<a href="notice/list.php"><div class="circle">
			<i class="fa fa-3x fa-bullhorn" aria-hidden="true"></i><br>通知中心
		</div></a>
	</td>
</tr>
<tr>
	<td>
		<a onclick="changePassword();"><div class="circle">
			<i class="fa fa-3x fa-key" aria-hidden="true"></i><br>修改密码
		</div></a>
	</td>
	<td>
		<div class="circle" style="background-color:#E0E0E0;color:black;">
			<i class="fa fa-3x fa-shield" aria-hidden="true"></i><br>保密管理
		</div>
	</td>
</tr>
</table>

<hr>

<h3 class="sub-header">最近的登录活动（首页仅显示10条，更多请点击导航栏"<a href="log/list.php">登录记录</a>"）</h3>
<table class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;text-align:center;">
<tr>
	<th>登录方式</th>
	<th>应用名称</th>
	<th>登录时间</th>
</tr>
<?php
foreach($logInfos as $info){
	$appInfoQuery=PDOQuery($dbcon,"SELECT name FROM app WHERE id=?",[$info['app_id']],[PDO::PARAM_STR]);
	if($info['app_id']==0) $appName="统一身份认证平台";
	elseif($appInfoQuery[1]!=1) $appName="应用已注销";
	else $appName=$appInfoQuery[0][0]['name'];
?>
<tr>
	<td><?=$info['method'];?></td>
	<td><?=$appName;?></td>
	<td><?=$info['create_time'];?></td>
</tr>
<?php } ?>
</table>

<?php include 'include/footer.php'; ?>

</body>
</html>
