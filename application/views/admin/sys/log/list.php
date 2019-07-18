<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-操作记录管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-27
 * @version 2019-06-12
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>操作记录管理 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'操作记录管理','path'=>[['操作记录管理','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<button class="btn btn-danger btn-block" onclick="truncate_ready();">清 空 操 作 记 录</button>

		<hr>

		<div class="panel panel-default">
			<div class="panel-body">
				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>类型</th>
							<th>内容</th>
							<th>用户名</th>
							<th>时间</th>
							<th>IP</th>
						</tr>
					</thead>

					<tbody>
					<?php foreach($list as $info){ ?>
						<tr>
							<td><?=$info['type']; ?></td>
							<td><?=$info['content']; ?></td>
							<td><?=$info['user_name']; ?></td>
							<td><?=$info['create_time']; ?></td>
							<td><?=$info['create_ip']; ?></td>
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

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
window.onload=function(){
	$('#table').DataTable({
		responsive: true,
		"order":[[3,'desc']],
	});
};

function truncate_ready(){
	$("#truncatePwd").val("");
	$("#truncateModal").modal('show');
}


function truncate_sure(){
	lockScreen();
	pwd=$("#truncatePwd").val();

	if(pwd.length<6){
		unlockScreen();
		$("#truncateModal").modal('hide');
		showModalTips("请输入当前用户的密码！！");
		return false;
	}

	$.ajax({
		url:"./toTruncate",
		type:"post",
		dataType:"json",
		data:{"pwd":pwd},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#truncateModal").modal('hide');
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				$("#truncateModal").modal('hide');
				alert("清空成功！");
				location.reload();
				return true;
			}else if(ret.code==500){
				$("#truncateModal").modal('hide');
				showModalTips("清空失败！！！");
				return false;
			}else if(ret.code==403){
				$("#truncateModal").modal('hide');
				showModalTips("密码错误！");
				return false;
			}else{
				$("#truncateModal").modal('hide');
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

<div class="modal fade" id="truncateModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要清空系统日志吗？</font>
				<br>
				<font color="blue" style="font-weight:bolder;font-size:20px;">若确定，请输入您的登录密码以确认身份</font>
				<hr>
				<input type="password" class="form-control" id="truncatePwd" onkeyup='if(event.keyCode==13)truncate_sure();'>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 取消</button> <button type="button" class="btn btn-danger" onclick="truncate_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
