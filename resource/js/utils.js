/**
 * getURLParam 获取URL参数
 * @param String 参数名称
 **/
function getURLParam(name){
	var reg = new RegExp("(^|&)"+name+"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r!=null){return decodeURI(r[2]);}
	else{return null;}
}


function showModalTips(content,title=""){
	$("#tips").html(content);
	$("#tipsModal").modal("show");
}


/**
 * lockScreen 屏幕锁定
 **/
function lockScreen(){
$('body').append(
	'<div id="lockContent" style="opacity: 0.8; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;left:50%; margin-left:-20px; top:50%; margin-top:-20px;">'+
	'<div><i class="fa fa-refresh fa-spin fa-5x fa-fw"></i></div>'+
	'</div>'+
	'<div id="lockScreen" style="background: #000; opacity: 0.35; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;">'+
	'</div>'
	);
}


/**
 * unlockScreen 屏幕解锁
 **/
function unlockScreen(){
	// 延时，更逼真，不会闪现
	sleep(150);
	$('#lockScreen').remove();
	$('#lockContent').remove();
}


/**
 * sleep 延时
 * @param String 需要延时的毫秒数(1s=1000)
 **/
function sleep(numberMillis) {
	var now = new Date();
	var exitTime = now.getTime() + numberMillis;
	while (true){
		now = new Date();
		if (now.getTime() > exitTime){
			return;
		}
	}
}


/**
* -----------------------------------
* setCookie 设置Cookie
* -----------------------------------
* @param String Cookie名称
* @param String Cookie内容
* -----------------------------------
**/
function setCookie(name,value)
{
	var Days = 30;// Cookies有效天数
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}


/**
* -----------------------------------
* getCookie 获取Cookie
* -----------------------------------
* @param String Cookie名称
* -----------------------------------
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
* -----------------------------------
* delCookie 删除Cookie
* -----------------------------------
* @param String Cookie名称
* -----------------------------------
**/
function delCookie(name){
	var exp = new Date();
	exp.setTime(exp.getTime()-1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


function arrayToObject(array){
	b={};
	array.map(function(e,i){
		b[i]=e;
	});

	return b;
}