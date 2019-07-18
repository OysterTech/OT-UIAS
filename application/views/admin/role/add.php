<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-新增角色
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-09
 * @version 2019-06-12
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>新增角色 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'新增角色','path'=>[['角色列表',base_url('admin/role/list')],['新增角色','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="panel panel-default">
			<div class="panel-heading">新增角色</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="name">角色名称</label>
					<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#remark").focus();'>
					<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的角色名称</p>
				</div>
				<br>
				<div class="form-group">
					<label for="remark">备注</label>
					<textarea class="form-control" id="remark"></textarea>
					<p class="help-block">选填</p>
				</div>
				<hr>
				<button class="btn btn-success btn-block" onclick='add()'>确 认 新 增 角 色 &gt;</button>
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
function add(){
	lockScreen();
	name=$("#name").val();
	remark=$("#remark").val();

	if(name==""){
		unlockScreen();
		$("#tips").html("请输入角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(name.length<1 || name.length>20){
		unlockScreen();
		$("#tips").html("请输入 1-20字 的角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"./toAdd",
		type:"post",
		data:{"name":name,"remark":remark},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("新增成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="insertFailed"){
				$("#tips").html("新增失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				$("#tips").html("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				$("#tipsModal").modal('show');
				return false;
			}
		}
	});
}
</script>

</body>
</html>
