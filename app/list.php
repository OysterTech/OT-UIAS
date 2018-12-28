<?php
/**
 * @name 生蚝科技统一身份认证平台-我的应用列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-27
 * @version 2018-12-27
 */

require_once '../include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

// 获取用户权限的应用信息
$appPermission=PDOQuery($dbcon,"SELECT app_permission FROM user WHERE user_name=?",[$userName],[PDO::PARAM_STR]);
$appPermission=explode(",",$appPermission[0][0]['app_permission']);

$sql="SELECT name,main_page,return_url FROM app WHERE status=1 AND is_show=1 ";
foreach($appPermission as $appId){
	$sql.="OR id='{$appId}' ";
}
$appInfoQuery=PDOQuery($dbcon,$sql);
$appInfos=$appInfoQuery[0];
?>
<html>
<head>
	<title>应用列表 / 生蚝科技统一身份认证平台</title>
	<?php include '../include/header.php'; ?>
	<link rel="stylesheet" href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" src="https://www.xshgzs.com/yc/cdms/resource/css/dataTables.responsive.css">
	<style>
	td,th{
		text-align:center;
		vertical-align:middle;
	}
	</style>
</head>
<body style="padding:68px 0 0 10px;">

<?php include '../include/navbar.php'; ?>

<ol class="breadcrumb">
	<li><a href="<?=ROOT_PATH;?>">统一认证平台用户中心</a></li>
	<li class="active">应用列表</li>
</ol>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;text-align:center;">
<thead>
<tr>
	<th>应用名</th>
	<th>首页</th>
	<th>登录</th>
</tr>
</thead>
<tbody>
<?php foreach($appInfos as $appInfo){ ?>
<tr>
	<td><?=$appInfo['name'];?></td>
	<td><a href="<?=$appInfo['main_page'];?>" target="_blank" class="btn btn-primary">访问</a></td>
	<td><a href="<?=$appInfo['return_url']."?token=".getSess('token');?>" target="_blank" class="btn btn-success">进入</a></td>
</tr>
<?php } ?>
</tbody>
</table>

<?php include '../include/footer.php'; ?>

<script>
window.onload=function(){ 
	$('#table').DataTable({
		responsive: true
	});
};
</script>

</body>
</html>
