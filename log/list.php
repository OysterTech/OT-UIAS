<?php
/**
 * @name 生蚝科技统一身份认证平台-登录记录
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-27
 * @version 2018-12-27
 */

require_once '../include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

// 查询登录记录
$logQuery=PDOQuery($dbcon,"SELECT * FROM log WHERE user_id=? AND content LIKE '登录-%'",[getSess("user_id")],[PDO::PARAM_INT]);
$logInfos=$logQuery[0];
?>
<html>
<head>
	<title>登录记录 / 生蚝科技统一身份认证平台</title>
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
	<li class="active">登录记录</li>
</ol>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;text-align:center;">
<thead>
<tr>
	<th>方式</th>
	<th>应用名</th>
	<th>登录时</th>
	<th>IP</th>
</tr>
</thead>
<tbody>
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
	<td><?=$info['ip'];?></td>
</tr>
<?php } ?>
</tbody>
</table>

<?php include '../include/footer.php'; ?>

<script>
window.onload=function(){ 
	$('#table').DataTable({
		responsive: true,
		"order":[[2,'desc']]
	});
};
</script>

</body>
</html>
