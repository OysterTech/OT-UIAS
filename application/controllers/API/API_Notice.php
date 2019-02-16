<?php
/**
 * @name 生蚝科技统一身份认证平台-C-API-通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-02-14
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_Notice extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function get()
	{
		$type=inputGet('type',0,1);
		$userId=$this->session->userdata($this->sessPrefix.'user_id')!=null?$this->session->userdata($this->sessPrefix.'user_id'):$this->ajax->returnData(403,"Not Login");
		$sql="SELECT a.*,b.nick_name AS publisher FROM notice a,user b WHERE a.publisher_id=b.id AND (receiver='0' OR receiver LIKE '%".$userId."%') ";

		switch($type){
			case "navbar":
				$latestTime=date("Y-m-d H:i:s",strtotime("-1 month"));
				$sql.="AND a.create_time>='".$latestTime."' ORDER BY a.create_time DESC";
				break;
			case "dashborad":
				$sql.="ORDER BY a.create_time DESC LIMIT 0,10";
				break;
			case "list":
				break;
			case "detail":
				$id=inputGet('id',0,1);
				$sql.="AND a.id=".$id;
				break;
			default:
				$this->ajax->returnData(1,"Invaild Type");
		}

		$query=$this->db->query($sql);
		$this->ajax->returnData(200,"success",['list'=>$query->result_array()]);
	}
}
