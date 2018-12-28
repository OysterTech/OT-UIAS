<?php
/**
 * @name 生蚝科技统一身份认证平台-个人资料
 * @author Jerry Cheung <master@smhgzs.com>
 * @since 2018-12-28
 * @version 2018-12-28
 */

require_once '../include/public.func.php';

if(getSess("isLogin")!=1){
	gotoUrl(ROOT_PATH."login.php");
}

// 查询登录记录
$query=PDOQuery($dbcon,"SELECT * FROM user WHERE id=?",[getSess("user_id")],[PDO::PARAM_INT]);
if($query[1]!=1){
	gotoUrl(ROOT_PATH);
}else{
	$info=$query[0][0];
}
?>
<html>
<head>
	<title>个人资料 / 生蚝科技统一身份认证平台</title>
	<?php include '../include/header.php'; ?>
	<link rel="stylesheet" href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" src="https://www.xshgzs.com/yc/cdms/resource/css/dataTables.responsive.css">
</head>
<body style="padding:68px 0 0 10px;">

<?php include '../include/navbar.php'; ?>

<ol class="breadcrumb">
	<li><a href="<?=ROOT_PATH;?>">统一认证平台用户中心</a></li>
	<li class="active">个人资料卡</li>
</ol>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">个人资料</h3>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label for="userName">登录用户名(3~20位)</label>
			<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();' value="<?=$info['user_name'];?>">
			<p class="help-block">用户名仅允许含有数字字母下划线</p>
		</div><br>
		<div class="form-group">
			<label for="nickName">昵称(3~20位)</label>
			<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#phone").focus();' value="<?=$info['nick_name'];?>">
		</div><br>
		<div class="form-group">
			<label for="phone">手机号</label>
			<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();' value="<?=$info['phone'];?>">
			<p class="help-block">目前仅支持中国大陆11位手机号码</p>
		</div><br>
		<div class="form-group">
			<label for="email">邮箱地址</label>
			<input class="form-control" id="email" onkeyup='if(event.keyCode==13)$("#").focus();' value="<?=$info['email'];?>">
			<p class="help-block"></p>
		</div><br>
		<div class="form-group">
			<label>注册时间</label>
			<input class="form-control" value="<?=$info['create_time'];?>" disabled>
		</div><br>
		<div class="form-group">
			<label>最后修改时间</label>
			<input class="form-control" value="<?=$info['update_time'];?>" disabled>
		</div><br>
		<button onclick="toEdit()" class="btn btn-info btn-block">确 认 修 改 &gt;</button>
	</div>
</div>

<?php include '../include/footer.php'; ?>

<script>
function testUserName(txt){
	r=new RegExp("[\\u4E00-\\u9FFF]+","g");
	if(r.test(txt)){
		return false;
	}else{
		r=new RegExp(/^[\w]+$/);
		if(!r.test(txt)){
			return false;
		}else{
			return true;
		}
	}
}


function toEdit(){
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	phone=$("#phone").val();
	email=$("#email").val();

	if(userName=="" || nickName=="" || phone=="" || email==""){
		showModalTips("请完整填写所有资料！");
		return;
	}
	if(testUserName(userName)===false){
		showModalTips("用户名仅允许含有数字字母下划线！");
		return;
	}
	if(userName.length<3 || userName.length>20){
		showModalTips("用户名长度应为3~20位！");
		return;
	}
	if(nickName.length<3 || nickName.length>20){
		showModalTips("昵称长度应为3~20位！");
		return;
	}
	if(phone.length!=11){
		showModalTips("请正确输入手机号！");
		return;
	}

	$.ajax({
		url:"toEdit.php",
		type:"POST",
		data:{"userName":userName,"nickName":nickName,"phone":phone,"email":email},
		dataType:"json",
		error:function(e){
			console.log(e);
			showModalTips("服务器错误！"+e.status);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				alert("修改成功！");
				window.location.href="<?=ROOT_PATH;?>";
			}else if(ret.code==1){
				showModalTips("此用户名已存在！<br>请更换其他用户名");
				return;
			}else if(ret.code==2){
				showModalTips("此昵称已存在！<br>请更换其他昵称");
				return;
			}else if(ret.code==3){
				showModalTips("此手机号已存在！");
				return;
			}else if(ret.code==4){
				showModalTips("此邮箱已存在！<br>请更换其他邮箱");
				return;
			}else if(ret.code==5){
				showModalTips("修改失败！<br>请检查是否有修改资料！");
				return;
			}else{
				showModalTips("系统错误！<br>请联系管理员并提交错误码["+ret.code+"]");
				return;
			}
		}
	});
}
</script>

</body>
</html>
