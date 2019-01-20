<?php
/**
 * @name 生蚝科技统一身份认证平台-用户列表
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2019-01-17
 * @version 2019-01-20
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>用户列表 / <?=$this->setting->get('systemName');?></title>
	<?php $this->load->view('include/header'); ?>
</head>
<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1>用户列表</h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> 生蚝科技用户中心</a></li>
			<li class="active">后台管理</li>
			<li class="active">用户列表</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="callout callout-info">
			▲ 点击“用户名”显示详细资料<br>
			▲ 点击“角色”修改用户角色
		</div>
		<div class="box">
			<div class="box-body">
				<table id="table" class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>用户名(昵称)</th>
							<th>角色</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="info in userList">
							<td><a v-bind:onclick="['vm.showUserInfo(&quot;'+info['unionId']+'&quot;,&quot;'+info['nickName']+'&quot;)']">{{info['userName']}}({{info['nickName']}})</a></td>
							<td><a v-bind:onclick="['vm.chooseRole('+info['id']+',&quot;'+info['nickName']+'&quot;,&quot;'+info['roleName']+'&quot;)']">{{info['roleName']}}</a></td>
							<td><!--a v-bind:href="['edit.php?id='+info['id']]" class="btn btn-info">编辑</a--> <a v-bind:onclick="['vm.resetPassword('+info['id']+',&quot;'+info['userName']+'&quot;,&quot;'+info['nickName']+'&quot;)']" class="btn btn-warning">重置密码</a> <a v-bind:onclick="['vm.delete('+info['id']+',&quot;'+info['nickName']+'&quot;)']" class="btn btn-danger">删除</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->

	<div class="modal fade" id="chooseRoleModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{chooseRoleModalTitle}}</h3>
				</div>
				<div class="modal-body">
					<select id="chooseRoleId" class="form-control" v-on:change="showRoleInfo(this)">
						<option v-for="info in roleList" v-bind:value="info['id']" v-bind:name="info['name']">{{info['name']}}</option>
					</select>
					<br>
					<table class="table table-hover table-striped table-bordered" style="border-radius: 5px;border-collapse: separate;" v-bind:style="{display:roleInfoStyle}">
						<tr>
							<td style="width:75px;">角色名称</td>
							<th style="text-align:left;">{{roleName}}</th>
						</tr>
						<tr>
							<td style="width:75px;">角色描述</td>
							<th style="text-align:left;">{{roleRemark}}</th>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">&lt; 取消操作</button> <button class="btn btn-success" v-on:click="toChangeRole">确认修改 &gt;</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="userInfoModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{userInfoModalTitle}}</h3>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-striped table-bordered" style="border-radius: 5px;border-collapse: separate;">
						<tr>
							<td style="width:75px;">UnionID</td>
							<th style="text-align:left;">{{userUnionId}}</th>
						</tr>
						<tr>
							<td style="width:75px;">用户名</td>
							<th style="text-align:left;">{{userInfo['userName']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">昵称</td>
							<th style="text-align:left;">{{userInfo['nickName']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">手机号</td>
							<th style="text-align:left;">{{userInfo['phone']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">邮箱</td>
							<th style="text-align:left;">{{userInfo['email']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">学校</td>
							<th style="text-align:left;">{{userInfo['school']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">积分余额</td>
							<th style="text-align:left;">{{userInfo['integral']}}</th>
						</tr>
						<tr>
							<td style="width:75px;">{{specialIntegralName}}</td>
							<th style="text-align:left;">{{userInfo['specialIntegral']}}</th>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="resetPasswordModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">温馨提示</h3>
				</div>
				<div class="modal-body">
					<center>
						<font color="red" style="font-weight:bold;font-size:24px;text-align:center;">
							确定要重置[<font color="blue">{{resetPasswordNickName}}</font>]的账户密码吗？
						</font>
					</center>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" data-dismiss="modal">&lt; 取消操作</button> <button class="btn btn-warning" v-on:click="toResetPassword">确认重置 &gt;</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="resetResultModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">[{{resetPasswordNickName}}]的初始密码</h3>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-striped table-bordered" style="border-radius: 5px;border-collapse: separate;">
						<tr>
							<td style="width:75px;">用户名</td>
							<th style="text-align:left;">{{resetPasswordUserName}}</th>
						</tr>
						<tr>
							<td style="width:75px;">昵称</td>
							<th style="text-align:left;">{{resetPasswordNickName}}</th>
						</tr>
						<tr>
							<td style="width:75px;">初始密码</td>
							<th style="text-align:left;color:red;">{{originPassword}}</th>
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
							确定要删除[<font color="blue">{{deleteNickName}}</font>]的通行证吗？
						</font>
					</center>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" v-on:click="cancelDelete">&lt; 取消操作</button> <button class="btn btn-danger" v-bind:onclick="['if(confirm(&quot;请再次确认要删除['+deleteNickName+']的通行证吗？&quot;)){vm.toDelete();}else{vm.cancelDelete();}']">确认删除 &gt;</button>
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
var vm = new Vue({
	el:'#app',
	data:{
		userList:{},
		roleList:{},
		chooseRoleModalTitle:"",
		roleInfoStyle:"none",
		roleName:"",
		roleRemark:"",
		roleOperatingUserId:0,
		userInfoModalTitle:"",
		userInfo:{},
		userUnionId:"",
		specialIntegralName:"",
		resetPasswordUserId:0,
		resetPasswordUserName:"",
		resetPasswordNickName:"",
		originPassword:{},
		deleteUserId:"",
		deleteNickName:""
	},
	methods:{
		getAllUser:function(){
			lockScreen();
			$.ajax({
				url:headerVm.apiPath+"user/getAllUser",
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
						vm.userList=list;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		delete:function(userId,nickName){
			vm.deleteUserId=userId;
			vm.deleteNickName=nickName;
			$("#deleteModal").modal("show");
		},
		toDelete:function(){
			lockScreen();
			$("#deleteModal").modal("hide");
			$.ajax({
				url:headerVm.apiPath+"user/delete",
				data:{"userId":vm.deleteUserId},
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
					}else if(ret.code==400){
						vm.cancelDelete();
						showModalTips("禁止操作当前用户！");
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
			vm.deleteUserId=0;
			vm.deleteNickName="";
			$("#deleteModal").modal("hide");
		},
		chooseRole:function(userId,userName,roleName){
			vm.roleOperatingUserId=userId;
			vm.chooseRoleModalTitle="选择["+userName+"]的角色";
			vm.setRoleList();
			$("select option[name='"+roleName+"']").attr("selected","selected");
			$("#chooseRoleModal").modal("show");
		},
		setRoleList:function(){
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
		showRoleInfo:function(){
			roleId=$("#chooseRoleId").val();
			$.ajax({
				url:headerVm.apiPath+"role/getRoleInfo/"+roleId,
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
						roleInfo=ret.data['list'][0];
						vm.roleName=roleInfo['name'];
						vm.roleRemark=roleInfo['remark'];
						vm.roleInfoStyle="";
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		toChangeRole:function(){
			userId=vm.roleOperatingUserId;
			roleId=$("#chooseRoleId").val();
			$.ajax({
				url:headerVm.apiPath+"user/updateUserInfo",
				data:{"userId":userId,"roleId":roleId},
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
						alert("修改成功！");
						location.reload();
						return true;
					}else{
						showModalTips("系统错误！"+ret.code);
						console.log(ret);
						return false;
					}
				}
			});
		},
		showUserInfo:function(unionId,nickName){
			lockScreen();
			$.ajax({
				url:headerVm.apiPath+"user/getUserInfo",
				type:"post",
				data:{"method":"unionId","unionId":unionId},
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					if(ret.code==200){
						unlockScreen();
						vm.userUnionId=unionId;
						vm.userInfo=ret.data['userInfo'];
						vm.userInfoModalTitle="["+nickName+"]的用户资料";

						if(vm.userInfo['isGroup']==1){
							vm.specialIntegralName="赊欠积分";
						}else if(vm.userInfo['isGroup']==0){
							vm.specialIntegralName="冻结积分";
						}

						$("#userInfoModal").modal("show");
						return true;
					}else{
						unlockScreen();
						showModalTips("系统错误！<br>请联系技术支持并提供错误码【USIF"+ret.code+"】");
						return false;
					}
				}
			});		
		},
		resetPassword:function(userId,userName,nickName){
			vm.resetPasswordUserId=userId;
			vm.resetPasswordUserName=userName;
			vm.resetPasswordNickName=nickName;
			$("#resetPasswordModal").modal("show");
		},
		toResetPassword:function(){
			lockScreen();
			$("#resetPasswordModal").modal("hide");
			$.ajax({
				url:headerVm.apiPath+"user/resetPassword",
				data:{"userId":vm.resetPasswordUserId},
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
						vm.originPassword=ret.data['password'];
						$("#resetResultModal").modal("show");
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
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

vm.getAllUser();

window.onload=function(){ 	
	$('#table').DataTable({
		responsive: true,
		"order":[[0,'asc']],
		"columnDefs":[{
			"targets":[2],
			"orderable": false
		}]
	});
};
</script>



</body>
</html>
