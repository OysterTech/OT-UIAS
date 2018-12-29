<?php
/**
 * @name XX应用-SSO登录处理页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-01
 * @version 2018-12-29
 */
require_once 'include/public.func.php';

$appId="otsa_a8868b87c9ea27d624c4";
$returnUrl=ROOT_PATH."ssoLogin.php";
$ssoLoginUrl="https://ssouc.xshgzs.com/login.php?appId=".$appId."&returnUrl=".urlencode($returnUrl);
$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:toSSOLogin($ssoLoginUrl);

$postData=array("token"=>$token,"appId"=>$appId,"returnUrl"=>$returnUrl);
$ch=curl_init("https://ssouc.xshgzs.com/api/getUserInfo.php");
curl_setopt($ch,CURLOPT_HEADER,false);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$output=curl_exec($ch);
curl_close($ch);

if($output!==FALSE){
	$data=json_decode(substr($output,3),TRUE);

	if($data['code']!=200){toSSOLogin($ssoLoginUrl);}

	echo "CURL: ".var_dump($data)."<br>";
}else{
	echo "CURL Error:".curl_error($ch);
}


function toSSOLogin($ssoLoginUrl){
	die(header("location:".$ssoLoginUrl));
}
?>
<html>
<head>
	<title>测试2SSO登录 / 生蚝科技</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
</head>

<body>
	
<p id="loginTips">正在登录，请稍候……</p>

</body>
</html>
