<?php
/**
 * @name 生蚝科技统一身份认证平台-M-应用
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-01-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 获取应用信息
	 * @param  string $appId     appId
	 * @param  string $returnUrl 应用回调地址
	 * @return array             应用信息
	 */
	public function get($appId,$returnUrl="")
	{
		$data=array('app_id'=>$appId);

		if($returnUrl!=""){
			$data['return_url']=$returnUrl;
		}

		$query=$this->db->get_where('app',$data);
		
		if($query->num_rows()==1){
			$list=$query->result_array();
			return $list[0];
		}else{
			return array();
		}
	}

}