<?php
/**
 * @name 生蚝科技统一身份认证平台-C-API-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-20
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_User extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function resetPassword()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) $this->ajax->returnData(403,"no Permission");

		$userId=isset($_POST['userId'])&&$_POST['userId']!=""?$_POST['userId']:$this->ajax->returnData(0,"lack Param");
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) $this->ajax->returnData(400,"cannot Operate own");

		$password=mt_rand(12345678,98765432);
		$salt=getRanSTR(8);
		$hash=sha1(md5($password).$salt);

		$query=$this->db->update('user',array('password'=>$hash,'salt'=>$salt),array('id'=>$userId));
		if($query==true) $this->ajax->returnData(200,"success",['password'=>$password]);
		else $this->ajax->returnData(500,"database Error");
	}


	public function delete()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) $this->ajax->returnData(403,"no Permission");

		$userId=isset($_POST['userId'])&&$_POST['userId']!=""?$_POST['userId']:$this->ajax->returnData(0,"lack Param");
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) $this->ajax->returnData(400,"cannot Operate own");

		$query=$this->db->delete('user',array('id'=>$userId));
		if($query==true) $this->ajax->returnData(200,"success");
		else $this->ajax->returnData(500,"database Error");
	}


	public function getAllUser()
	{
		$query=$this->db->query("SELECT a.id,a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,b.name AS roleName FROM user a,role b WHERE a.role_id=b.id");
		$this->ajax->returnData(200,"success",['list'=>$query->result_array()]);
	}


	public function checkDuplicate($method='',$value='')
	{
		if($method=='' || $value==''){
			$this->ajax->returnData(0,"lack Param");
		}else if($method=="userName"){
			$this->db->where('user_name', $value);
		}else if($method=="phone"){
			$this->db->where('phone', $value);
		}else{
			$this->ajax->returnData(1,"Invaild Method");
		}

		$query=$this->db->get('user');

		if($query->num_rows()!=1){
			$this->ajax->returnData(200,"no User");
		}else{
			$this->ajax->returnData(403,"have User");
		}
	}


	public function updateUserInfo()
	{
		$userId=isset($_POST['userId'])&&$_POST['userId']!=""?$_POST['userId']:$this->session->userdata($this->sessPrefix.'user_id');
		$userName=$this->input->post('userName');
		$nickName=$this->input->post('nickName');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		$roleId=isset($_POST['roleId'])&&$_POST['roleId']!=""?$_POST['roleId']:0;

		// 检查是否有重复
		$this->db->where('user_name',$userName);
		$this->db->where('id!=',$userId);
		$query1=$this->db->get('user');
		if($query1->num_rows()!=0){
			$this->ajax->returnData(1,"have UserName");
		}

		$this->db->where('nick_name',$nickName);
		$this->db->where('id!=',$userId);
		$query2=$this->db->get('user');
		if($query2->num_rows()!=0){
			$this->ajax->returnData(2,"have nickName");
		}

		$this->db->where('phone',$phone);
		$this->db->where('id!=',$userId);
		$query3=$this->db->get('user');
		if($query3->num_rows()!=0){
			$this->ajax->returnData(3,"have Phone");
		}

		// 修改资料
		if($roleId>0){
			$query4=$this->db->update('user',array('role_id'=>$roleId),array('id'=>$userId));
		}else{
			$query4=$this->db->update('user',array('user_name'=>$userName,'nick_name'=>$nickName,'phone'=>$phone,'email'=>$email),array('id'=>$userId));
		}

		if($query4==true){
			$this->ajax->returnData(200,"success");
		}else{
			$this->ajax->returnData(5,"changeError");
		}
	}


	public function getUserInfo()
	{
		$method=isset($_POST['method'])&&$_POST['method']!=""?$_POST['method']:$this->ajax->returnData(0,"lack Param");

		if($method=="unionId"){
			$unionId=isset($_POST['unionId'])&&strlen($_POST['unionId'])==8?$_POST['unionId']:$this->ajax->returnData(0,"lack Param");
			$query=$this->db->query('SELECT a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.union_id=? AND a.role_id=b.id',[$unionId]);

			if($query->num_rows()!=1){
				$this->ajax->returnData(1,'no User');
			}else{
				$list=$query->result_array();
				$this->ajax->returnData(200,'success',['userInfo'=>$list[0]]);
			}
		}else if($method=="api"){
			$token=isset($_POST['token'])&&$_POST['token']!=""?$_POST['token']:$this->ajax->returnData(0,"lack Param");
			$this->db->select('user_id');
			$query=$this->db->get_where('login_token',array('token'=>$token));

			if($query->num_rows()==1){
				$list=$query->result_array();
				$userId=$list[0]['user_id'];
				$userInfoQuery=$this->db->query('SELECT a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.id=? AND a.role_id=b.id',[$userId]);

				if($userInfoQuery->num_rows()!=1){
					$this->ajax->returnData(1,"no User");
				}else{
					$userInfoList=$userInfoQuery->result_array();
					$this->db->where('token',$token);
					$this->db->delete('login_token');
					$this->ajax->returnData(200,"success",['userInfo'=>$userInfoList[0]]);
				}
			}else{
				$this->ajax->returnData(403,"failed To Auth");
			}
		}else{
			$this->ajax->returnData(2,"Invaild Method");
		}
	}
}
