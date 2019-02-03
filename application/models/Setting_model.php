<?php
/**
 * @name 生蚝科技统一身份认证平台-M-系统配置
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-22
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
		$sql="UPDATE setting SET value=? WHERE name=?";
		$this->db->where('name',$name);
		$query=$this->db->update('setting',['value'=>$value]);

		if($query===true){
			//$this->Log_model->create("系统","修改系统配置：".$name."|".$value);
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
		$this->db->select('value');
		$query=$this->db->get_where('setting',['name'=>$name]);
		
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
		$query=$this->db->get('setting');
		$list=$query->result_array();
		return $list;
	}
}
