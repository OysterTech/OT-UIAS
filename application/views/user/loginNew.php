<html>
<head>
	<title>登录 / 生蚝科技统一身份认证平台</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="生蚝科技,生蚝科技统一身份认证平台,生蚝科技用户中心">
	<meta name="description" content="生蚝科技统一身份认证平台">
	<meta name="author" content="生蚝科技 Oyster Tech">
	<link rel="shortcut icon" href="<?=base_url('resource/image/favicon.ico');?>">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" type="text/css" href="https://id.xshgzs.com/resource/css/login.css">

	<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://static.xshgzs.com/js/utils.js"></script>
	<script src="/resource/js/login.js"></script>

	<!-- Baidu tongji-->
	<script>
		var _hmt = _hmt || [];
		(function() {
			var hm = document.createElement("script");
			hm.src = "https://hm.baidu.com/hm.js?57f935b2ea561c6b84f8ea26dd96fe5a";
			var s = document.getElementsByTagName("script")[0]; 
			s.parentNode.insertBefore(hm, s);
		})();
	</script>
</head>
<body style="background-image: url(https://static.xshgzs.com/image/backstage_bg.png); background-position: center center; background-repeat: repeat;">
	
	<!-- 登陆面板 -->
	<div class="login_page">
		<div class="login_box_m">
			<div class="content">

				<div class="logo">
					<img src="/resource/images/logo.png">
				</div>

				<div class="login_form">
					<div class="login_form_title">
						<center><h3>用户登录</h3></center>
					</div>

					<div class="login_form_detail">
						<!-- 登录表单 -->
						<div class="left_side">

							<div class="input_div">
								<i class="_icon _i-user"></i>
								<input id="username" placeholder="请输入您的通行证帐号">
							</div>

							<div class="input_div psw" style="margin-bottom: 20px;">
								<i class="_icon _i-psw"></i>
								<input type="password" id="password" placeholder="请输入通行证密码">
							</div>

							<button class="sub_btn" style="cursor: pointer;">登录</button>

							<!-- 忘记密码? -->
							<div class="clearfix">
								<a class="forgetPassword">忘记密码</a>
							</div>

						</div>

						<!-- 右侧 -->
						<div class="right_side clearfix">
							<!-- 第三方互联登录 -->
							<div class="div1">
								<div class="login_form_title2">
									<center><h3>第三方互联登录<br></h3></center>
								</div>

								<!-- 移动端分割线 -->
								<div class="connectHr"><hr></div>
								<!-- /.移动端分割线 -->

								<a style="color:#07C160;text-decoration: none;">
									<i class="fa fa-weixin"></i>
									<b class="connectText">微信扫码<br></b>
								</a>
								<a style="color:#24292e;text-decoration: none;">
									<i class="fa fa-github"></i>
									<b class="connectText">Github互联<br></b>
								</a>
								<a style="text-decoration: none;">
									<img src="https://id.xshgzs.com/resource/images/weixinWork.png">
									<b class="connectText">企业微信扫码<br></b>
								</a>
								<a style="text-decoration: none;">
									<img src="https://id.xshgzs.com/resource/images/gzlib_origin.jpg" style="border-radius:50%;">
									<b class="connectText">广州图书馆互联<br></b>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.登陆面板 -->

	<div class="page-copy">
		<center>
			<!-- 页脚版权 -->
			<p style="font-weight:bold;font-size:18px;line-height:26px;">
				&copy; <a href="https://www.xshgzs.com?from=sso3" target="_blank" style="font-size:21px;">生蚝科技</a> 2014-2019
				<a style="color:#07C160" onclick='showWXCode()'><i class="fa fa-weixin fa-lg" aria-hidden="true"></i></a>
				<a style="color:#FF7043" onclick='launchQQ()'><i class="fa fa-qq fa-lg" aria-hidden="true"></i></a>
				<a style="color:#29B6F6" href="mailto:master@xshgzs.com"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></a>
				<a style="color:#AB47BC" href="https://github.com/OysterTech" target="_blank"><i class="fa fa-github fa-lg" aria-hidden="true"></i></a>

				<br>

				All Rights Reserved.<br>
				<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:black;">粤ICP备19018320号-1</a><br><br>
			</p>
			<!-- ./页脚版权 -->
		</center>
	</div>

	<div class="modal fade" id="tipsModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
				</div>
				<div class="modal-body">
					<font color="red" style="font-weight:bolder;font-size:24px;text-align:center;">
						<p id="tips"></p>
					</font>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" onclick='isAjaxing=0;$("#tipsModal").modal("hide");'>返回 &gt;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="wxModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">微信公众号二维码</h3>
				</div>
				<div class="modal-body">
					<center><img src="https://www.xshgzs.com/resource/index/images/wxOfficialAccountQRCode.jpg" style="width:85%"></center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick='$("#wxModal").modal("hide");'>关闭 &gt;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
</html>