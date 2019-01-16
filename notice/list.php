<?php
/**
 * @name 生蚝科技统一身份认证平台-通知列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-12
 */

require_once '../include/public.func.php';

checkAuth();
?>
<!DOCTYPE html>
<html>
<head>
	<title>通知列表 / 生蚝科技用户中心</title>
	<?php include '../include/header.php'; ?>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include '../include/navbar.php'; ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>通知列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">通知</li>
			<li class="active">通知列表</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				<table id="noticeTable" class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>标题</th>
							<th>发布人</th>
							<th>发布时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="Object.keys(noticeList).length===0">
							<th colspan='5' style='color:blue'>暂 无 交 易 记 录！</th>
						</tr>
						<tr v-for="info in noticeList">
							<td>{{info['title']}}</td>
							<td>{{info['publisher']}}</td>
							<td>{{info['create_time']}}</td>
							<td><a v-bind:href="['detail.php?id='+info['id']]" class="btn btn-info">详细</a></td>
						</tr>
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
var vm = new Vue({
	el:'#app',
	data:{
		noticeList:{}
	},
	methods:{
		getNotice:function(){
			lockScreen();
			$.ajax({
				url:"<?=API_PATH;?>notice/get.php",
				data:{"type":"list"},
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

vm.getNotice();

window.onload=function(){ 
	$('table').DataTable({
		responsive: true,
		"order":[[2,'desc']],
		"columnDefs":[{
			"targets":[3],
			"orderable": false
		}]
	});
};
</script>

</body>
</html>
