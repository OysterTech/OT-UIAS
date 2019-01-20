<?php
/**
 * @name 生蚝科技统一身份认证平台-C-用户管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_User extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function list()
	{
		$this->safe->checkAuth();
		$this->load->view('admin/user/list');
	}
}
