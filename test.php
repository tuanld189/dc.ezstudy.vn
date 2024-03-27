<?php
$website_id='198587';
$security_code='test';
$_CALL_BACK='https://ezstudy.vn/vtcpayment/';
$_END_POIN='https://vtcpay.vn/';
$_END_POIN='http://alpha1.vtcpay.vn/'; // test
$_PATH='bank-gateway/checkout.html'; 
$_PATH='portalgateway/checkout.html';

$_url=$_END_POIN.$_PATH;
$_arr=array();
$_arr['amount']=50000;
$_arr['currency']='VND'; //USD
$_arr['payment_type']='DomesticBank'; // VTCPay, DomesticBank, InternationalCard
$_arr['receiver_account']='0963465816';
$_arr['reference_number']='A123';
$_arr['website_id']=$website_id;

$arr_data=array();
foreach($_arr as $val){
	$arr_data[]=$val;
}
$str_data=implode('|',$arr_data);
$_arr['signature']=hash('sha512',$str_data);

$curl = curl_init($_url);
curl_setopt($curl, CURLOPT_URL, $_url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POST, count($_arr));                         
curl_setopt($curl, CURLOPT_POSTFIELDS, $_arr);                         
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                        
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl); var_dump($resp);
curl_close($curl);
