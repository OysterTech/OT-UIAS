<?php
/**
 * @name 生蚝科技统一身份认证平台-首页
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-04
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
<div class="content-wrapper">
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
						<ul class="todo-list" id="dashboradNoticeList">
						</ul>
					</div>
				</div>
				<!-- ./通知列表 -->
			</section>
			<!-- ./左侧 -->

			<!-- 右侧 -->
			<section class="col-lg-6 connectedSortable">
				<!-- 积分交易记录列表 -->
				<div class="box box-solid bg-green-gradient">
					<div class="box-header">
						<i class="fa fa-exchange"></i>

						<h3 class="box-title">积分收支记录<small>(仅显示最新10条)</small></h3>
						<!-- tools box -->
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
						<!-- /. tools -->
					</div>
					<!-- /.box-header -->
					<div class="box-footer text-black">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>交易金额</th>
									<th>内容</th>
									<th>时间</th>
								</tr>
							</thead>
							<tbody id="transLogTable"></tbody>
						</table>
						<a href="integral/transactionLogList.php" class="btn btn-info btn-block">查 看 全 部 记 录 &gt;</a>
					</div>
					<!-- ./积分交易记录列表 -->
				</div>
			</section>
			<!-- ./右侧 -->
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
getDashBroadNotice();

function getDashBroadNotice(){
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

				for(info in list){
					id=list[info]['id'];
					title=list[info]['title'];
					createTime=list[info]['create_time'];

					html='<li>'
					    +'<span class="handle"><i class="fa fa-bullhorn"></i></span>'
					    +'<a href="<?=ROOT_PATH;?>notice/detail.php?id='+id+'"><span class="text">'+title+'</span></a>'
					    +'<small class="label label-info"><i class="fa fa-clock-o"></i> '+createTime+'</small>'
					    +'</li>';

					$("#dashboradNoticeList").append(html);
				}
				
				if(list==""){
					html="<li>"
					    +"<font color='blue'><b>暂无公告！</b></font>"
					    +"</li>";
					$("#dashboradNoticeList").append(html);
				}

			}else{
				showModalTips("系统错误！"+ret.code);
				console.log(ret);
				return false;
			}
		}
	});
}
</script>

</body>
</html>
