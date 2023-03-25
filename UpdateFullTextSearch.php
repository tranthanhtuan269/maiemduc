<?php 
require_once('./global.php'); 
require("src/mysql_function.php");
$mysqlIns=new mysql();  $mysqlIns->link=$db;

function utf8convert($str) {
	if(!$str) return false;
	$utf8 = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd'=>'đ|Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			);
	foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
return $str;
}


if ($_REQUEST['tbl'] == "tbl_trans") {
	$tbl_trans=$mysqlIns->select_tbl_trans();
	$count = 0;
	//echo count($tbl_trans);
	for($i=0;$i<count($tbl_trans);$i++)
    {
		$fulltext_search = 					'trn_name@'.strtoupper(utf8convert($tbl_trans[$i]['trn_name'])).'@trn_name;';
		$fulltext_search = $fulltext_search.'trn_detail@'.strtoupper(utf8convert($tbl_trans[$i]['trn_detail'])).'@trn_detail;';
		$fulltext_search = $fulltext_search.'trn_deliver_address@'.strtoupper(utf8convert($tbl_trans[$i]['trn_deliver_address'])).'@trn_deliver_address;';
		$fulltext_search = $fulltext_search.'prg_note@'.strtoupper(utf8convert($tbl_trans[$i]['prg_note'])).'@prg_note;';
		//echo $fulltext_search;
	  	$result=$mysqlIns->update_fulltext_search_trn_trans($tbl_trans[$i]['trn_id'], $fulltext_search);
		$count = $count + $result;
    }
	echo $count;
}

if ($_REQUEST['tbl'] == "tbl_customer") {
	$tbl_customer=$mysqlIns->select_tbl_customer();
	$count = 0;
	//echo count($tbl_customer);
	for($i=0;$i<count($tbl_customer);$i++)
    {
		$fulltext_search = 					'cust_name@'.strtoupper(utf8convert($tbl_customer[$i]['cust_name'])).'@cust_name;';
		$fulltext_search = $fulltext_search.'cust_company@'.strtoupper(utf8convert($tbl_customer[$i]['cust_company'])).'@cust_company;';
		$fulltext_search = $fulltext_search.'cust_address@'.strtoupper(utf8convert($tbl_customer[$i]['cust_address'])).'@cust_address;';
		$fulltext_search = $fulltext_search.'cust_note@'.strtoupper(utf8convert($tbl_customer[$i]['cust_note'])).'@cust_note;';
		//echo $fulltext_search;
	  	$result=$mysqlIns->update_fulltext_search_trn_customer($tbl_customer[$i]['cust_id'], $fulltext_search);
		$count = $count + $result;
		echo $count.'<br>';
    }
	echo $count;
}
?><?php mysql_close($db); ?>