<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-角色列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-09
 * @version 2019-07-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>角色列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'角色列表','path'=>[['角色列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a href="<?=base_url('admin/role/add');?>" class="btn btn-primary btn-block">新 增 角 色</a>

		<hr>

		<div class="panel panel-default">
			<div class="panel-body">
				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>角色名称</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody></tbody>
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
let table;

var vm = new Vue({
	el:'#app',
	data:{
		deleteId:0
	},
	methods:{
		getList:()=>{
			$.ajax({
				url:headerVm.apiPath+"role/get",
				dataType:'json',
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						table=$('#table').DataTable({
							responsive: true,
							"columnDefs":[{
								"targets":[1],
								"orderable": false
							}]
						});

						for(i in list){
							let operateHtml=''
							               +'<a href="'+headerVm.rootUrl+'admin/role/edit?id='+list[i]['id']+'&name='+list[i]['name']+'" class="btn btn-info">编辑</a> '
							               +"<a onclick='vm.del_ready("+'"'+list[i]['id']+'","'+list[i]['name']+'"'+")' class='btn btn-danger'>删除</a> "
							               +'<a href="'+headerVm.rootUrl+'admin/role/setPermission?id='+list[i]['id']+'&name='+list[i]['name']+'" class="btn btn-success">分配权限</a> ';

							operateHtml=list[i]['is_default']!=0?operateHtml:operateHtml+"<a onclick='vm.setDefaultRole("+'"'+list[i]['id']+'"'+")' class='btn btn-primary'>设为默认角色</a> ";

							table.row.add({
								0: list[i]['name'],
								1: operateHtml
							}).draw();
						}
					}
				}
			})
		},
		del_ready:(id,name)=>{
			vm.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		del_sure:()=>{
			lockScreen();
			$.ajax({
				url:"./toDelete",
				type:"post",
				dataType:"json",
				data:{"id":vm.deleteId},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						$("#delModal").modal('hide');
						alert("删除成功！");
						location.reload();
						return true;
					}else if(ret.code==1){
						$("#delModal").modal('hide');
						showModalTips("删除失败！！！");
						return false;
					}else{
						$("#delModal").modal('hide');
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		setDefaultRole:(id)=>{
			lockScreen();

			$.ajax({
				url:"./toSetDefaultRole",
				type:"post",
				dataType:"json",
				data:{"id":id},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						alert("设置成功！");
						location.reload();
						return true;
					}else if(ret.code==1){
						showModalTips("设置失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		}
	},
	mounted:function(){
		this.getList();
	}
});
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列角色吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm.del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
