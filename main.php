<?php
/**
 * @name 生蚝科技统一身份认证平台-首页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-23
 */

require_once 'include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

$appPermission=PDOQuery($dbcon,"SELECT app_permission FROM user WHERE user_name=?",[$userName],[PDO::PARAM_STR]);
$appPermission=explode(",",$appPermission[0][0]['app_permission']);

$sql="SELECT name,return_url,status,is_show FROM app WHERE 1=1 ";
foreach($appPermission as $appId){
	$sql.="OR id='{$appId}' ";
}
$appInfoQuery=PDOQuery($dbcon,$sql);
$appInfos=$appInfoQuery[0];

$logQuery=PDOQuery($dbcon,"SELECT * FROM log WHERE user_id=?",[getSess("user_id")],[PDO::PARAM_INT]);
$logInfos=$logQuery[0];
?>
<html>
<head>
	<title>首页 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="padding:68px 0 0 20px;">

<?php include 'include/navbar.php'; ?>

<h1 class="page-header">首页</h1>
<h3 class="sub-header">已开通统一身份认证的部分应用</h3>
<div class="row placeholders">
	<?php foreach($appInfos as $appInfo){ ?>
	<div class="col-xs-5 col-sm-2 placeholder">
		<h4><?=$appInfo['name'];?></h4>
		<a href="<?=$appInfo['return_url']."?token=".getSess('token');?>" target="_blank" class="btn btn-primary">进入 >> Entrance</a>
	</div>
	<?php } ?>
</div>

<hr>

<h3 class="sub-header">最近的登录活动（首页仅显示10条，更多请点击导航栏"登录记录"）</h3>
<table class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;text-align:center;">
<tr>
	<th>登录方式</th>
	<th>应用名称</th>
	<th>登录时间</th>
	<th>IP地址</th>
</tr>
<?php foreach($logInfos as $info){ ?>
<tr>
	<td><?=$info['method'];?></td>
	<td><?=$info['method'];?></td>
	<td><?=$info['create_time'];?></td>
	<td><?=$info['ip'];?></td>
</tr>
<?php } ?>
</table>

<hr>

<a href="<?=ROOT_PATH;?>logout.php">登出</a><br>

<?php include 'include/footer.php'; ?>

</body>
</html>
