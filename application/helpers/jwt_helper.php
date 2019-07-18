<?php

class jwt_helper extends CI_Controller
{
	
	const CONSUMER_SECRET = '@Oyster2018Tech^_^';
	const CONSUMER_TTL = 86400; // second | 1 day

	public static function create($param=[])
	{
		$CI =& get_instance();
		$CI->load->library('JWT');
		$token = $CI->jwt->encode(array(
			'param' => $param,
			'issuedAt' => date('Y-m-d H:i:s'),
			'ttl' => self::CONSUMER_TTL
		), self::CONSUMER_SECRET);
		return $token;
	}

	public static function validate($token)
	{
		$CI =& get_instance();
		$CI->load->library('JWT');
		
		$decodeToken=$CI->jwt->decode($token, self::CONSUMER_SECRET);
		$ttl_time=strtotime($decodeToken->issuedAt);
		$now_time=strtotime(date('Y-m-d H:i:s'));
		
		if(($now_time-$ttl_time)>$decodeToken->ttl) {
			return false;
		} else {
			return true;
		}
	}

	public static function decode($token)
	{
		$CI =& get_instance();
		$CI->load->library('JWT');
		
		try {
			$decodeToken = $CI->jwt->decode($token, self::CONSUMER_SECRET);
			return $decodeToken;
		} catch (Exception $e) {
			return false;
		}
	}
}
