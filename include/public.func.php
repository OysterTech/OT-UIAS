<?php
/**
 * @name 生蚝科技统一身份认证平台-公用函数库
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-11-30
 * @version 2018-12-23
 */

session_start();
require_once 'PDOConn.php';

define('ROOT_PATH','https://ssouc.xshgzs.com/');
define('JS_PATH',ROOT_PATH.'resource/js/');
define('IMG_PATH',ROOT_PATH.'resource/image/');
define('CSS_PATH',ROOT_PATH.'resource/css/');
define('SESSION_PREFIX','OTSSO_');


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
 * returnAjaxData 返回JSON数据
 * @param  string 状态码
 * @param  string 待返回的数据
 * @return JSON   已处理好的JSON数据
 */
function returnAjaxData($code,$msg,$data="")
{
	$ret=array('code'=>$code,'message'=>$msg,'data'=>$data);
	return json_encode($ret);
}


/**
 * 获取session值
 * @param  string $sessionName session名称
 * @return string              值（无则NULL）
 */
function getSess($sessionName="")
{
	if(isset($_SESSION[SESSION_PREFIX.$sessionName])){
		if($_SESSION[SESSION_PREFIX.$sessionName]==""){
			return NULL;
		}else{
			return $_SESSION[SESSION_PREFIX.$sessionName];
		}
	}else{
		return NULL;
	}
}


/**
 * setSess 设置Session
 * @param string/array $sessionName 设置session的名称
 * @param string/array $value       session对应值
 * @return bool                     设置结果
 */
function setSess($sessionName="",$value="")
{
	if(is_array($sessionName)==true){
		foreach($sessionName as $name=>$value){
			$_SESSION[SESSION_PREFIX.$name]=$value;
		}
		return true;
	}else if(is_array($sessionName)==false && is_array($value)==false){
		$_SESSION[SESSION_PREFIX.$sessionName]=$value;
		return true;
	}else{
		return false;
	}
}


/**
 * gotoUrl 跳转
 * @param  string $url 欲跳转的路径（仅需文件路径）
 */
function gotoUrl($url="")
{
	die(header("location:".$url));
}


/**
 * checkPassword 校验密码有效性
 * @param string $password      原密码
 * @param string $password_indb 存于数据库的密码
 * @param string $salt          存于数据库的盐
 * @return bool                 校验结果
 */
function checkPassword($password,$password_indb,$salt){
	$hashPassword=sha1(md5($password).$salt);
	if($hashPassword===$password_indb){
		return true;
	}else{
		return false;
	}
}


/**
 * getIP 获取IP地址
 * @return string IP地址
 */
function GetIP(){
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
