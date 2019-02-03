<?php
/**
 * @name 生蚝科技统一身份认证平台-管理-操作记录
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-27
 * @version 2018-12-28
 */

require_once '../../include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

// 查询登录记录
$logQuery=PDOQuery($dbcon,"SELECT a.*,b.user_name FROM log a,user b WHERE a.user_id=b.id");
$logInfos=$logQuery[0];
?>
<html>
<head>
	<title>操作记录管理 / 生蚝科技统一身份认证平台</title>
	<?php include '../../include/header.php'; ?>
	<link rel="stylesheet" href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" src="https://www.xshgzs.com/yc/cdms/resource/css/dataTables.responsive.css">
	<style>
	td,th{
		text-align:center;
		vertical-align:middle;
	}
	</style>
</head>
<body style="padding:68px 0 0 10px;">

<?php include '../../include/navbar.php'; ?>

<ol class="breadcrumb">
	<li><a href="<?=ROOT_PATH;?>">统一认证平台用户中心</a></li>
	<li class="active">管理后台</li>
	<li class="active">操作记录管理</li>
</ol>

<br>

<button onclick="$('#password').val('');$('#authModal').modal('show');" class="btn btn-danger btn-block">清 除 所 有 记 录</button>

<hr>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;text-align:center;">
<thead>
<tr>
	<th>用户名</th>
	<th>应用名</th>
	<th>内容</th>
	<th>时间</th>
	<th>IP</th>
	<!--th>操作</th-->
</tr>
</thead>
<tbody>
<?php
foreach($logInfos as $info){
	$appInfoQuery=PDOQuery($dbcon,"SELECT name FROM app WHERE id=?",[$info['app_id']],[PDO::PARAM_STR]);
	if($info['app_id']==0) $appName="SSO中心";
	elseif($appInfoQuery[1]!=1) $appName="应用已注销";
	else $appName=$appInfoQuery[0][0]['name'];
?>
<tr>
	<td><?=$info['user_name'];?></td>
	<td><?=$appName;?></td>
	<td><?=$info['content'];?></td>
	<td><?=$info['create_time'];?></td>
	<td><?=$info['ip'];?></td>
	<!--td><button class="btn btn-warning" onclick='delete("<?=$info['id'];?>")'>删除</button></td-->
</tr>
<?php } ?>
</tbody>
</table>

<?php include '../../include/footer.php'; ?>

<script>
window.onload=function(){ 
	$('#table').DataTable({
		"order":[[3,'desc']]
		/*"columnDefs":[{
			"targets":[5],
			"orderable": false
		}]*/
	});
};


function toClear(){
	password=$("#password").val();

	if(password==""){
		$("#authModal").modal('hide');
		showModalTips("管理员密码不能为空！<br>记录未被清空！");
		return;
	}

	$.ajax({
		url:"toClear.php",
		type:"post",
		dataType:"json",
		data:{"password":password},
		error:function(e){
			console.log(e);
			$("#authModal").modal('hide');
			showModalTips("服务器错误！"+e.status);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				alert("清空成功！");
				location.reload();
			}else if(ret.code==1){
				$("#authModal").modal('hide');
				showModalTips("清空失败！<br>记录未被清空！");
				return;
			}else if(ret.code==403){
				$("#authModal").modal('hide');
				showModalTips("管理员身份认证失败！<br>记录未被清空！");
				return;
			}else{
				$("#authModal").modal('hide');
				showModalTips("系统错误！<br>请联系管理员并提交错误码["+ret.code+"]<hr>记录未被清空！");
				return;
			}
		}
	})
}
</script>

<div class="modal fade" id="authModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">管理员身份认证</h3>
			</div>
			<div class="modal-body">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
					<input id="password" type="password" class="form-control" placeholder="请输入当前用户密码">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">&lt; 取消</button> <button type="button" class="btn btn-success" onclick="toClear();">确认清空 &gt;</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
