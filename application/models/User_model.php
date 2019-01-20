<?php
/**
 * @name 生蚝科技统一身份认证平台-M-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
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
