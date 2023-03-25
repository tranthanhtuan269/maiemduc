<?php session_start(); ob_start();


require_once('./global.php'); 


require("src/mysql_function.php");


$mysqlIns=new mysql();
$mysqlIns->link=$db;




// load library


require 'php-excel_dondathang.class.php';


	


	//Data


	$date = date('Y-m-d');


	$timestamp = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");


	if(isset($_GET['phone'])) {

		$phone = $_GET['phone'];
	}

	if(isset($_GET['cust_name'])) {

		$cust_name = $_GET['cust_name'];
	}






	if(isset($_GET['report_from'])) {


		$report_from = $_GET['report_from'];


	} else {


		$report_from = date('d-m-Y', $timestamp);


	}


	if(isset($_GET['report_to'])) {


		$report_to = $_GET['report_to'];


	} else {


		$report_to = date('d-m-Y');


	}



	

	$report_from_arr = explode("-",$report_from);


	if (strlen($report_from_arr[2]) == 4) {


		$report_from = "'".$report_from_arr[2].'-'.$report_from_arr[1].'-'.$report_from_arr[0]."'";


	}





	$report_to_arr = explode("-",$report_to);


	if (strlen($report_to_arr[2]) == 4) {


		$report_to = "'".$report_to_arr[2].'-'.$report_to_arr[1].'-'.$report_to_arr[0]."'";


	}






	//echo $report_from.'@@@'.$report_to;  





	$export_excel_trans=$mysqlIns->export_excel_trans($phone,$_GET['report_from'],$_GET['report_to']);











	// generate file (constructor parameters are optional)


$xls = new Excel_XML('UTF-8', false, 'Danh sach don dat hang');


$xls->addArray($export_excel_trans);


$xls->generateXML($cust_name.'_'.date('d-m-Y'));


	


	


?>


