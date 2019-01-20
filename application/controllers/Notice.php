<?php
/**
 * @name 生蚝科技统一身份认证平台-C-通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {

	public $sessPrefix;
	public $API_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function detail()
	{
		$this->load->helper('cookie');

		$id=isset($_GET['id'])&&$_GET['id']>0?$_GET['id']:gotoUrl(base_url());
		$readingNotices=isset($_COOKIE[$this->sessPrefix.'readingNotices'])?explode(",",$_COOKIE[$this->sessPrefix.'readingNotices']):array();

		// 判断是否已计算过阅读
		if(!in_array($id,$readingNotices)){
			$sql='UPDATE notice SET read_count=read_count+1 WHERE id=?';
			$this->db->query($sql,[$id]);
			array_push($readingNotices,$id);
			set_cookie($this->sessPrefix."readingNotices",implode(",",$readingNotices),time()+60*60*24*7);
		}

		$this->load->view('notice/detail');
	}


	public function list()
	{
		$this->load->view('notice/list');
	}
}
