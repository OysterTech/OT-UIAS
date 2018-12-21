<?php
/**
 * @name 生蚝科技统一身份认证平台-登录页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-21
 */

require_once 'include/public.func.php';

$appId=isset($_GET['appId'])?$_GET['appId']:"";
$returnUrl=isset($_GET['returnUrl'])?urldecode($_GET['returnUrl']):ROOT_PATH."main.php";

// 查询应用信息
$query=PDOQuery($dbcon,"SELECT * FROM app WHERE app_id=? AND return_url=?",[$appId,$returnUrl],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($query[1]!=1){
	// 没有此应用
	if($appId!="" && $returnUrl!=ROOT_PATH."main.php"){
		gotoUrl(ROOT_PATH."noAccess.php?mod=appInfo");
	}else{
		$appName="统一认证平台用户中心";
	}
}else{
	$appName=$query[0][0]['name'];
}

setSess(['appId'=>$appId,'returnUrl'=>$returnUrl]);

if(getSess("isLogin")==1){
	// 已登录，重新生成token，防止二次使用
	$token=sha1(md5($appId).time());
	setSess(['token'=>$token]);
	
	gotoUrl($returnUrl."?token=".getSess('token'));
}
?>
<html>
<head>
	<title>登录 / 生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="padding-top:12px;">

<center>
	正在通过统一身份认证平台快速登录：<b><?=$appName;?></b>
</center>

<hr>

<div>
	用户名：<input name="userName" id="userName" value="1"><br>
	密码：<input name="password" id="password" value="1"><br>
	<button class="btn btn-success" onclick="toLogin();">登录</button>
	<input type="hidden" id="appId" value="<?=$appId;?>">
</div>

<?php include 'include/footer.php'; ?>

<script>
function toLogin(){
	appId=$("#appId").val();
	userName=$("#userName").val();
	password=$("#password").val();
	
	$.ajax({
		url:"toLogin.php",
		//type:"post"
		data:{"appId":appId,"userName":userName,"password":password},
		dataType:"json",
		error:function(e){
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				data=ret.data;
				window.location.href=data['returnUrl'];
				return true;
			}else if(ret.code==4031){
				showModalTips("用户名或密码错误！");
				return false;
			}else if(ret.code==4032){
				alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");
				window.location.href="<?=ROOT_PATH;?>main.php";
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				showModalTips("未知错误！请提交错误码["+ret.code+"]并联系技术支持");
				return false;
			}
		}
	});
}
</script>

<div class="modal fade" id="tipsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bold;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
