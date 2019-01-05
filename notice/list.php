<?php
/**
 * @name 生蚝科技统一身份认证平台-通知列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-03
 */

require_once '../include/public.func.php';

checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
	<title>通知列表 / 生蚝科技统一身份认证平台</title>
	<?php include '../include/header.php'; ?>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<?php include '../include/navbar.php'; ?>

<!-- 页面内容 -->
<div class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>通知列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=ROOT_PATH;?>dashborad.php"><i class="fa fa-dashboard"></i> ITR用户中心</a></li>
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
					<tbody id="noticeTableList"></tbody>
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
getNotice();

function getNotice(){
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

				for(info in list){
					id=list[info]['id'];
					title=list[info]['title'];
					publisher=list[info]['publisher'];
					createTime=list[info]['create_time'];

					html='<tr>'
					    +'<td>'+title+'</td>'
					    +'<td>'+publisher+'</td>'
					    +'<td>'+createTime+'</td>'
					    +'<td><a href="detail.php?id='+id+'" class="btn btn-info">详细</a></td>'
					    +'</tr>';

					$("#noticeTableList").append(html);
				}

				$('#noticeTable').DataTable({
					responsive: true,
					"order":[[2,'desc']],
					"columnDefs":[{
						"targets":[3],
						"orderable": false
					}]
				});
			}
		}
	});
}
</script>

</body>
</html>
