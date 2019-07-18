<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-空白示例页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-15
 * @version 2019-03-17
 */ 
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title><?=$this->setting->get('systemName'); ?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'空白示例页','path'=>[['空白示例页','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				在此显示文字或其他内容
			</div>
		</div>
 	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>
</body>
</html>
