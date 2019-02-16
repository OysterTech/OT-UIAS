<?php
/**
 * @name 生蚝科技统一身份认证平台-H-输入
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-02-11
 * @version 2019-02-16
 */

function inputGet($dataName="",$allowNull=0,$isAjax=0)
{
	if(isset($_GET[$dataName])){
		if($allowNull!=1 && $_GET[$dataName]==""){
			return $isAjax==1?returnAjaxData(0,'lack Parameter'):false;
		}else{
			return $_GET[$dataName];
		}
	}elseif($allowNull==1){
		return;
	}else{
		return $isAjax==1?returnAjaxData(1,'lack Parameter'):false;
	}
}


function inputPost($dataName="",$allowNull=0,$isAjax=0)
{
	if(isset($_POST[$dataName])){
		if($allowNull!=1 && $_POST[$dataName]==""){
			return $isAjax==1?returnAjaxData(0,'lack Parameter'):false;
		}else{
			return $_POST[$dataName];
		}
	}elseif($allowNull==1){
		return;
	}else{
		return $isAjax==1?returnAjaxData(0,'lack Parameter'):false;
	}
}


/**
 * 返回JSON数据
 * @param  string       状态码
 * @param  string       状态消息
 * @param  string/array 待返回的数据
 * @return JSON         已处理好的JSON数据
 */
function returnAjaxData($code,$msg,$data=""){
	$ret=array('code'=>$code,'message'=>$msg,'data'=>$data,'requestTime'=>time());
	die(json_encode($ret));
}
