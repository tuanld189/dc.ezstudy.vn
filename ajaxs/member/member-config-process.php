<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
include_once(incl_path."config_api.php");
require_once(libs_path.'cls.mysql.php');

if(!isLogin()) die("E01");
$username = getInfo('username');
$grade=isset($_POST['grade']) ? $_POST['grade']:'';
$version = isset($_POST['txt_version']) ? $_POST['txt_version']:array();
$subject = isset($_POST['txt_subject']) ? $_POST['txt_subject']:array();

if($grade!='' && isset($version[0]) && count($subject)>0){
	$arr=[];
	$version_id = $version[0];
	$arr['grade'] = $grade;
	$arr['grade_version'] = $version[0];
	$arr['subject_list'] = implode(',',$subject);
	
	$result1 = SysEdit("ez_member",$arr," username='$username'");
	// Cập nhật thông tin học sinh sang DC
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['username'] 	= $username;
	$json['partner_code'] = PARTNER_CODE;
	$json['arr'] = $arr;
	$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
	$url = API_MEMBER_UPDATE_INFO;
	$reponse_data = Curl_Post($url,json_encode($post_data));
	//var_dump($reponse_data);
	if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
		if(isset($reponse_data['data']) && $reponse_data['data']=="success"){
			setInfo('grade', $grade);
			setInfo('grade_version', $version_id);
			setInfo('subject_list', implode(',',$subject));
			die("success");
		}
	}
	die("error");
}else{
	echo "Không có data!";
}
