<?php
/**
 * @name 生蚝科技统一身份认证平台-我的资料
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2019-01-05
 * @version 2019-01-05
 */

require_once '../include/public.func.php';

checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../include/header.php'; ?>
	<title>我的资料 / 生蚝科技统一身份认证平台</title>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include '../include/navbar.php'; ?>

<!-- 页面内容 -->
<div class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>我的资料</h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">资料</li>
			<li class="active">我的资料</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				
				<div class="form-group">
					<label for="userName" class="col-sm-2 control-label">通行证登录用户名 / UserName</label>
					<div class="col-sm-10">
						<input class="form-control" id="userName" placeholder="">
					</div>
				</div>
				
				<div class="form-group">
					<label for="nickName" class="col-sm-2 control-label">通行证昵称 / NickName</label>
					<div class="col-sm-10">
						<input class="form-control" id="nickName" placeholder="">
					</div>
				</div>
				
				<div class="form-group">
					<label for="phone" class="col-sm-2 control-label">手机号码 / Phone</label>
					<div class="col-sm-10">
						<input type="number" class="form-control" id="phone" placeholder="">
					</div>
				</div>
				
				<div class="form-group">
					<label for="email" class="col-sm-2 control-label">邮箱地址 / Email</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="email" placeholder="">
					</div>
				</div>
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
