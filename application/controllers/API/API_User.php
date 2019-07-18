<?php
/**
 * @name 生蚝科技统一身份认证平台-C-API-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-07-18
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
		if($auth!=true) returnAjaxData(403,"no Permission");

		$userId=inputPost('userId',0,1);
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) returnAjaxData(400,"cannot Operate own");

		$password=mt_rand(12345678,98765432);
		$salt=getRanSTR(8);
		$hash=sha1(md5($password).$salt);

		$query=$this->db->update('user',array('password'=>$hash,'salt'=>$salt),array('id'=>$userId));
		
		if($query==true) returnAjaxData(200,"success",['password'=>$password]);
		else returnAjaxData(500,"database Error");
	}


	public function delete()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403,"no Permission");

		$userId=inputPost('userId',0,1);
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) returnAjaxData(400,"cannot Operate own");

		$query=$this->db->delete('user',array('id'=>$userId));
		if($query==true) returnAjaxData(200,"success");
		else returnAjaxData(500,"database Error");
	}


	public function getAllUser()
	{
		$query=$this->db->query('SELECT a.id,a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,b.name AS roleName FROM user a,role b WHERE a.role_id=b.id');
		returnAjaxData(200,'success',['list'=>$query->result_array()]);
	}


	public function checkDuplicate($method='',$value='')
	{
		if($method=='' || $value==''){
			returnAjaxData(0,"lack Parameter");
		}else if($method=="userName"){
			$this->db->where('user_name', $value);
		}else if($method=="phone"){
			$this->db->where('phone', $value);
		}else{
			returnAjaxData(1,"Invaild Method");
		}

		$query=$this->db->get('user');

		if($query->num_rows()!=1){
			returnAjaxData(200,"no User");
		}else{
			returnAjaxData(403,"have User");
		}
	}


	public function updateUserInfo()
	{
		$userId=isset($_POST['userId'])&&$_POST['userId']!=""?$_POST['userId']:$this->session->userdata($this->sessPrefix.'user_id');
		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleId=isset($_POST['roleId'])&&$_POST['roleId']!=""?$_POST['roleId']:0;

		// 检查是否有重复
		$this->db->where('user_name',$userName);
		$this->db->where('id!=',$userId);
		$query1=$this->db->get('user');
		if($query1->num_rows()!=0){
			returnAjaxData(1,"have UserName");
		}

		$this->db->where('nick_name',$nickName);
		$this->db->where('id!=',$userId);
		$query2=$this->db->get('user');
		if($query2->num_rows()!=0){
			returnAjaxData(2,"have nickName");
		}

		$this->db->where('phone',$phone);
		$this->db->where('id!=',$userId);
		$query3=$this->db->get('user');
		if($query3->num_rows()!=0){
			returnAjaxData(3,"have Phone");
		}

		// 修改资料
		if($roleId>0){
			$query4=$this->db->update('user',array('role_id'=>$roleId),array('id'=>$userId));
		}else{
			$query4=$this->db->update('user',array('user_name'=>$userName,'nick_name'=>$nickName,'phone'=>$phone,'email'=>$email),array('id'=>$userId));
		}

		if($query4==true){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(5,'Failed to change');
		}
	}


	public function getUserInfo()
	{
		$method=inputPost('method',0,1);

		if($method=='admin'){
			$id=inputPost('userId',0,1);
			$query=$this->db->query('SELECT user_name AS userName,nick_name AS nickName,phone,email,role_id FROM user WHERE id=?',[$id]);

			if($query->num_rows()!=1){
				returnAjaxData(1,'User not found');
			}else{
				$userInfo=$query->first_row('array');
				$roleIds=explode(',',$list[0]['role_id']);

				foreach($roleIds as $roleId){
					$this->db->or_where('id', $roleId);
				}

				unset($userInfo['role_id']);
				$query2=$this->db->get('role');
				$roleList=$query2->result_array();
				$roleName='';

				foreach($roleList as $roleInfo){
					$roleName.=$roleInfo['name'].',';
					$userInfo['roleName'][$roleInfo['id']]=$roleInfo['name'];
				}

				$userInfo['allRoleName']=substr($roleName,0,strlen($roleName)-1);

				returnAjaxData(200,'success',['userInfo'=>$userInfo]);
			}
		}else if($method=='own'){
			$query=$this->db->query('SELECT a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.id=? AND b.id=?',[$_SESSION[$this->sessPrefix.'userId'],$_SESSION[$this->sessPrefix.'roleId']]);

			if($query->num_rows()!=1){
				returnAjaxData(1,'User not found');
			}else{
				$info=$query->first_row('array');
				$info['roleId']=$_SESSION[$this->sessPrefix.'roleId'];
				returnAjaxData(200,'success',['userInfo'=>$info]);
			}
		}else if($method=='unionId'){
			$unionId=inputPost('unionId',0,1);
			$query=$this->db->query('SELECT a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.union_id=? AND a.role_id=b.id',[$unionId]);

			if($query->num_rows()!=1){
				returnAjaxData(1,'User not found');
			}else{
				$list=$query->result_array();
				returnAjaxData(200,'success',['userInfo'=>$list[0]]);
			}
		}else if($method=='api'){
			$token=inputPost('token',0,1);
			
			$this->db->select('user_id');
			$query=$this->db->get_where('login_token',array('token'=>$token));

			if($query->num_rows()==1){
				$list=$query->result_array();
				$userId=$list[0]['user_id'];
				$userInfoQuery=$this->db->query('SELECT union_id AS unionId,user_name AS userName,nick_name AS nickName,phone,email,role_id FROM user WHERE id=?',[$userId]);

				if($userInfoQuery->num_rows()!=1){
					returnAjaxData(1,'User not found');
				}else{
					$userInfo=$userInfoQuery->first_row('array');
					$roleIds=explode(',',$userInfo['role_id']);
					unset($userInfo['role_id']);

					$this->db->where_in('id',$roleIds);
					$roleQuery=$this->db->get('role');

					if($roleQuery->num_rows()<1) returnAjaxData(2,'No role info');
					else $userInfo['roleInfo']=$roleQuery->result_array();

					$this->db->where('token',$token)
					         ->delete('login_token');

					returnAjaxData(200,'success',['userInfo'=>$userInfo]);
				}
			}else{
				returnAjaxData(403,'Failed to Auth');
			}
		}else{
			returnAjaxData(2,'Invaild method');
		}
	}
}
