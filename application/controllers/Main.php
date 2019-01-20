<?php
/**
 * @name 生蚝科技统一身份认证平台-C-基础
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function error($mod='')
	{
		switch($mod){
			case 'appInfo':
				$tipsContent="APP信息不存在！";
				break;
			default:
				$tipsContent="系统故障！";
				break;
		}

		$this->load->view('error',['tipsContent'=>$tipsContent]);
	}


	public function dashborad()
	{
		$this->safe->checkAuth();
		$this->load->view('dashborad');
	}


	public function index()
	{
		if($this->session->userdata($this->sessPrefix."isLogin")!=1){
			gotoUrl(base_url('login'));
		}else{
			gotoUrl(base_url('dashborad'));
		}
	}
}
