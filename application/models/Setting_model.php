<?php
/**
* @name 生蚝科技RBAC开发框架-M-系统配置
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-03-05
* @version 2018-03-14
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 保存系统配置
	 * @param String 配置名称
	 * @param String 配置值
	 */
	public function save($name,$value)
	{
		$nowTime=date("Y-m-d H:i:s");
		
		$sql="UPDATE setting SET value=?,update_time=? WHERE name=?";
		$query=$this->db->query($sql,[$value,$nowTime,$name]);

		if($this->db->affected_rows()==1){
			$this->Log_model->create("系统","修改系统配置：".$name."|".$value);
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * 获取指定系统配置值
	 * @param String 配置名
	 * @return String 配置值
	 */
	public function get($name)
	{
		$sql="SELECT value FROM setting WHERE name=?";
		$query=$this->db->query($sql,[$name]);
		
		if($query->num_rows()==1){
			$list=$query->result_array();
			return $list[0]['value'];
		}else{
			return NULL;
		}
	}
	
	
	/**
	 * 获取所有系统配置
	 * @return Array 所有系统配置
	 */
	public function list()
	{
		$sql="SELECT * FROM setting";
		$query=$this->db->query($sql,[]);
		$list=$query->result_array();
		return $list;
	}

}
