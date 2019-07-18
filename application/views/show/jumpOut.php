<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-页面跳转
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
	<?php $this->load->view('include/pagePath',['name'=>($name!=""?$name:"页面跳转"),'path'=>[['页面跳转','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a href="<?=$url;?>" target="_blank" class="btn btn-success btn-block" style="font-size:20px;font-weight:bold;"><i class="fa fa-link" aria-hidden="true"></i> 点 此 手 动 跳 转</a>
		<br>
		<a onclick="history.go(-1);" class="btn btn-primary btn-block" style="font-size:20px;font-weight:bold;"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> 返 回 上 一 页</a>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var url="<?=$url;?>";
lockScreen("跳转中，请稍候...");
setTimeout(function(){
	window.open(url);
	unlockScreen();
	history.go(-1);
},2000);
</script>
</body>
</html>
