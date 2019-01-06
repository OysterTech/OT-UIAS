<?php
/**
 * @name 生蚝科技统一身份认证平台-首页
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-06
 */

require_once 'include/public.func.php';

checkLogin();

?>
<!DOCTYPE html>
<html>
<head>
	<title>首页 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include 'include/navbar.php'; ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>生蚝科技统一身份认证平台<small>首页</small></h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">首页</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<!-- Main row -->
		<div class="row">
			<!-- 左侧 -->
			<section class="col-lg-6 connectedSortable">
				<!-- 通知列表 -->
				<div class="box box-primary">
					<div class="box-header">
						<i class="fa fa-list-alt"></i>
						<h3 class="box-title">通知列表</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="todo-list">
							<li v-if="noticeList==={}"><font color='blue'><b>暂无公告！</b></font></li>
							<li v-else v-for="noticeInfo in noticeList">
								<span class="handle"><i class="fa fa-bullhorn"></i></span>
								<a v-bind:href="['<?=ROOT_PATH;?>notice/detail.php?id='+noticeInfo['id']]"><span class="text">{{noticeInfo['title']}}</span></a>
								<small class="label label-info"><i class="fa fa-clock-o"></i> {{noticeInfo['create_time']}}</small>
							</li>
						</ul>
					</div>
				</div>
				<!-- ./通知列表 -->
			</section>
			<!-- ./左侧 -->
		</div>
		<!-- /.row (main row) -->
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php include 'include/footer.php'; ?>

</div>
<!-- ./wrapper -->

<script>
var vm = new Vue({
	el:'#app',
	data:{
		noticeList:{}
	},
	methods:{
		getDashBroadNotice:function(){
			lockScreen();
			$.ajax({
				url:"<?=API_PATH;?>notice/get.php",
				data:{"type":"dashborad"},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						list=ret.data['list'];
						vm.noticeList=list;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		}
	}
});

vm.getDashBroadNotice();
</script>

</body>
</html>
