<?php
/**
 * @name 生蚝科技统一身份认证平台-故障页面
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-21
 * @version 2018-12-21
 */

require_once 'include/public.func.php';

$mod=isset($_GET['mod'])&&$_GET['mod']!=""?$_GET['mod']:"";
?>
<html>
<head>
	<title>生蚝科技统一身份认证平台</title>
	<?php include 'include/header.php'; ?>
</head>
<body style="font-family:Microsoft YaHei; background-color:#f9f9f9;margin-top: 20px;">
<center>
	<img src="<?=IMG_PATH;?>error.png" style="width:50%;">
	<h1>
		
		<?php if($mod=="appInfo"){ ?>
		APP信息不存在！<br>
		<?php }else{ ?>
		系统故障！<br>
		<?php } ?>
			
		请联系管理员！
	</h1>
</center>
	
<?php include 'include/footer.php'; ?>

</body>
</html>
