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
$data 	= isset($json['v2']) ? $json : json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username = isset($data['username'])?antiData($data['username']):'';
$password = isset($data['password'])?antiData($data['password']):'';
if($key == PIT_API_KEY || isset($json['v2'])){
	if($username=='' || $password==''){
		echo json_encode(array('status'=>'no','data'=>"not_found"));
		die();
	}
	$password=hash('sha256', $username).'|'.hash('sha256', $password);
	$req = LogIn($username, $password);
	if($req==null || $req['status']=='no') 
		echo json_encode(array('status'=>'no','data'=>"error"));
	else{
		$req['data']['islogin']=true;
		if(isset($json['v2'])) {
			header("Content-Type: application/json");
		}
		echo json_encode(array('status'=>'yes','data'=>$req));
	}
}else{
	if(isset($json['v2'])) {
		header("Content-Type: application/json");
	}
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();