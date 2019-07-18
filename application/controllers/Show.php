<?php
/**
* @name 生蚝科技RBAC开发框架-C-显示
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-06
* @version 2019-06-12
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends CI_Controller {

	public $sessPrefix;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function blank()
	{
		$this->load->view('show/blank');
	}


	public function login()
	{
		$this->load->view('show/login');
	}
	

	public function jumpOut($url,$name="")
	{
		$url=urldecode($url);
		$this->load->view('show/jumpOut',['url'=>$url,'name'=>$name]);
	}
	
	
	public function testJWT()
	{
		$this->load->helper('jwt_helper');
		
		echo $token=jwt_helper::create(['testUID'=>6,'r'=>'8']).PHP_EOL.PHP_EOL;
		echo var_dump(jwt_helper::decode($token)).PHP_EOL.PHP_EOL;
		echo var_dump(jwt_helper::validate($token)).PHP_EOL.PHP_EOL;
	}
}
