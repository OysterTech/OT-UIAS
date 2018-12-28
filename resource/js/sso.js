/**
 * @name 生蚝科技统一身份认证平台-JS-SSO
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-20
 * @version 2018-12-21
 */

var OTSSO={
	ssoServiceUrl:"https://ssouc.xshgzs.com/",

	getUserInfo:function(token,appId,returnUrl){
		$.ajax({
			url:OTSSO.ssoServiceUrl+"api/getUserInfo.php",
			data:{"token":token,"appId":appId,"returnUrl":returnUrl},
			dataType:"jsonp",
			jsonp: "callback",
			jsonpCallback: 'OTSSO.returnUserInfo',
			async:false
		});
	},

	returnUserInfo:function(ret){
		if(ret.code==200){
			userInfo=ret.data['userInfo'];
			alert("认证成功！用户名:"+userInfo['nickName']);
			var exp = new Date();
			exp.setTime(exp.getTime()+0.5*24*60*60*1000);
			document.cookie="OTSSO_userInfo="+JSON.stringify(userInfo)+";expires="+exp.toGMTString();
		}else if(ret.code==403){
			window.location.href=OTSSO.ssoServiceUrl+"login.php?appId="+appId+"&returnUrl="+encodeURIComponent(returnUrl);
			return false;
		}else if(ret.code==0){
			showModalTips("参数缺失！请联系技术支持！");
			return false;
		}else{
			showModalTips("未知错误！请提交错误码["+ret.code+"]并联系技术支持");
			return false;
		}
	}
};
