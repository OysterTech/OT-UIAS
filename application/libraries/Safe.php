<?php
/**
 * @name 生蚝科技统一身份认证平台-L-安全类
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-23
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Safe {

	protected $_CI;
	public $sessPrefix;

	function __construct(){
		$this->_CI =& get_instance();
		$this->_CI->load->helper(array('url'));
		$this->_CI->load->model(array('Setting_model'=>'setting'));

		$this->sessPrefix=$this->_CI->setting->get('sessionPrefix');
	}

	/**
	 * 获取系统全局Session名称前缀
	 * @return String 全局Session名前缀
	 */
	public function getSessionPrefix()
	{
		return $this->sessPrefix;
	}
	
	
	/**
	 * 判断当前页面是否有权限访问
	 */
	public function checkAuth($method="",$url="")
	{
		if($this->_CI->session->userdata($this->sessPrefix.'isLogin')!=1){
			if($method!='api'){
				logout();
			}else{
				return false;
			}
		}

		if($url=='') $url=uri_string();
		$menuPermission=array();
		$roleId=$this->_CI->session->userdata($this->sessPrefix.'role_id');

		$this->_CI->db->select('menu_id');
		$this->_CI->db->where('role_id',$roleId);
		$query=$this->_CI->db->get('role_permission');
		$list=$query->result_array();

		foreach($list as $value){
			array_push($menuPermission,$value['menu_id']);
		}

		$query=$this->_CI->db->query('SELECT id FROM menu WHERE uri LIKE "'.$url.'%"');

		if($query->num_rows()!=1){
			return false;
		}else{
			$info=$query->result_array();
			$menuId=$info[0]['id'];

			if(in_array($menuId,$menuPermission)){
				return true;
			}else{
				if($method!='api'){
					logout();
				}else{
					return false;
				}
			}
		}
	}
	
	
	public function checkPassword($password,$password_indb,$salt)
	{
		$hashPassword=sha1(md5($password).$salt);
		if($hashPassword===$password_indb){
			return true;
		}else{
			return false;
		}
	}
}
