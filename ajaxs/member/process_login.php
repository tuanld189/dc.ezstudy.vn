<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$arr = array();
$username = isset($_POST['username'])?antiData($_POST['username']):'';
$arr['username'] = $username;
$arr['ischeck'] = isset($_POST['ischeck'])?antiData($_POST['ischeck']):'no';
$arr['time'] = time();
$password = isset($_POST['password'])?antiData($_POST['password']):'';
unset($_POST);

if($arr['username']=='' || $password=='') die('Username and Password are empty');
$arr['password'] = $password;

$json['key'] = PIT_API_KEY;
$json['username'] = $username;
$json['password'] = $password;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$req = Curl_Post(API_MEMBER_LOGIN,json_encode($post_data));
if($req['status']=='no') 
	die($req['data']);
else{
	$req['data']['islogin']=true;
	setSessionLogin($req['data']);
	if($arr['ischeck']=='yes') setcookie('LOGIN_USER',encrypt(json_encode($arr)),time() + (86400 * 30), "/");
	die('success');
}

unset($req);
unset($arr);