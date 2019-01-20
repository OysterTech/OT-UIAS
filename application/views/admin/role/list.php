<?php
/**
 * @name 生蚝科技统一身份认证平台-角色列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2019-01-20
 * @version 2019-01-20
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>角色列表 / <?=$this->setting->get('systemName');?></title>
	<?php $this->load->view('include/header'); ?>
	<link rel="stylesheet" href="https://cdn.bootcss.com/zTree.v3/3.5.28/css/zTreeStyle/zTreeStyle.min.css" type="text/css">
	<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.core.min.js"></script>
	<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.excheck.min.js"></script>
	<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.exedit.min.js"></script>
</head>
<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>角色列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">后台管理</li>
			<li class="active">角色列表</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="callout callout-info">
			▲ 点击“名称”显示详细资料
		</div>
		<div class="box">
			<div class="box-body">
				<table id="table" class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>名称</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="info in roleList">
							<td><a v-bind:onclick="['vm.showRoleInfo(&quot;'+info['name']+'&quot;,&quot;'+info['remark']+'&quot;)']">{{info['name']}}</a></td>
							<td><!--a v-bind:href="['edit.php?id='+info['id']]" class="btn btn-info">编辑</a--> <a v-bind:onclick="['vm.setRolePermission('+info['id']+',&quot;'+info['name']+'&quot;)']" class="btn btn-success">分配权限</a> <a v-bind:onclick="['vm.delete('+info['id']+',&quot;'+info['name']+'&quot;)']" class="btn btn-danger">删除</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->

	<div class="modal fade" id="roleInfoModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">角色详情</h3>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-striped table-bordered" style="border-radius: 5px;border-collapse: separate;">
						<tr>
							<td style="width:75px;">角色名称</td>
							<th style="text-align:left;">{{roleInfoName}}</th>
						</tr>
						<tr>
							<td style="width:75px;">角色描述</td>
							<th style="text-align:left;">{{roleInfoRemark}}</th>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">温馨提示</h3>
				</div>
				<div class="modal-body">
					<center>
						<font color="red" style="font-weight:bold;font-size:24px;text-align:center;">
							确定要删除[<font color="blue">{{deleteRoleName}}</font>]角色吗？
						</font>
					</center>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" v-on:click="cancelDelete">&lt; 取消操作</button> <button class="btn btn-danger" v-bind:onclick="['if(confirm(&quot;请再次确认要删除['+deleteRoleName+']角色吗？&quot;)){vm.toDelete();}else{vm.cancelDelete();}']">确认删除 &gt;</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="setPermissionModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">分配 [{{setPermissionRoleName}}] 的权限</h3>
				</div>
				<div class="modal-body">
					<ul id="treeDemo" class="ztree"></ul>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">&lt; 取消操作</button> <button class="btn btn-success" v-on:click="toSetRolePermission">确认配权 &gt;</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

</div>
<!-- ./wrapper -->

<script>
var ztreeSetting = {
	view: {
		selectedMulti: false
	},
	check: {
		enable: true
	},
	data: {
		simpleData: {
			enable: true
		}
	}
};

var vm = new Vue({
	el:'#app',
	data:{
		roleList:{},
		roleInfoName:"",
		roleInfoRemark:"",
		deleteRoleId:0,
		deleteRoleName:"",
		setPermissionRoleId:0,
		setPermissionRoleName:""
	},
	methods:{
		delete:function(roleId,roleName){
			vm.deleteRoleId=roleId;
			vm.deleteRoleName=roleName;
			$("#deleteModal").modal("show");
		},
		toDelete:function(){
			lockScreen();
			$("#deleteModal").modal("hide");
			$.ajax({
				url:"delete",
				data:{"roleId":vm.deleteRoleId},
				dataType:"json",
				type:"post",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						alert("删除成功！");
						location.reload();
						return true;
					}else if(ret.code==500){
						vm.cancelDelete();
						showModalTips("删除失败！");
						return false;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		cancelDelete:function(){
			vm.deleteRoleId=0;
			vm.deleteRoleName="";
			$("#deleteModal").modal("hide");
		},
		getAllRole:function(){
			lockScreen();
			$.ajax({
				url:headerVm.apiPath+"role/getRoleInfo",
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
						vm.roleList=list;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		showRoleInfo:function(name,remark){
			vm.roleInfoName=name;
			vm.roleInfoRemark=remark;
			$("#roleInfoModal").modal('show');
		},
		setRolePermission:function(roleId,roleName){
			$.ajax({
				url:headerVm.apiPath+"role/getRoleMenuForZtree/"+roleId,
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					vm.setPermissionRoleId=roleId;
					vm.setPermissionRoleName=roleName;
					$.fn.zTree.init($("#treeDemo"),ztreeSetting,JSON.parse(ret));
					$("#setPermissionModal").modal("show");
				}
			});
		},
		toSetRolePermission:function(){
			lockScreen();
			$("#setPermissionModal").modal("hide");

			ids="";
			zTree=$.fn.zTree.getZTreeObj("treeDemo");
			nodes=zTree.getCheckedNodes();
			for(i=0,l=nodes.length;i<l;i++){
				ids+=nodes[i].id+",";
			}
			ids=ids.substr(0,ids.length-1);

			$.ajax({
				url:"toSetRolePermission",
				type:"post",
				data:{'roleId':vm.setPermissionRoleId,'permissionId':ids},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:function(ret){
					unlockScreen();
					if(ret.code==200){
						showModalTips("成功给【"+vm.setPermissionRoleName+"】分配权限！");
						return true;
					}else if(ret.code==1){
						showModalTips("无此角色！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！");
						return false;
					}else if(ret.code==500){
						showModalTips("系统错误！<br>请联系技术支持！");
						return false;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			})
		}
	}
});

vm.getAllRole();

window.onload=function(){ 	
	$('#table').DataTable({
		responsive: true,
		"order":[[0,'asc']],
		"columnDefs":[{
			"targets":[1],
			"orderable": false
		}]
	});
};
</script>



</body>
</html>
