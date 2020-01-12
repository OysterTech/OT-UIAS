var nowLoginMethod="password";
var pwdMethodBtnStyle="background-position:0 -57;";
var qrMethodBtnStyle="background-position:0 0;";
var checkQrStatus;
var isAjaxing=0;

function launchQQ(){
	if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
		window.location.href="mqqwpa://im/chat?chat_type=wpa&uin=571339406";
	}else{
		window.open("http://wpa.qq.com/msgrd?v=3&uin=571339406");
	}
}

function showWXCode(){
	$("#wxModal").modal('show');
}

// 监听模态框关闭事件
$(function (){
	$('#tipsModal').on('hidden.bs.modal',function (){
		isAjaxing=0;
	});
});

window.onload=function(){
	/********** ▼ 记住密码 ▼ **********/
	Remember=getCookie("rememberUserName");
	if(Remember!=null){
		$("#userName").val(Remember);
		$("#password").focus();
		$("#Remember").attr("checked",true);
	}else{
		$("#userName").focus();
	}
	/********** ▲ 记住密码 ▲ **********/

	localStorage.removeItem("allRoleInfo");
	if(isPhone()==true){
		//$(".login_form_title2").hide();
	}
}

function changeLoginMethod(){
	if(nowLoginMethod=="password"){
		nowLoginMethod="qr";
		loadWxLoginQrCode();
		$("#changeLoginMethodBtn").attr("style",pwdMethodBtnStyle);
		$("#userIcon").attr("style","display:none");
		$("#loginForm").attr("style","display:none");
		$("#loginQrcode").attr("style","font-size:17px;");
	}else if(nowLoginMethod=="qr"){
		nowLoginMethod="password";
		stopCheckStatus();
		$("#changeLoginMethodBtn").attr("style",qrMethodBtnStyle);
		$("#userIcon").attr("style","");
		$("#loginForm").attr("style","");
		$("#loginQrcode").attr("style","display:none");
	}
}


function loadWxLoginQrCode(){
	width=$("#loginForm").width();
	width-=80;
	if(width<280) width=280;
	$("#qrCodeImg").attr('src','<?=$this->API_PATH;?>wxmp/getQrCode/<?=session_id();?>/<?=$appId;?>/'+width);
	$("#qrCodeImg").attr('style','width:'+width+'px;height:'+width+'px;');
	$("#wxCodeStatusDiv").attr('style','display:none');
	startCheckStatus();
}


function startCheckStatus(){
	checkQrStatus = window.setInterval(function() {
		$.ajax({
			url:"<?=$this->API_PATH;?>wxmp/checkStatus",
			dataType:"json",
			success:function(ret){
				console.log(ret);
				if(ret.code==200){
					status=ret.data['status'];

					switch(status){
						case "-1":
							// 用户主动取消
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-warn weui-icon_msg-primary');
							$("#wxCodeStatusContent").html('用户已取消登录<br>请点击“刷新”重新扫码');
							break;
						case "0":
							// 过期
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-warn weui-icon_msg');
							$("#wxCodeStatusContent").html('小程序码已超时失效<br>请点击“刷新”重新扫码');
							break;
						case "1":
							// 正常
							break;
						case "2":
							// 已经被扫
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-info weui-icon_msg');
							$("#wxCodeStatusContent").html('已成功扫码<br>请在微信小程序授权登录');
							break;
						case "3":
							// 已授权
							window.clearInterval(checkQrStatus);
							$("#qrCodeImg").attr('src','');
							$("#qrCodeImg").attr('style','width:100%;height:0px');
							$("#wxCodeStatusDiv").attr('style','');
							$("#wxCodeStatusIcon").attr('class','weui-icon-success weui-icon_msg');
							$("#wxCodeStatusContent").html('正在跳转，请稍候！');
							setTimeout(function(){
								window.location.href="<?=$redirectUrl;?>";
							},1500);
							break;
						default:
							break;
					}
				}else if(ret.code==403){
					showModalTips("暂未生成小程序码！<br>请点击“刷新”生成！");
					stopCheckStatus();
					return false;
				}else if(ret.code==404){
					showModalTips("小程序码信息不存在！<br>请点击“刷新”生成！");
					stopCheckStatus();
					return false;
				}
			}
		})
	}, 2000);
}


function stopCheckStatus(){
	window.clearInterval(checkQrStatus);
	$("#qrCodeImg").attr('src','');
}


function toLogin(){
	// 防止多次提交
	if(isAjaxing==1){
		return false;
	}

	isAjaxing=1;
	lockScreen();
	appId=$("#<?=$this->sessPrefix;?>appId").val();
	userName=$("#<?=$this->sessPrefix;?>userName").val();
	password=$("#<?=$this->sessPrefix;?>password").val();

	/********** ▼ 记住密码 ▼ **********/
	Remember=$("input[type='checkbox']").is(':checked');
	if(Remember==true){
		setCookie("<?=$this->sessPrefix;?>RmUN",userName);
	}else{
		delCookie("<?=$this->sessPrefix;?>RmUN");
	}
	/********** ▲ 记住密码 ▲ **********/
	
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		showModalTips("请正确输入用户名！");
		return false;
	}
	if(password.length<6 || password.length>20){
		unlockScreen();
		showModalTips("请正确输入密码！");
		return false;
	}


	$.ajax({
		url:"<?=base_url('user/toLogin');?>",
		type:"post",
		data:{"appId":appId,"userName":userName,"password":password},
		dataType:"json",
		error:function(e){
			unlockScreen();
			showModalTips("服务器错误！"+e.status);
			console.log(e);
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#pwd").removeAttr("disabled");

			if(ret.code==200){
				localStorage.setItem('allRoleInfo',ret.data['allRoleInfo']);
				localStorage.setItem('jwtToken',ret.data['jwtToken']);
				
				if(appId=="" && getURLParam("service")!=null){url=decodeURIComponent(getURLParam("service"));}
				else if(appId==""){url="<?=base_url('dashborad');?>";}
				else{url=ret.data['redirectUrl'];}

				window.location.href=url;
				return true;
			}else if(ret.code==1){
				showModalTips("当前用户被禁用！<br>请联系管理员！");
				return false;
			}else if(ret.code==4031){
				showModalTips("用户名或密码错误！");
				return false;
			}else if(ret.code==4032){
				unlockScreen();
				alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");
				window.location.href="<?=base_url('dashborad');?>";
				return false;
			}else if(ret.code==3){
				showModalTips("用户暂未激活！<br>请尽快进行激活！");
				return false;
			}else if(ret.code==2){
				showModalTips("获取角色信息失败！请联系管理员！");
				return false;
			}else{
				console.log(ret);
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}  
	});
}