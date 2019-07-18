<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-用户列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-14
 * @version 2019-06-13
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>用户列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'用户列表','path'=>[['用户列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a href="<?=base_url('admin/user/add');?>" class="btn btn-primary btn-block">新 增 用 户</a>
		<hr>

		<div class="panel panel-default">
			<div class="panel-body">
				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>用户名</th>
							<th>昵称</th>
							<th>状态</th>
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
		updateId:0,
		statusNum:1,
		resetId:0,
		deleteId:0
	},
	methods:{
		getList:()=>{
			$.ajax({
				url:"./get",
				dataType:'json',
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						table=$('#table').DataTable({
							responsive: true,
							"columnDefs":[{
								"targets":[3],
								"orderable": false
							}]
						});

						for(i in list){
							let statusHtml=
								list[i]['status']==0 ? "<a onclick='vm.updateStatus_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+",1);'><font color='red'>已禁用</font></a>" : 
								(list[i]['status']==1 ? "<a onclick='vm.updateStatus_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+",0);'><font color='green'>正常</font></a>" : "<font color='blue'>未激活</font>")
							let operateHtml=''
							               +'<a href="'+headerVm.rootUrl+'admin/user/edit?id='+list[i]['id']+'" class="btn btn-info">编辑</a> '
							               +"<a onclick='vm.resetPwd_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-warning'>重置密码</a> "
							               +"<a onclick='vm.del_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-danger'>删除</a>";

							table.row.add({
								0: list[i]['user_name'],
								1: list[i]['nick_name'],
								2: statusHtml,
								3: operateHtml
							}).draw();
						}
					}
				}
			})
		},
		updateStatus_ready:(id,nickName,status)=>{
			vm.updateId=id;
			vm.statusNum=status;
			statusTips="确定要";

			if(status==0){
				statusTips+="<font color=red>禁用</font>";
			}else if(status==1){
				statusTips+="<font color=green>启用</font>";
			}else{
				showModalTips("错误的状态码！");
				return false;
			}

			statusTips+="用户["+nickName+"]吗？";
			$("#statusTips").html(statusTips);
			$("#statusModal").modal('show');
		},
		updateStatus_sure:()=>{
			lockScreen();

			$.ajax({
				url:headerVm.rootUrl+"admin/user/toUpdateStatus",
				type:"post",
				dataType:"json",
				data:{"id":vm.updateId,"status":vm.statusNum},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#statusModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();
					$("#statusModal").modal('hide');

					if(ret.code==200){
						alert("更新成功！");
						location.reload();
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==1){
						showModalTips("更新失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		resetPwd_ready:(id,name)=>{
			vm.resetId=id;
			$("#resetName_show").html(name);
			$("#resetModal").modal('show');
		},
		resetPwd_sure:()=>{
			lockScreen();

			$.ajax({
				url:headerVm.rootUrl+"admin/user/toResetPwd",
				type:"post",
				dataType:"json",
				data:{"id":vm.resetId},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#resetModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();
					$("#resetModal").modal('hide');

					if(ret.code==200){
						$("#info_userName_show").html(ret.data['userName']);
						$("#info_nickName_show").html(ret.data['nickName']);
						$("#info_originPwd_show").html(ret.data['originPwd']);
						$("#infoModal").modal('show');
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==1){
						showModalTips("无此用户！！！");
						return false;
					}else if(ret.code==2){
						showModalTips("重置失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		del_ready:(id,name)=>{
			vm.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		del_sure:()=>{
			lockScreen();
			
			$.ajax({
				url:headerVm.rootUrl+"admin/user/toDelete",
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
					$("#delModal").modal('hide');

					if(ret.code==200){
						alert("删除成功！");
						location.reload();
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==1){
						showModalTips("删除失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
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
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列用户吗？</font>
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


<div class="modal fade" id="resetModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要重置下列用户的密码吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="resetName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm.resetPwd_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">更新状态提示</h3>
      </div>
      <div class="modal-body">
        <font style="font-weight:bold;font-size:24px;text-align:center;">
          <p id="statusTips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button>
        <button type="button" class="btn btn-primary" onclick="vm.updateStatus_sure();">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="infoModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">用户详细资料</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;text-align: center;">
        <tr>
          <td>用户名</td>
          <th><p id="info_userName_show"></p></th>
        </tr>
        <tr>
          <td>昵称</td>
          <th><p id="info_nickName_show"></p></th>
        </tr>
        <tr>
          <td>初始密码</td>
          <th><p id="info_originPwd_show" style="color: green;font-weight: bold;"></p></th>
        </tr>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确认 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
