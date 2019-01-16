<?php
/**
 * @name 生蚝科技统一身份认证平台-第三方登录-企业微信
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-30
 * @version 2019-01-12
 */
require_once '../include/public.func.php';

$code=isset($_GET['code'])&&$_GET['code']!=""?$_GET['code']:showError("参数缺失！");
define("CORPID","");
define("CORPSECRET","");

// 获取Access_token
$accessTokenData=http_post("https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".CORPID."&corpsecret=".CORPSECRET);
$accessTokenData=json_decode($accessTokenData,TRUE);
if($accessTokenData['errcode']==-1){
	showError("系统繁忙-A1");
}elseif($accessTokenData['errcode']==40003){
	showError("用户不存在-A403");
}elseif($accessTokenData['errcode']!=0){
	showError("系统错误-A0");
}else{
	$accessToken=$accessTokenData['access_token'];
}

// 获取用户ID
$userInfo=http_post('https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$accessToken.'&code='.$code);
$userInfo=json_decode($userInfo,TRUE);
if($userInfo['errcode']==-1){
	showError("系统繁忙-U1");
}elseif($userInfo['errcode']==40003){
	showError("用户不存在-U403");
}elseif($userInfo['errcode']!=0){
	showError("系统错误-U0");
}else{
	$thirdId=strtolower($userInfo['UserId']);
}

// 获取用户详细资料
/*$userDetailInfo=http_post('https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token='.$accessToken.'&userid='.$userId);
$userDetailInfo=json_decode($userDetailInfo,TRUE);
if($userDetailInfo['errcode']==-1){
	showError("系统繁忙-D1");
}elseif($userDetailInfo['errcode']==40003){
	showError("用户不存在-D403");
}elseif($userDetailInfo['errcode']!=0){
	showError("系统错误-D0");
}else{	
	//die(var_dump($userDetailInfo));
	$userId=strtolower($userId);
}*/

$unionInfo=PDOQuery($dbcon,"SELECT * FROM third_user WHERE method='github' AND third_id=?",[$thirdId],[PDO::PARAM_STR]);

if($unionInfo[1]!=1){
	setSess("third_name","workwechat");
	setSess("third_thirdId",$thirdId);
	header("location:bindUser.php");
}else{
	PDOQuery($dbcon,"UPDATE third_user SET last_login=? WHERE method='github' AND third_id=?",[date("Y-m-d H:i:s"),$thirdId],[PDO::PARAM_STR,PDO::PARAM_STR]);

	$appId=getSess("appId");
	$userId=$unionInfo[0][0]['user_id'];
	$userQuery=PDOQuery($dbcon,"SELECT * FROM user WHERE id=?",[$userId],[PDO::PARAM_INT]);

	if($userQuery[1]!=1){
		showError("当前用户绑定的通行证账号已被注销！");
	}else{
		$userInfo=$userQuery[0][0];
		$token=sha1(md5($appId).time());
		setSess(['isLogin'=>1,'token'=>$token,'userName'=>$userInfo['user_name'],'nickName'=>$userInfo['nick_name'],'role'=>$userInfo['role'],'user_id'=>$userId]);

		// 校验是否有权限
		$appPermission=explode(",",$userInfo['app_permission']);
		$appInfo=PDOQuery($dbcon,"SELECT id,name FROM app WHERE app_id=?",[$appId],[PDO::PARAM_STR]);

		if($appId!="" && $appInfo[1]!=1){
			// 没有此应用
			die('<script>alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");window.location.href="'.ROOT_PATH.'main.php";</script>');
		}elseif($appId!="" && in_array($appInfo[0][0]['id'],$appPermission)!=true){
			// 没有权限访问
			die('<script>alert("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！");window.location.href="'.ROOT_PATH.'main.php";</script>');
		}else{
			// 有权限，跳转回应用登录页
			if($appId==""){
				$appInfo[0][0]['id']=0;
				$appInfo[0][0]['name']="SSO中心";
			}

			$tokenQuery=addLoginToken($dbcon,$token,$userId);
			$addLog=PDOQuery($dbcon,"INSERT INTO log(user_id,app_id,method,content,ip) VALUES (?,?,'企业微信扫码登录','登录-".$appInfo[0][0]['name']."',?)",[$userId,$appInfo[0][0]['id'],getIP()],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_STR]);

			if($tokenQuery===TRUE) die(header("location:".getSess("returnUrl")."?token=".$token));
			else showError("系统错误-TKN");
		}
	}
}


function showError($content){
	die('<script>alert("'.$content.'\n请重新扫码或使用密码登录！\n\n欢迎您联系技术支持以获得更多帮助！");history.go(-1);</script>');
}


function http_post($url,$data=""){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13');
	$result=curl_exec($ch);
	curl_close($ch);
	
	return $result;
}
?>