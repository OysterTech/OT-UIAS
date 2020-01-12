/**
* getURLParam 获取指定URL参数
* @param  String 参数名称
* @return String 参数值
**/
function getURLParam(name){
	var reg = new RegExp("(^|&)"+name+"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r!=null){return decodeURI(r[2]);}
	else{return null;}
}


/**
* showCNNum 显示汉字的数字
* @param INT 一位数字
**/
function showCNNum(number){
	rtn="";

	if(number=="1") rtn="一";
	else if(number=="2") rtn="二";
	else if(number=="3") rtn="三";
	else if(number=="4") rtn="四";
	else if(number=="5") rtn="五";
	else if(number=="6") rtn="六";
	else if(number=="7") rtn="七";
	else if(number=="8") rtn="八";
	else if(number=="9") rtn="九";
	else if(number=="0") rtn="零";

	return rtn;
}


/**
* isInArray 检测指定字符串是否存在于数组
* @param Array  待检测的数组
* @param String 指定字符串
**/
function isInArray(arr,val){
	length=arr.length;

	if(length>0){
		for(var i=0;i<length;i++){
			if(arr[i] == val){
				return i;
			}
		}
		return false;
	}else{
		return false;
	}
}


/**
* isChn 字符串是否全为汉字
* @param String 待检测的字符串
**/
function isChn(str){ 
	var reg = /^[\u4E00-\u9FA5]+$/; 
	if(!reg.test(str)){ 
		return 0; 
	}else{
		return 1;
	}
}


/**
* setCookie 设置Cookie值
* @param String Cookie名称
* @param String Cookie值
**/
function setCookie(name,value)
{
  var Days = 30;// Cookie有效天数
  var exp = new Date();
  exp.setTime(exp.getTime() + Days*24*60*60*1000);
  document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}


/**
* getCookie 获取Cookie
* @param  String Cookie名称
* @return String Cookie值
**/
function getCookie(name)
{
	var arr;
	var reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg)){
		return unescape(arr[2]);
	}else{
		return null;
	}
}


/**
* delCookie 删除Cookie
* @param String Cookie名称
**/
function delCookie(name){
	var exp = new Date();
	exp.setTime(exp.getTime()-1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


/**
* lockScreen 屏幕锁定，显示加载图标
**/
function lockScreen(content=""){
	$('body').append(
		'<div class="loadingwrap" id="loadingwrap"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div><br><font style="color:yellow;font-size:24px;font-weight:bold;">'+content+'</font></div></div>'
		);
}


/**
* unlockScreen 屏幕解锁
**/
function unlockScreen(){
	// 0.3s后再删除，防止闪现
	setTimeout(function(){
		$('#loadingwrap').remove();
	},300);	
}


/**
* showModalTips 模态框显示提醒消息
* @param String 消息内容
* @param String 消息标题
**/
function showModalTips(msg,title='温馨提示'){
	$("#tips").html(msg);
	$("#tipsTitle").html(title);
	$("#tipsModal").modal("show");
}


function arrayToObject(array){
	b={};
	array.map(function(e,i){
		b[i]=e;
	});

	return b;
}