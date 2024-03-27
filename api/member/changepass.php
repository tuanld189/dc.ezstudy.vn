<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');


$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username = isset($data['username'])?antiData($data['username']):'';
$password = isset($data['password'])?antiData($data['password']):'';
$new_pass = isset($data['new_pass'])?antiData($data['new_pass']):'';

if($key == PIT_API_KEY){
	if($username=='' || $password==''|| $new_pass==''){
		echo json_encode(array('status'=>'no','data'=>"not_found"));
		die;
	}
	$pass = hash('sha256', $username).'|'.hash('sha256', $password);
	$count = SysCount("ez_member"," AND password='$pass' AND username='$username'");
	if($count<1){
		echo json_encode(array('status'=>'no','data'=>"Mật khẩu hiện tại không đúng"));
		die;
	}
	$new_pass = hash('sha256', $username).'|'.hash('sha256', $new_pass);
	$arr = array();
	$arr['password']  = $new_pass;
	$result = SysEdit("ez_member",$arr," username='$username'");
	//var_dump($result);
	// update dữ liệu member về DC
	if($result) {
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] = $username;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
		//var_dump($post_data);
	}

	echo json_encode(array('status'=>'yes','data'=>"success",'activate'=>$activate));
		
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();