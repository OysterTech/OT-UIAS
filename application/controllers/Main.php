<?php
/**
 * @name 生蚝科技统一身份认证平台-C-基本
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-07
 * @version 2019-07-18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();

		$this->sessPrefix=$this->safe->getSessionPrefix();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function index()
	{
		if($this->nowUserId===null){
			$url=base_url().'user/login';
			$url=isset($_GET['redirect'])?$url.'?redirect='.$_GET['redirect']:$url;
			
			header('location:'.$url);
		}

		$this->load->view('index');
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

}
