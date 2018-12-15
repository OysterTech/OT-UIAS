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
