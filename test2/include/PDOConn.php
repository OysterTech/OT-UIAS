<?php
/**
 * ------------------------------------
 * 生蚝科技SSO系统 数据库操作PDO驱动
 * @Author Jerry Cheung
 * ------------------------------------
 */

$dbcon=null;
$dbms="mysql";
$host="localhost";
$database="ssouc";
$userName="root";
$passWord="";
$dsn="{$dbms}:host={$host};dbname={$database};charset=utf8";
try{
	$dbcon=new PDO($dsn,$userName,$passWord);
}catch(PDOException $e){
	die("Err.no:".$e->getMessage());
}


/**
* ------------------------------------
* PDOQuery PDO自定义函数
* ------------------------------------
* @param Object $dbconn   数据库连接对象
* @param String $sql      SQL语句,数据用?
* @param Array  $pararray 数据内容
* @param Array  $paratype 数据类型，与pararray同步，PDO::PARAM_INT等，详见PDO类
* ------------------------------------
*/
function PDOQuery($dbconn,$sql,$pararray=[],$paratype=[]){
	$dbo=$dbconn->prepare($sql);
	for($i=0;$i<count($pararray);$i++){
		// PDO绑定参数从1开始
		$dbo->bindParam($i+1,$pararray[$i],$paratype[$i]);
	}
	$dbo->execute();
	//return $dbo->errorInfo();
	return [$dbo->fetchAll(PDO::FETCH_ASSOC),$dbo->rowCount(),$dbo->errorInfo()];
}


/**
* ------------------------------------
* PDOQuery2 PDO自定义函数2
* ------------------------------------
* @param $dbconn  数据库连接对象
* @param $query   SQL语句,数据用?
* @param $paras   要查询的数据和数据类型，以二维数组方式传入，第一个索引为第几个数据，第二个索引中0为数据，1为数据类型
* ------------------------------------
*/
function PDOQuery2($dbconn,$query,$paras){
	$dbo=$dbconn->prepare($query);
	for($i=0;$i<count($paras);$i++){
		//pdo绑定参数从1开始
		$dbo->bindParam($i+1,$paras[$i][0],$paras[$i][1]);
	}
	$dbo->execute();
	return [$dbo->fetchAll(PDO::FETCH_ASSOC),$dbo->rowCount(),getColNames($dbo)];
}


/**
* ------------------------------------
* getColNames 获取SQL的列名
* ------------------------------------
* @param $pdost 传入一个PDOStatement对象
* ------------------------------------
*/
function getColNames($pdost){
 $count=$pdost->columnCount();
 $col=array();
 for($i=0;$i<$count;$i++){
  $col[$i]=$pdost->getColumnMeta($i)['name'];
 }
 return $col;
}
