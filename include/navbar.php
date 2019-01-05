<?php
/**
 * @name 生蚝科技统一身份认证平台-导航栏
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-31
 * @version 2019-01-05
 */
?>

<input type="hidden" id="<?=SESSION_PREFIX;?>unionId" name="<?=SESSION_PREFIX;?>unionId" value="<?=getSess('unionId');?>">

<header class="main-header">
	<a href="<?=ROOT_PATH;?>dashborad.php" class="logo">
		<span class="logo-mini"><img src="https://www.itrclub.com/resource/index/img/logo.png" style="width:85%"></span>
		<span class="logo-lg"><img src="https://www.itrclub.com/resource/index/img/logo.png" style="width:20%"> <b>ITRClub</b></span>
	</a>
	<nav class="navbar navbar-static-top">
		<a class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bell-o"></i>
						<span class="label label-warning" id="navbarNoticeNum"></span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">最近一个月的通知公告</li>
						<li>
							<ul class="menu" id="navbarNoticeList">
							</ul>
						</li>
						<li class="footer"><a href="<?=ROOT_PATH;?>notice/list.php">查看所有通知 &gt;</a></li>
					</ul>
				</li>
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="<?=IMG_PATH;?>user.png" class="user-image">
						<span class="hidden-xs" id="nickNameNavbarBoxShow"></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="<?=IMG_PATH;?>user.png" class="img-circle">
							<p id="nameNavbarBoxShow"><!--small>Member since ?</small--></p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="<?=ROOT_PATH;?>profile/my.php" class="btn btn-default btn-flat">个人中心</a>
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
		url:"<?=ROOT_PATH;?>profile/toChangePassword.php",
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
			}else if(ret.code==2){
				showModalTips("修改错误！");
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

<div class="modal modal-warning fade" id="logoutModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">登出提示</h4>
			</div>
			<div class="modal-body">
				<h3 style="line-height:38px;">确认要退出统一认证平台吗？<br>退出后将会登出所有系统！</h3>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">&lt; 取消</button>
				<a href="<?=ROOT_PATH;?>logout.php" class="btn btn-outline">确认登出 &gt;</a>
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
				<img src="<?=IMG_PATH;?>user.png" class="img-circle">
			</div>
			<div class="pull-left info">
				<p id="nickNameTreeShow"></p>
				<small id="roleNameTreeShow"></small>
			</div>
		</div>
		<ul class="sidebar-menu" data-widget="tree" id="menuTree"></ul>
	</section>
</aside>
<!-- ./侧边导航栏 -->

<script>
getUserInfo();
getMenuTree();
getNavbarNotice();


function getUserInfo(){
	lockScreen();
	unionId=$("#<?=SESSION_PREFIX;?>unionId").val();

	$.ajax({
		url:"<?=API_PATH;?>user/getUserInfo.php",
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
				info=ret.data['userInfo'];
				userName=info['userName'];
				nickName=info['nickName'];
				roleName=info['roleName'];

				$("#nameNavbarBoxShow").html(userName+" - "+nickName);
				$("#nickNameNavbarBoxShow").html(nickName);
				$("#nickNameTreeShow").html(nickName);
				$("#roleNameTreeShow").html(roleName);

				unlockScreen();
				return true;
			}else{
				unlockScreen();
				showModalTips("系统错误！<br>请联系技术支持并提供错误码【USIF"+ret.code+"】");
				return false;
			}
		}
	});
}


function getMenuTree(){
	lockScreen();

	$.ajax({
		url:"<?=API_PATH;?>user/getUserMenu.php",
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

				// 显示父菜单
				for(i in treeData){
					fatherInfo=treeData[i];
					
					if(fatherInfo['hasChild']!=1){
						html='<li><a href="'+fatherInfo['uri']+'"><i class="fa fa-'+fatherInfo['icon']+'"></i> '+fatherInfo['name']+'</a></li>';
						$("#menuTree").append(html);
					}else{
						html='<li class="treeview">'
						    +'<a href="#">'
						    +'<i class="fa fa-'+fatherInfo['icon']+'"></i> <span>'+fatherInfo['name']+'</span>'
						    +'<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>'
						    +'</a>'
						    +'<ul class="treeview-menu" id="tree_father_'+fatherInfo['id']+'">';
						$("#menuTree").append(html);

						// 显示二级菜单
						for(j in fatherInfo['child']){
							childInfo=fatherInfo['child'][j];
							
							if(childInfo['hasChild']!=1){
								html='<li><a href="'+childInfo['uri']+'"><i class="fa fa-'+childInfo['icon']+'"></i> '+childInfo['name']+'</a></li>'
								    +'</ul>'
								    +'</li>';// 闭合父菜单标签
								$("#tree_father_"+fatherInfo['id']).append(html);
							}else{
								html='<li class="treeview">'
								    +'<a href="#">'
								    +'<i class="fa fa-'+childInfo['icon']+'"></i> <span>'+childInfo['name']+'</span>'
								    +'<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>'
								    +'</a>'
								    +'<ul class="treeview-menu" id="tree_child_'+childInfo['id']+'">';
								$("#tree_father_"+fatherInfo['id']).append(html);

								// 显示三级菜单
								for(k in childInfo['child']){
									grandsonInfo=childInfo['child'][k];
									html='<li><a href="'+grandsonInfo['uri']+'"><i class="fa fa-'+grandsonInfo['icon']+'"></i> '+grandsonInfo['name']+'</a></li>'
									    +'</ul>'
									    +'</li>';// 闭合二级菜单标签
									$("#tree_child_"+childInfo['id']).append(html);
								}
							}
						}
					}
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
}


function getNavbarNotice(){
	$.ajax({
		url:"<?=API_PATH;?>notice/get.php",
		data:{"type":"navbar"},
		dataType:"json",
		error:function(e){
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				list=ret.data['list'];
				count=list.length;

				if(count>0) $("#navbarNoticeNum").html(count);
				else $("#navbarNoticeNum").remove();

				for(info in list){
					id=list[info]['id'];
					title=list[info]['title'];
					html='<li><a href="<?=ROOT_PATH;?>notice/detail.php?id='+id+'"><i class="fa fa-bullhorn"></i> '+title+'</a></li>';
					$("#navbarNoticeList").append(html);
				}
				
				if(list==""){
					html="<li>"
					    +"<a><font color='blue'><b>暂无公告！</b></font></a>"
					    +"</li>";
					$("#navbarNoticeList").append(html);
				}

			}
		}
	});
}
</script>
