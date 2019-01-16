<?php
/**
 * @name 生蚝科技统一身份认证平台-API-获取通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-12-31
 * @version 2019-01-07
 */

require_once '../../include/public.func.php';

$type=isset($_GET['type'])&&$_GET['type']!=""?$_GET['type']:die(returnAjaxData(0,"Lack Param"));
$userId=getSess("user_id")!=""?getSess("user_id"):die(returnAjaxData(403,"Not Login"));
$sql="SELECT a.*,b.nick_name AS publisher FROM notice a,user b WHERE a.publisher_id=b.id AND (receiver='0' OR receiver LIKE '%".$userId."%') ";

switch($type){
	case "navbar":
		$latestTime=date("Y-m-d H:i:s",strtotime("-1 month"));
		$sql.="AND a.create_time>='".$latestTime."' ORDER BY a.create_time DESC";
		break;
	case "dashborad":
		$sql.="ORDER BY a.create_time DESC LIMIT 0,10";
		break;
	case "list":
		break;
	case "detail":
		$id=isset($_GET['id'])&&$_GET['id']!=""?$_GET['id']:die(returnAjaxData(0,"Lack Param"));
	 $sql.="AND a.id=".$id;
	 break;
	default:
		die(returnAjaxData(1,"Invaild Type"));
}

$query=PDOQuery($dbcon,$sql);
die(returnAjaxData(200,"success",['list'=>$query[0]]));
