<?php

/**
 * gotoUrl 直接跳转到URL
 * @param  string $url 欲跳转到的网址
 */
function gotoUrl($url='')
{
	die(header('location:'.$url));
}


/**
 * getIP 获取IP地址
 * @return string IP地址
 */
function getIP()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif(!empty($_SERVER["REMOTE_ADDR"])){
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else{
		$cip = "0.0.0.0";
	}
	return $cip;
}


/**
 * logout 强行自动登出
 */
function logout()
{
	die(header("location:/logout/".urlencode($_SERVER['REQUEST_URI'])));
}


/**
 * getRanSTR 获取随机字母串
 * @param int    欲获取的随机字符串长度
 * @param 0|1|2  0:只要大写|1:只要小写|2:无限制
 * @return String 随机字符串
 */
function getRanSTR($length,$LettersType=2)
{
	if($LettersType==0){
		$str="ZXCVBNQWERTYASDFGHJKLUPM";
	}elseif($LettersType==1){
		$str="qwertyasdfghzxcvbnupmjk";
	}else{
		$str="qwertyZXCVBNasdfghQWERTYzxcvbnASDFGHupJKLnmUPjk";
	}

	$ranstr="";
	$strlen=strlen($str)-1;
	for($i=1;$i<=$length;$i++){
		$ran=mt_rand(0,$strlen);
		$ranstr.=$str[$ran];
	}

	return $ranstr;
}


/**
 * curl请求封装函数
 * @param  string  $url          请求URL
 * @param  string  $type         请求类型(get/post)
 * @param  array   $postData     需要POST的数据
 * @param  string  $postDataType POST数据类型(array/json)
 * @param  integer $timeout      超时秒数
 * @param  string  $userAgent    UserAgent
 * @return string                返回结果
 */
function curl($url,$type='get',$postData=array(),$postDataType='array',$timeout=5,$userAgent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'){
	if($url=='' || $timeout <=0){
		return false;
	}

	$ch=curl_init((string)$url);
	curl_setopt($ch,CURLOPT_HEADER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_TIMEOUT,(int)$timeout);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_USERAGENT,$userAgent);

	if($type=='post'){
		if($postData==array()){
			return false;
		}else if($postDataType=='json'){
			$postData=json_encode($postData);
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
		}

		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
	}

	$rtn=curl_exec($ch);
	if($rtn===false) $rtn=curl_errno($ch);
	curl_close($ch);

	return $rtn;
}
