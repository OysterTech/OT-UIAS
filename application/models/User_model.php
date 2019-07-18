<?php
/**
 * @name 生蚝科技统一身份认证平台-M-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-20
 * @version 2019-07-18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public $userInfoFields;

	public function __construct() {
		parent::__construct();
		$this->userInfoFields=['id','union_id','user_name','nick_name','app_permission','role_id','phone','email'];
	}


	/**
	 * 通过用户名获取用户资料
	 * @param  string $userName 用户名
	 * @return array            用户资料
	 */
	public function getUserInfoByUserName($userName='')
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
	 * 用户登录验证
	 * @param String 用户密码
	 * @param String 用户Id
	 * @param String 用户名
	 * @return String 验证状态码
	 */
	public function validateUser($pwd,$userId=0,$userName="")
	{
		$sql1="SELECT salt,password,status FROM user WHERE id=? OR user_name=?";
		$query1=$this->db->query($sql1,[$userId,$userName]);
		
		if($query1->num_rows()!=1){
			return 404;
		}
		
		$info=$query1->result_array();
		$status=$info[0]['status'];
		$salt=$info[0]['salt'];
		$pwd_indb=$info[0]['password'];

		if(sha1(md5($pwd).$salt)==$pwd_indb){
			return 200;
		}elseif($status==0){
			return -1;
		}else{
			return 403;
		}
	}


	/**
	 * 自定义查询条件以获取用户资料
	 * @param  array $conditions 查询条件
	 * @return array             查询条件
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
