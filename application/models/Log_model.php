<?php
/**
* @name 生蚝科技RBAC开发框架-M-Log日志
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-18
* @version 2018-08-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 向数据库写入系统操作记录
	 * @param String 操作类型
	 * @param String 操作内容
	 * @param String 操作者名称(默认空为自动获取)
	 */
	public function create($type,$content,$name="")
	{
		$ip=$this->input->ip_address();
		
		if($name==""){
			$name=$this->session->userdata($this->sessPrefix.'userName');
		}
		
		$sql="INSERT INTO log(type,content,user_name,create_ip) VALUES(?,?,?,?)";
		$query=$this->db->query($sql,[$type,$content,$name,$ip]);

		if($this->db->affected_rows()==1){
			return true;
		}else{
			return false;
		}
	}
}
