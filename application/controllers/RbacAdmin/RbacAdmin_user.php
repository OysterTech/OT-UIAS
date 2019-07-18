<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-用户
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-08
* @version 2019-07-18
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RbacAdmin_user extends CI_Controller {
	
	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkAuth();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
		$this->load->helper('string');
	}


	public function toList()
	{
		$this->load->view('admin/user/list');
	}


	public function get()
	{
		$auth=$this->safe->checkAuth('api','admin/user/list');
		if($auth!=true) returnAjaxData(403002,"no Permission");

		$this->db->select('id,user_name,nick_name,email,phone,status,role_id,union_id,create_time,update_time,last_login');
		$query=$this->db->get('user');
		$list=$query->result_array();
		returnAjaxData(200,'success',['list'=>$list]);
	}


	public function add()
	{
		$this->load->view('admin/user/add');
	}


	public function toAdd()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403002,"no Permission");

		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleId=inputPost('roleId',0,1);
		$status=1;
		
		// 检查用户名手机邮箱是否已存在
		$query1=$this->db->get_where('user',['user_name'=>$userName]);
		if($query1->num_rows()!=0){
			returnAjaxData(1,"have UserName");
		}
		$query2=$this->db->get_where('user',['phone'=>$phone]);
		if($query2->num_rows()!=0){
			returnAjaxData(2,"have Phone");
		}
		$query3=$this->db->get_where('user',['email'=>$email]);
		if($query3->num_rows()!=0){
			returnAjaxData(3,"have Email");
		}

		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($originPwd.$hashSalt);

		$sql="INSERT INTO user(user_name,nick_name,password,salt,phone,email,role_id,status) VALUES (?,?,?,?,?,?,?,?)";
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$phone,$email,$roleId,$status]);

		if($this->db->affected_rows()==1){
			$data['originPwd']=$originPwd;
			returnAjaxData(200,"success",$data);
		}else{
			returnAjaxData(500,"failed to Insert");
		}
	}

	
	public function edit()
	{
		$userId=inputGet('id',0);

		$query=$this->db->get_where('user',['id'=>$userId]);

		if($query->num_rows()!=1){
			header("location:".base_url());
		}

		$list=$query->result_array();

		$this->load->view('admin/user/edit',['userId'=>$userId,'info'=>$list[0]]);
	}


	public function toEdit()
	{
		$userId=inputPost('userId',0,1);
		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleId=inputPost('roleId',0,1);
		$nowTime=date("Y-m-d H:i:s");
		
		$sql="UPDATE user SET user_name=?,nick_name=?,phone=?,email=?,role_id=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$userName,$nickName,$phone,$email,$roleId,$nowTime,$userId]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(0,"failed to Update");
		}
	}


	public function toDelete()
	{
		$id=inputPost('id',0,1);
		
		if($id==$this->nowUserId){
			returnAjaxData(400,"now User");
		}

		$this->db->delete('user',['id'=>$id]);

		if($this->db->affected_rows()==1){
			//$logContent='删除用户|'.$id;
			//$this->Log_model->create('用户',$logContent);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}


	public function toResetPwd()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403002,"no Permission");

		$id=inputPost('id',0,1);
		
		if($id==$this->nowUserId){			
			returnAjaxData(400,'now User');
		}
		
		// 获取用户名
		$this->db->select('user_name,nick_name');
		$query1=$this->db->get_where('user',['id'=>$id]);
		if($query1->num_rows()!=1){
			returnAjaxData(1,"no User");
		}else{
			$list=$query1->result_array();
			$userName=$list[0]['user_name'];
			$nickName=$list[0]['nick_name'];
		}

		// 生成初始密码
		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashPwd=sha1(md5($originPwd).$salt);

		$this->db->where('id',$id);
		$this->db->where('user_name',$userName);
		$this->db->update('user',['password'=>$hashPwd,'salt'=>$salt]);

		if($this->db->affected_rows()==1){
			$logContent='重置密码|'.$id.'|'.$userName;
			$this->Log_model->create('用户',$logContent,$this->nowUserName);
			returnAjaxData(200,"success",['originPwd'=>$originPwd,'userName'=>$userName,'nickName'=>$nickName]);
		}else{
			returnAjaxData(2,"failed to Reset");
		}
	}
	

	public function toUpdateStatus()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403002,"no Permission");

		$id=inputPost('id',0,1);
		$status=inputPost('status',0,1);
		
		if($id==$this->nowUserId){			
			returnAjaxData(400,'now User');
		}

		$this->db->where('id',$id);
		$this->db->update('user',['status'=>$status]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Update Status");
		}
	}
}
