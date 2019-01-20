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
	die(header("location:/logout/".urlencode('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])));
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
