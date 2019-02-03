<?php
/**
 * @name 生蚝科技统一身份认证平台-M-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-26
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public $userInfoFields;

	public function __construct() {
		parent::__construct();
		$this->userInfoFields=['id','union_id','wx_open_id','user_name','nick_name','app_permission','role_id','phone','email','school'];
	}


	/**
	 * 通过用户名获取用户资料
	 * @param  string $userName 用户名
	 * @return array            用户资料
	 */
	public function getInfoByUserName($userName='')
	{
		$query=$this->db->get_where('user',['user_name'=>$userName]);
		
		if($query->num_rows()==1){
			$list=$query->result_array();
			return $list[0];
		}else{
			return array();
		}
	}


	/**
	 * 自定义查询条件以获取用户资料
	 * @param  array $conditions 查询条件
	 * @return array           c 查询条件
	 */
	public function getInfo($conditions=array())
	{
		foreach($conditions as $key=>$value){
			if(!in_array($key,$this->userInfoFields)){
				// 是否存在此字段
				unset($conditions[$key]);
			}else if($value=="" || $value==null){
				// 条件内容是否为空
				unset($conditions[$key]);
			}
		}

		if(count($conditions)>=1){
			$conditions=array_change_key_case($conditions);
		}else{
			return array();
		}
		
		$query=$this->db->get_where('user',$conditions);
		
		if($query->num_rows()==1){
			$list=$query->result_array();
			return $list[0];
		}else{
			return array();
		}
	}


	public function addLoginToken($token='',$userId='')
	{
		$expTime=date("Y-m-d H:i:s",strtotime("-15 minutes"));
		$this->db->where('create_time<=',$expTime);
		$query=$this->db->delete('login_token');

		if($query==false){
			return false;
		}else{
			$data=array(
				'token'=>$token,
				'ip'=>getIP(),
				'user_id'=>$userId
			);
			$add=$this->db->insert('login_token',$data);

			return $add;
		}
	}
}
