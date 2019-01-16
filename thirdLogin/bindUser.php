<?php
/**
 * @name 生蚝科技统一身份认证平台-第三方绑定用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-12
 * @version 2019-01-12
 */

require_once '../include/public.func.php';

if(getSess("third_name")!=NULL && getSess("third_thirdId")!=NULL){
	$thirdId=getSess("third_thirdId");
	switch(getSess("third_name")){
		case "github":
			$thirdCNName="Github";
			break;
		case "workwechat":
			$thirdCNName="企业微信";
			break;
		default:
			alertDie("非法第三方服务商！",1);
			break;
	}
}else{
	alertDie("请求内容缺失！请重试！".getSess("third_thirdId"),1);
}


function alertDie($alertContent,$goBack=0){
	$alert='alert("'.$alertContent.'");';
	$script="<script>".$alert;
	$script.=$goBack==1?"window.location.href='".ROOT_PATH."';":"window.location.href='bindUser.php';";
	$script.="</script>";

	die($script);
}


if(isset($_POST[SESSION_PREFIX."bindUser_userName"]) && isset($_POST[SESSION_PREFIX."bindUser_password"])){
	$userName=$_POST[SESSION_PREFIX."bindUser_userName"];
	$password=$_POST[SESSION_PREFIX."bindUser_password"];

	$userInfoQuery=PDOQuery($dbcon,"SELECT * FROM user WHERE user_name=?",[$userName],[PDO::PARAM_STR]);
	if($userInfoQuery[1]!=1) alertDie("用户不存在！");
	else $userInfo=$userInfoQuery[0][0];

	$vaild=checkPassword($password,$userInfo['password'],$userInfo['salt']);

	if($vaild==TRUE){
		$sql="INSERT INTO third_user(user_id,method,third_id) VALUES (?,?,?)";
		$query=PDOQuery($dbcon,$sql,[$userInfo['id'],getSess("third_name"),$thirdId],[PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR]);
		
		if($query[1]==1){
			setSess(["third_name","third_thirdId"],[NULL,NULL]);
			$appId=getSess("appId");
			$token=sha1(md5($appId).time());
			setSess(['isLogin'=>1,'token'=>$token,'userName'=>$userName,'nickName'=>$userInfo['nick_name'],'role'=>$userInfo['role_id'],'user_id'=>$userInfo['id'],'unionId'=>$userInfo['union_id']]);

			// 校验是否有权限
			$appPermission=explode(",",$userInfo['app_permission']);
			$appInfo=PDOQuery($dbcon,"SELECT id,name FROM app WHERE app_id=?",[$appId],[PDO::PARAM_STR]);

			if($appId!="" && $appInfo[1]!=1){
				// 没有此应用
				alertDie("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！",1);
			}elseif($appId!="" && in_array($appInfo[0][0]['id'],$appPermission)!=true){
				// 没有权限访问
				alertDie("登录成功~\n当前用户暂无访问此应用权限！\n即将跳转至平台用户中心！",1);
			}else{
				// 有权限，跳转回应用登录页
				if($appId==""){
					$appInfo[0][0]['id']=0;
					$appInfo[0][0]['name']="SSO中心";
				}

				$tokenQuery=addLoginToken($dbcon,$token,$userInfo['id']);
				$addLog=PDOQuery($dbcon,"INSERT INTO log(user_id,app_id,method,content,ip) VALUES (?,?,'Github互联登录','登录-".$appInfo[0][0]['name']."',?)",[$userInfo['id'],$appInfo[0][0]['id'],getIP()],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_STR]);

				if($tokenQuery===TRUE) die(header("location:".getSess("returnUrl")."?token=".$token));
				else alertDie("系统错误-TKN");
			}
		}else{
			alertDie("绑定失败！请联系管理员！");
		}
	}else{
		alertDie("用户名或密码错误！");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>登录 / ITRClub统一身份认证平台</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.bootcss.com/admin-lte/2.4.8/css/AdminLTE.min.css">
</head>
<body class="hold-transition register-page" style="background-color:#66CCFF">
	<div class="register-box" style="margin:20px auto;">
		<div class="register-logo">
			<a href="https://www.itrclub.com"><img src="https://www.itrclub.com/resource/index/img/logo.png" style="width:40%"></a>
		</div>

		<div class="register-box-body">
			<p class="login-box-msg" style="font-size:16px;"><?=$thirdCNName;?>帐号绑定通行证用户</p>

			<form method="post">
				<div class="form-group has-feedback">
					<input type="text" name="<?=SESSION_PREFIX;?>bindUser_userName" class="form-control" placeholder="通行证用户名">
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" name="<?=SESSION_PREFIX;?>bindUser_password" class="form-control" placeholder="通行证密码">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<button type="submit" class="btn btn-success btn-block btn-flat">确 认 绑 定 &gt;</button>
			</form>
		</div>
		<!-- /.form-box -->
	</div>
	<!-- /.register-box -->

	<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
