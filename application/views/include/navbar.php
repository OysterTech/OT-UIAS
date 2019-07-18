<?php
/**
 * @name 生蚝科技RBAC开发框架-导航栏
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-06-11
 */
?>

<!--input type="hidden" id="<?=$this->sessPrefix;?>userId" name="<?=$this->sessPrefix;?>userId" value="<?=$this->session->userdata($this->sessPrefix.'userId');?>"-->

<div id="header">
<header class="main-header">
	<a v-bind:href="[rootUrl+'dashborad']" class="logo">
		<span class="logo-mini"><img src="https://www.xshgzs.com/resource/index/images/logo2.png" style="width:85%"></span>
		<span class="logo-lg"><img src="https://www.xshgzs.com/resource/index/images/logo2.png" style="width:20%"> <b>生蚝科技</b></span>
	</a>
	<nav class="navbar navbar-static-top">
		<a class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img v-bind:src="[rootUrl+'resource/images/user.png']" class="user-image">
						<span class="hidden-xs">{{userInfo['nickName']}}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img v-bind:src="[rootUrl+'resource/images/user.png']" class="img-circle">
							<p>{{userInfo['userName']}} - {{userInfo['nickName']}}<!--small>Member since ?</small--></p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a v-bind:href="[rootUrl+'user/updateProfile']" class="btn btn-default btn-flat">修改资料</a>
								<a data-toggle="modal" data-target="#changePasswordModal" class="btn btn-default btn-flat">修改密码</a>
							</div>
							<div class="pull-right">
								<button data-toggle="modal" data-target="#logoutModal" class="btn btn-default btn-flat">登出</button>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>

<div class="modal fade" id="changePasswordModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">修改密码</h3>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					<center>
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 新密码需包含6~20位的字符！
					</center>
				</div>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-unlock" aria-hidden="true"></i></span>
					<input id="oldPwd" type="password" class="form-control" onkeyup='if(event.keyCode==13)$("#newPwd").focus();' placeholder="请输入原密码">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
					<input id="newPwd" type="password" class="form-control" onkeyup='if(event.keyCode==13)$("#surePwd").focus();' placeholder="请输入新密码">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-shield" aria-hidden="true"></i></span>
					<input id="surePwd" type="password" class="form-control" placeholder="请再次输入新密码">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">&lt; 取消</button> <button type="button" class="btn btn-success" onclick="toChangePassword();">确认修改 &gt;</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-warning fade" id="logoutModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">登出提示</h4>
			</div>
			<div class="modal-body">
				<h3 style="line-height:38px;">确认要退出吗？</h3>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">&lt; 取消</button>
				<a v-bind:href="[rootUrl+'user/logout']" class="btn btn-outline">确认登出 &gt;</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- 侧边导航栏 -->
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img v-bind:src="[rootUrl+'resource/images/user.png']" class="img-circle">
			</div>
			<div class="pull-left info">
				<p>{{userInfo['nickName']}}</p>
				<select id="roleList" style="background-color:rgb(59, 73, 102);border:0;color:#fff;margin-left:-4px;" v-on:change="changeRole" v-model="roleIdSelected">
					<option v-for="(roleName,roleId) in allRoleInfo" v-bind:value="roleId">{{roleName}}</option>
				</select>
			</div>
		</div>
		<!-- 菜单树 -->
		<!-- 父菜单 -->
		<ul class="sidebar-menu" data-widget="tree">
			<li>
				<a v-bind:href="[rootUrl+'dashborad']">
					<i class="fa fa-home"></i> 系统主页面
				</a>
			</li>
			<li v-for="fatherInfo in treeData" v-if="fatherInfo['hasChild']!=1 && fatherInfo['type']==1"><a v-bind:href="[rootUrl+fatherInfo['uri']]"><i v-bind:class="['fa fa-'+fatherInfo['icon']]"></i> {{fatherInfo['name']}}</a></li>
			<!-- 二级菜单 -->
			<li v-else-if="fatherInfo['type']==1" class="treeview">
				<a href="#">
					<i v-bind:class="['fa fa-'+fatherInfo['icon']]"></i> <span>{{fatherInfo['name']}}</span>
					<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
					<li v-for="childInfo in fatherInfo['child']" v-if="childInfo['hasChild']!=1"><a v-bind:href="[rootUrl+childInfo['uri']]"><i v-bind:class="['fa fa-'+childInfo['icon']]"></i> {{childInfo['name']}}</a></li>
					<!-- 三级菜单 -->
					<li v-else class="treeview">
						<a href="#">
							<i v-bind:class="['fa fa-'+childInfo['icon']]"></i> <span>{{childInfo['name']}}</span>
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">
							<li v-for="grandsonInfo in childInfo['child']"><a v-bind:href="[rootUrl+grandsonInfo['uri']]"><i v-bind:class="['fa fa-'+grandsonInfo['icon']]"></i> {{grandsonInfo['name']}}</a></li>
						</ul>
					</li>
					<!-- ./三级菜单 -->
				</ul>
			</li>
			<!-- ./二级菜单 -->
		</ul>
		<!-- ./父菜单 -->
		<!-- ./菜单树 -->
	</section>
