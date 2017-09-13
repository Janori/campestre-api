<?php

namespace App\Helpers;

class JResponse{
	
	public static function set($status, $msg, $data = null){
		$response = array('status'=>$status, 'msg'=>$msg, 'data'=>$data);
		return $response;
	}
	
}