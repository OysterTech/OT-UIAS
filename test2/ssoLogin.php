<?php
/**
 * @name XX应用-SSO登录处理页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-01
 * @version 2018-12-14
 */
require_once 'include/public.func.php';
?>
<html>
<head>
	<title>测试2应用SSO登录 / 生蚝科技</title>
	<?php include 'include/header.php'; ?>
</head>

<body>
Token：<?php echo $_GET['token']; ?><br>
通过Ajax到sso中心换取用户信息<br>
<a href="http://ssouc.xshgzs.com/logout.php">登出</a>
<?php include 'include/footer.php'; ?>

<script>
var token=getURLParam("token");
getUserInfo();

function getUserInfo(){
	$.ajax({
		url:"<?=SSO_SERVICE_PATH;?>api/getUserInfo.php",
		//type:"post"
		data:{"token":token},
		dataType:"json",
		error:function(e){
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				data=ret.data;
				alert("认证成功！用户名:"+data['userName']);
				return true;
			}else if(ret.code==403){
				window.location.href="<?=ROOT_PATH;?>login.php";
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
