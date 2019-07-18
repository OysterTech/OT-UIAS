<?php
define('APP_ID','');// 应用ID
define('REDIRECT_URL','');// 回调Url（需要协议头，最后必须带"/"）
define('SSO_SERVER','');// SSO服务器域名（需要协议头，最后必须带"/"）
define('SSO_LOGIN_URL',SSO_SERVER.'user/login?appId='.APP_ID.'&redirectUrl='.urlencode(REDIRECT_URL));
define('SSO_API_URL',SSO_SERVER.'api/user/getUserInfo');

$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:die(header("location:".SSO_LOGIN_URL));
$postData=array('method'=>'api','token'=>$token,'appId'=>APP_ID,'redirectUrl'=>REDIRECT_URL);
$output=curl(SSO_API_URL,'post',$postData);
$data=json_decode($output,TRUE);

if($data['code']!=200) die(header("location:".SSO_LOGIN_URL));
else $userInfo=$data['data']['userInfo'];

// 显示用户信息
var_dump($userInfo);


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
?>