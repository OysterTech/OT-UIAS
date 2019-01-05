<?php
/**
 * @name 生蚝科技统一身份认证平台-登录记录
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2018-12-31
 */

require_once '../include/public.func.php';

checkLogin();

// 查询登录记录
$logQuery=PDOQuery($dbcon,"SELECT * FROM log WHERE user_id=? AND content LIKE '登录-%'",[getSess("user_id")],[PDO::PARAM_INT]);
$logInfos=$logQuery[0];
?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../include/header.php'; ?>
	<title>登录记录 / 生蚝科技统一身份认证平台</title>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include '../include/navbar.php'; ?>

<!-- 页面内容 -->
<div class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>登录记录</h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">应用</li>
			<li class="active">登录记录</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				<table id="table" class="table table-bordered table-hover table-striped">
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
		responsive: true,
		"order":[[2,'desc']]
	});
};
</script>

</body>
</html>
