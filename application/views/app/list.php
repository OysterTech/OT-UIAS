<?php
/**
 * @name 生蚝科技统一身份认证平台-V-我的应用列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-07-18
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>我的应用列表 / <?=$this->setting->get('systemName');?></title>
	<?php $this->load->view('include/header'); ?>
</head>
<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>我的应用列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
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
							<td><a href="<?=$appInfo['redirect_url']."?token=".$this->session->userdata($this->sessPrefix.'token');?>" target="_blank" class="btn btn-success">进入</a></td>
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

<?php $this->load->view('include/footer'); ?>

</div>
<!-- ./wrapper -->

<script>
window.onload=function(){ 
	$('#table').DataTable({
		responsive: true,
		"columnDefs":[{
			"targets":[1,2],
			"orderable": false
		}]
	});
};
</script>

</body>
</html>
