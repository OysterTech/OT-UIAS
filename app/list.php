<?php
/**
 * @name 生蚝科技统一身份认证平台-我的应用列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-04
 */

require_once '../include/public.func.php';

checkLogin();

// 获取用户权限的应用信息
$appPermission=PDOQuery($dbcon,"SELECT app_permission FROM user WHERE union_id=?",[getSess("unionId")],[PDO::PARAM_STR]);
$appPermission=explode(",",$appPermission[0][0]['app_permission']);

$sql="SELECT name,main_page,return_url FROM app WHERE status=1 AND is_show=1 AND (";
foreach($appPermission as $appId){
	$sql.="id='{$appId}' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).")";
$appInfoQuery=PDOQuery($dbcon,$sql);
$appInfos=$appInfoQuery[0];
?>
<!DOCTYPE html>
<html>
<head>
	<title>我的应用列表 / 生蚝科技统一身份认证平台</title>
	<?php include '../include/header.php'; ?>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include '../include/navbar.php'; ?>

<!-- 页面内容 -->
<div class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>我的应用列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">应用</li>
			<li class="active">我的应用列表</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				<table id="table" class="table table-bordered table-hover table-striped">
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
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php include '../include/footer.php'; ?>

</div>
<!-- ./wrapper -->

<script>
window.onload=function(){ 
	$('#table').DataTable({
		responsive: true
	});
};
</script>

</body>
</html>