</aside>
<!-- ./侧边导航栏 -->

</div>

<script>
function toChangePassword(){
	lockScreen();
	oldPwd=$("#oldPwd").val();
	newPwd=$("#newPwd").val();
	surePwd=$("#surePwd").val();

	if(oldPwd==""){
	 unlockScreen();
	 showModalTips("请正确输入旧密码！");
	 return false;
	}
	if(newPwd.length<6 || newPwd.length>20){
	 unlockScreen();
	 showModalTips("请输入6~20位的新密码！");
	 return false;
	}
	if(surePwd!=newPwd){
	 unlockScreen();
	 showModalTips("两次输入的新密码不相符！");
	 return false;
	}
	if(oldPwd==newPwd){
	 unlockScreen();
	 showModalTips("新密码与旧密码相同！");
	 return false;
	}
	
	$.ajax({
		url:"<?=base_url('profile/toChangePassword');?>",
		type:"post",
		dataType:"json",
		data:{"oldPwd":oldPwd,"newPwd":newPwd},
		error:function(e){
			unlockScreen();
			console.log(JSON.stringify(e));
			showModalTips("服务器错误！<br>请将错误码["+e.readyState+"."+e.status+"]至技术支持");
			return false;
		},
		success:function(ret){
			unlockScreen();
		
			if(ret.code==200){
				$("#changePasswordModal").modal("hide");
				$("#oldPwd").val("");
				$("#newPwd").val("");
				$("#surePwd").val("");
				showModalTips("修改密码成功！<br>下次登录请使用新密码登录！");
				return true;
			}else if(ret.code==403){
				showModalTips("旧密码错误！");
				return true;
			}else if(ret.code==500){
				showModalTips("修改失败！<br>请联系技术支持！");
				return true;
			}else{
				console.log(JSON.stringify(ret));
				showModalTips("系统错误！<br>请将错误码["+ret.code+"]至技术支持");
				return false;
			}
		}
	});
}
</script>

<script>
var headerVm = new Vue({
	el:'#header',
	data:{
		rootUrl:"<?=base_url();?>",
		apiPath:"<?=$this->API_PATH;?>",
		userId:$("#<?=$this->sessPrefix;?>userId").val(),
		userInfo:{},
		treeData:{},
		allRoleInfo:{},
		roleIdSelected:''
	},
	methods:{
		getUserInfo:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"user/getUserInfo",
				type:"post",
				data:{"method":"own"},
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
						info=ret.data['userInfo'];
						headerVm.userInfo=info;
						headerVm.roleIdSelected=headerVm.userInfo['roleId'];
						return true;
					}else{
						unlockScreen();
						showModalTips("系统错误！<br>请联系技术支持并提供错误码【USIF"+ret.code+"】");
						return false;
					}
				}
			});
		},
		getMenuTree:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"role/getUserMenu",
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					if(ret.code==200){
						treeData=ret.data['treeData'];
						headerVm.treeData=treeData;
						if(treeData==""){
							showModalTips("用户菜单获取失败 或 用户暂无权限！");
						}
						unlockScreen();
						return true;
					}else if(ret.code==403){
						unlockScreen();
						showModalTips("用户菜单获取失败！");
						return false;
					}else{
						unlockScreen();
						showModalTips("系统错误！<br>请联系技术支持并提供错误码【MN"+ret.code+"】");
						return false;
					}
				}
			});
		},
		getAllRole:function(){
			allRoleInfo=localStorage.getItem('allRoleInfo');
			headerVm.allRoleInfo=JSON.parse(allRoleInfo);
		},
		changeRole:function(){
			roleId=$("#roleList").val();

			if(roleId=='' || roleId==headerVm.userInfo['roleId']) return;

			$.ajax({
				url:headerVm.rootUrl+"user/toChangeRole",
				type:"post",
				data:{"roleId":roleId},
				dataType:"json",
				error:function(e){
					console.log(JSON.stringify(e));
				},
				success:function(ret){
					window.location.href=headerVm.rootUrl;
				}
			});
		}
	}
});

headerVm.getUserInfo();
headerVm.getMenuTree();
headerVm.getAllRole();
</script>
