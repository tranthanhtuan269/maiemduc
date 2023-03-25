<?php





if (!isset($_POST["action_code"])) $_POST["action_code"] = "";


if (!isset($_POST["trn_id"])) $_POST["trn_id"] = "";


if (!isset($_POST["trn_vat"])) $_POST["trn_vat"] = "";


if (!isset($_POST["trn_class"])) $_POST["trn_class"] = "";


if (!isset($_POST["trn_type_code"])) $_POST["trn_type_code"] = "";


if (!isset($_POST["trn_payment_type_tm"])) $_POST["trn_payment_type_tm"] = "0";


if (!isset($_POST["trn_payment_type_ck"])) $_POST["trn_payment_type_ck"] = "0";


if (!isset($_POST["trn_deliver_type"])) $_POST["trn_deliver_type"] = "";


if (!isset($_POST["trn_deliver_type"])) $_POST["trn_deliver_type"] = "";


if (!isset($_GET['id'])) $_GET['id'] = "";


if (!isset($_POST['trn_ref'])) $_POST['trn_ref'] = "";





if (!function_exists("GetSQLValueString")) {


function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 


{


  if (PHP_VERSION < 6) {


    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;


  }





  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);





  switch ($theType) {


    case "text":


      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";


      break;    


    case "long":


    case "int":


      $theValue = ($theValue != "") ? intval($theValue) : "NULL";


      break;


    case "double":


      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";


      break;


    case "date":


      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";


      break;


    case "defined":


      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;


      break;


  }


  return $theValue;


}


}





$colname_ds_nguoidung = "-1";


if ($_GET['id'] != "") {


  $colname_ds_nguoidung = $_GET['id'];


}





$editFormAction = $_SERVER['PHP_SELF'];


if (isset($_SERVER['QUERY_STRING'])) {


  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);


}





echo '<!--';


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {


mysql_select_db($dbhost, $db);





// kiem tra trung SDT va So Hoa don


$query_check = "select * from 


	(SELECT count(*) as phone FROM tbl_customer a WHERE a.cust_phone = ".GetSQLValueString($_POST['cust_phone'], "text").") a,


	(SELECT count(*) as ref FROM tbl_trans b WHERE b.trn_ref = ".GetSQLValueString($_POST['trn_ref'], "text").") b


	";


//echo $query_check;


$ds_check = mysql_query($query_check, $db) or die(mysql_error());


$row_check = mysql_fetch_assoc($ds_check);

	  if ($_POST['trn_start_date']!="") {

					$trn_start_dateArr = explode("-",$_POST['trn_start_date']);

					if (strlen($trn_start_dateArr[2]) == 4) {

						$trn_start_date = "'".$trn_start_dateArr[2].'-'.$trn_start_dateArr[1].'-'.$trn_start_dateArr[0]."'";

					}
			  }

			  else {

					$trn_start_date = "'".date("Y-m-d")."'";
			  }
			  
	if ($trn_start_date =="") {
		$trn_start_date = "'".date("Y-m-d")."'";
	}
	
	
	//if ($_SESSION['MM_Isadmin'] == 1) {

					$payment_status = "1";

					$auth_by = $_SESSION['MM_Username'];

					$auth_date = "SYSDATE()";

				/*} else {

					$payment_status = "0";

					$auth_by = "";

					$auth_date = "null";

				}*/
	

	if ($_POST["action_code"] == "0" || $_POST["action_code"] == "1" || $_POST["action_code"] == "2"){


	  $duongdan = "";


	  $uploadpath = "";


	  for ($i = 0; $i < count($_FILES['trn_img']['name']); $i++) {


		  $filename=$_SESSION['MM_Username']."_".$_FILES['trn_img']['name'][$i];


		  $filename=str_replace(" ","",$filename);


		  $upfile=$_FILES['trn_img']['tmp_name'][$i];


		  $uploadpath = "images/hoadon/".$filename;


		  $duongdan=$duongdan."images/hoadon/".$filename.";";


		  //echo '-->'.$uploadpath.'<!--';


		  //echo '-->'.$upfile.'<!--';


		  move_uploaded_file($upfile,$uploadpath);


		  


		 


	  }


	  //echo '-->'.$duongdan.'<!--';


	  //echo $image;


	  


	  $u_fulltext_search = 		    'cust_name@'.strtoupper(utf8convert($_POST['cust_name'])).'@cust_name;';


	  $u_fulltext_search = $u_fulltext_search.'cust_company@'.strtoupper(utf8convert($_POST['cust_company'])).'@cust_company;';


	  $u_fulltext_search = $u_fulltext_search.'cust_note@'.strtoupper(utf8convert($u_cust_note)).'@cust_note;';


	  


	  $fulltext_search = 					'trn_name@'.strtoupper(utf8convert($_POST['trn_name'])).'@trn_name;';


	  $fulltext_search = $fulltext_search.'trn_detail@'.strtoupper(utf8convert($_POST['trn_detail'])).'@trn_detail;';


	  $fulltext_search = $fulltext_search.'trn_deliver_address@'.strtoupper(utf8convert($_POST['trn_deliver_address'])).'@trn_deliver_address;';


	  $fulltext_search = $fulltext_search.'prg_note@'.strtoupper(utf8convert($_POST['prg_note'])).'@prg_note;';


			


	  if ($row_check["phone"] == 0) {


			  


		


			  $updateSQL = sprintf("INSERT INTO tbl_customer(


									cust_name,


									cust_company,


									cust_email,


									cust_phone,


									cust_created,


									cust_created_by,


									cust_fulltext_search) 


									VALUES (


									%s,


									%s,


									%s,


									%s,


									SYSDATE(),


									UCASE(%s),


									%s)",


							   GetSQLValueString($_POST['cust_name'], "text"),


							   GetSQLValueString($_POST['cust_company'], "text"),


							   GetSQLValueString($_POST['cust_email'], "text"),


							   GetSQLValueString($_POST['cust_phone'], "text"),


							   GetSQLValueString($_SESSION['MM_Username'], "text"),


							   GetSQLValueString($u_fulltext_search, "text")


							   );


	  } else {


			$updateSQL = sprintf("UPDATE tbl_customer SET


									cust_name = %s,


									cust_company = %s,


									cust_email = %s,


									cust_created = SYSDATE(),


									cust_created_by = UCASE(%s),


									cust_fulltext_search = %s


								  WHERE cust_phone = %s",


							   GetSQLValueString($_POST['cust_name'], "text"),


							   GetSQLValueString($_POST['cust_company'], "text"),


							   GetSQLValueString($_POST['cust_email'], "text"),


							   GetSQLValueString($_SESSION['MM_Username'], "text"),


							   GetSQLValueString($u_fulltext_search, "text"),


							   GetSQLValueString($_POST['cust_phone'], "text")


							   


							   );


	  }


	  


	  //echo $updateSQL;  


	  mysql_select_db($dbhost, $db);


	  $Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


	  $prg_status = '12';


	  $prg_step2_by = $_POST['prg_step2_by'];


	  if ($_POST['trn_has_file']=="1") {


			$prg_step2_by = '';


			$prg_status = '23';


	  }


	  //echo $_POST['prg_status'];


	  if (isset($_POST['prg_status']) && $_POST['prg_status'] > 12) $prg_status = $_POST['prg_status'];


	  


	  /*$prg_pending_by = $prg_step2_by;





	  if ($prg_pending_by=="") {


			$prg_pending_by = $_POST['prg_step3_by'];


	  }*/


	  


	if ($prg_status >= 12 && $prg_status < 23) {


		$prg_pending_by = $_POST['prg_step2_by'];


	} elseif ($prg_status >= 23 && $prg_status < 32) {


		$prg_pending_by = $_POST['prg_step3_by'];


	} elseif ($prg_status >= 32 && $prg_status < 41) {


		$prg_pending_by = $_POST['prg_step4_by'];


	} elseif ($prg_status >= 42 && $prg_status < 42) {


		$prg_pending_by = ' ';


	} 





	  


	  // get option string 


	  $trn_option = "";


	  $tbl_product_option=$mysqlIns->select_tbl_product_option($_POST['trn_prd_code']);


	  for($i=0;$i<count($tbl_product_option);$i++)


	  {


			$trn_option = $trn_option.''.$tbl_product_option[$i]['tp_option_code'].'='.$_POST['trn_'.$tbl_product_option[$i]['tp_option_code']].'@';


	  }
	  


	  


	  if ($_POST["action_code"] == "0" || $_POST["action_code"] == "2") {


			  if ($_POST['trn_end_date']=="") {


					$trn_end_date = 'null';


					//$trn_class = "1";


			  }


			  else {


					$trn_end_dateArr = explode("-",$_POST['trn_end_date']);


					if (strlen($trn_end_dateArr[2]) == 4) {


						$trn_end_date = "'".$trn_end_dateArr[2].'-'.$trn_end_dateArr[1].'-'.$trn_end_dateArr[0]."'";


					}


					//$trn_class = "0";


			  }


			  


			 /* if ($_POST["action_code"] == "2") {


					$updateSQL = sprintf("UPDATE tbl_trans SET


									trn_payment = trn_payment + %s,


									trn_payment_type_tm = %s,


									trn_payment_type_ck = %s,


									trn_payment_remain = %s,


									prg_payment_dt = SYSDATE(),


									prg_payment_tm_add = %s,


									prg_payment_ck_add = %s


								  WHERE trn_ref = %s",


							   	GetSQLValueString(str_replace(',','',str_replace(',','',$_POST['trn_payment_type_tm']) + str_replace(',','',$_POST['trn_payment_type_ck'])), "text"),


								GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_tm']), "text"),


								GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_ck']), "text"),


								GetSQLValueString(str_replace(',','',$_POST['trn_payment_remain']), "text"),


								GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_tm']), "text"),


								GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_ck']), "text"),


								GetSQLValueString($_POST['trn_ref'], "text")


								);


					mysql_select_db($dbhost, $db);


					$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


			  }*/


			  


			  $updateSQL = sprintf("INSERT INTO tbl_trans(


										trn_ref,


										trn_cust_phone,


										trn_name,


										trn_has_file,


										trn_prd_code,


										trn_prd_type,


										trn_quantity,


										trn_unit_price,


										trn_vat,


										trn_amount_withoutVAT,


										trn_amount,


										trn_payment,


										trn_payment_remain,


										trn_detail,


										trn_img,


										trn_type_code,


										prg_payment_dt,


										prg_payment_tm_add,


										prg_payment_ck_add,


										trn_payment_type_tm,


										trn_payment_type_ck,



										trn_during_from,


										trn_during,


										trn_end_date,
										trn_start_date,


										trn_deliver_type,


										trn_deliver_address,


										trn_created,


										trn_created_by,


										


										trn_giay,


										trn_can,


										trn_be,


										trn_xen,


										trn_ghim,


										trn_somau,


										trn_solien,


										trn_option,


										


										prg_step1_by,


										prg_step2_by,


										prg_step3_by,


										prg_step4_by,


										prg_pending_by,


										prg_pending_from_dt,


										prg_status,


										prg_issue_value,


										prg_issue_by,


										prg_issue_dt,


										prg_issue_from,


										prg_note,


										trn_fulltext_search) 


										VALUES (


										(SELECT case when ".$_POST["action_code"]." <> 0 then CAST( ".GetSQLValueString($_POST['trn_ref'], "text")." AS SIGNED INTEGER)


										             else MAX( CAST( t.trn_ref AS SIGNED INTEGER) ) + 1 


												end


										 FROM tbl_trans t),


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										SYSDATE(),


										%s,


										%s,


										%s,


										%s,



										SYSDATE(),


										%s,


										".$trn_end_date.",
										".$trn_start_date.",


										%s,


										%s,


										SYSDATE(),


										UCASE(%s),


										


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										%s,


										


										UCASE(%s),


										UCASE(%s),


										UCASE(%s),


										UCASE(%s),


										UCASE(%s),


										SYSDATE(),


										'".$prg_status."',


										%s,


										UCASE(%s),


										IF(TRIM('".$_POST['prg_issue_value']."') = '',null,SYSDATE()),


										'".$_POST['prg_issue_from']."',


										%s,


										%s) ",


								   //GetSQLValueString($_POST['trn_ref'], "text"),


								   GetSQLValueString($_POST['cust_phone'], "text"),


								   GetSQLValueString($_POST['trn_name'], "text"),


								   GetSQLValueString($_POST['trn_has_file'], "text"),


								   GetSQLValueString($_POST['trn_prd_code'], "text"),


								   GetSQLValueString($_POST['trn_prd_type'], "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_quantity']), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_unit_price']), "text"),


								   GetSQLValueString($_POST['trn_vat'], "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_amount_withoutVAT']), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_amount']), "text"),


								   GetSQLValueString(str_replace(',','',str_replace(',','',$_POST['trn_payment_type_tm']) + str_replace(',','',$_POST['trn_payment_type_ck'])), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_payment_remain']), "text"),


								   GetSQLValueString($_POST['trn_detail'], "text"),


								   GetSQLValueString($duongdan, "text"),


								   GetSQLValueString($_POST['trn_type_code'], "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_tm']), "text"),


							           GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_ck']), "text"),								   


								   GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_tm']), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_payment_type_ck']), "text"),


				


								   GetSQLValueString($_POST['trn_during'], "text"),


								   GetSQLValueString($_POST['trn_deliver_type'], "text"),


								   GetSQLValueString($_POST['trn_deliver_address'], "text"),


								   GetSQLValueString($_SESSION['MM_Username'], "text"),


								   


								   GetSQLValueString($_POST['trn_giay'], "text"),


								   GetSQLValueString($_POST['trn_can'], "text"),


								   GetSQLValueString($_POST['trn_be'], "text"),


								   GetSQLValueString($_POST['trn_xen'], "text"),


								   GetSQLValueString($_POST['trn_ghim'], "text"),


								   GetSQLValueString($_POST['trn_somau'], "text"),


								   GetSQLValueString($_POST['trn_solien'], "text"),


								   GetSQLValueString($trn_option, "text"),


								   


								   GetSQLValueString($_SESSION['MM_Username'], "text"),


								   GetSQLValueString($prg_step2_by, "text"),


								   GetSQLValueString($_POST['prg_step3_by'], "text"),


								   GetSQLValueString($_POST['prg_step4_by'], "text"),


								   GetSQLValueString($prg_pending_by, "text"),


								   GetSQLValueString(str_replace(',','',$_POST['prg_issue_value']), "text"),


								   GetSQLValueString($_SESSION['MM_Username'], "text"),


								   GetSQLValueString($_POST['prg_note'], "text"),


								   GetSQLValueString($fulltext_search, "text")


								   );

								   //echo '-->'.$updateSQL.'<!--';

			mysql_select_db($dbhost, $db);


			$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


	  if (($_POST['trn_payment_type_tm'] != "0" && $_POST['trn_payment_type_tm'] != "") || 
			    ($_POST['trn_payment_type_ck'] != "0" && $_POST['trn_payment_type_ck'] != "")) {
	      $updateSQLPayment = sprintf("INSERT INTO tbl_payment_hisv2(

											payment_id,

											payment_status,

											trn_id,

											trn_ref,

											trn_payment_tm,

											trn_payment_ck,

											trn_createdby,

											trn_created,

											trn_auth_by,

											trn_auth_date) 

											VALUES (

											IFNULL((select max(t.payment_id) + 1 from tbl_payment_hisv2 t),1),

											'".$payment_status."',

											(SELECT max(trn_id) FROM tbl_trans t where t.trn_created_by = UCASE('%s')),

											(SELECT case when ".$_POST["action_code"]." <> 0 then CAST( ".GetSQLValueString($_POST['trn_ref'], "text")." AS SIGNED INTEGER)

										             else MAX( CAST( t.trn_ref AS SIGNED INTEGER) )

												end

										 FROM tbl_trans t),

											

											'%s',

											'%s',

											UCASE('%s'),

											SYSDATE(),

											UCASE('$auth_by'),

											$auth_date)",
										$_SESSION['MM_Username'],
										$_SESSION['MM_Username'],

									   str_replace(',','',$_POST['trn_payment_type_tm']),

									   str_replace(',','',$_POST['trn_payment_type_ck']),

									   $_SESSION['MM_Username']

									   );

									   //echo '-->'.$updateSQLPayment.'<!--';

					mysql_select_db($dbhost, $db);

					$Result1 = mysql_query($updateSQLPayment, $db) or die(mysql_error());
					
				}
					
					

			  if ($_POST['trn_payment'] != "0" && $_POST['trn_payment'] != "") {


					if ($_SESSION['MM_Isadmin'] == 1) {


						$payment_status = "1";


					} else {


						$payment_status = "0";


					}


					


					$updateSQL = sprintf("SELECT trn_payment, trn_payment_type_tm, trn_payment_type_ck


										  from tbl_payment_his a


										  WHERE a.trn_ref = (SELECT max(CAST( t.trn_ref AS UNSIGNED )) FROM tbl_trans t where t.trn_created_by = UCASE('%s'))


										  order by payment_id desc limit 0,1",


										  $_SESSION['MM_Username']);


						mysql_select_db($dbhost, $db);


						$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


						


						$trn_payment_type_tm_his_tmp = 0;


						$trn_payment_type_ck_his_tmp = 0;


						if ($fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC)) {


							$trn_payment_his = $fetchData["trn_payment"];


							$trn_payment_type_tm_his_tmp = $fetchData["trn_payment_type_tm"];


							$trn_payment_type_ck_his_tmp = $fetchData["trn_payment_type_ck"];


						}


					$trn_payment_type_tm_his = str_replace(',','',$_POST['trn_payment_type_tm']) - $trn_payment_type_tm_his_tmp;


					$trn_payment_type_ck_his = str_replace(',','',$_POST['trn_payment_type_ck']) - $trn_payment_type_ck_his_tmp;


					$trn_payment_add = $trn_payment_type_tm_his + $trn_payment_type_ck_his;			


					


				//if ($trn_payment_type_tm_his_tmp != str_replace(',','',$_POST['trn_payment_type_tm']) ||
//

				//	$trn_payment_type_ck_his_tmp != str_replace(',','',$_POST['trn_payment_type_ck'])) {



/*

					$updateSQLPayment = sprintf("INSERT INTO tbl_payment_his(


											payment_id,


											payment_status,


											trn_id,


											trn_ref,


											trn_payment,


											trn_payment_type_tm,


											trn_payment_type_ck,


											trn_payment_add,


											trn_payment_tm_add,


											trn_payment_ck_add,


											trn_createdby,


											trn_created,


											trn_auth_by,


											trn_auth_date) 


											VALUES (


											(select max(t.payment_id) + 1 from tbl_payment_his t),


											'".$payment_status."',


											(SELECT max(trn_id) FROM tbl_trans t where t.trn_created_by = UCASE('%s')),


											(SELECT max(CAST( t.trn_ref AS UNSIGNED )) FROM tbl_trans t where t.trn_created_by = UCASE('%s')),


											'%s',


											'%s',


											'%s',


						


											'%s',


											'%s',


											'%s',


											UCASE('%s'),


											SYSDATE(),


											UCASE('$auth_by'),


											$auth_date)",


									   $_SESSION['MM_Username'],


									   $_SESSION['MM_Username'],


									   str_replace(',','',$_POST['trn_payment']),


									   str_replace(',','',$_POST['trn_payment_type_tm']),


									   str_replace(',','',$_POST['trn_payment_type_ck']),


						


									   str_replace(',','',$trn_payment_add),


									   str_replace(',','',$trn_payment_type_tm_his),


									   str_replace(',','',$trn_payment_type_ck_his),


									   $_SESSION['MM_Username']


									   );


									   //echo '-->'.$updateSQLPayment.'<!--';


					mysql_select_db($dbhost, $db);


					$Result1 = mysql_query($updateSQLPayment, $db) or die(mysql_error());*/
					
					
					


				//}


			  }
			  
			  


	  } else {


			  if ($_POST['trn_end_date']!="") {


					$trn_end_dateArr = explode("-",$_POST['trn_end_date']);


					if (strlen($trn_end_dateArr[2]) == 4) {


						$trn_end_date = "'".$trn_end_dateArr[2].'-'.$trn_end_dateArr[1].'-'.$trn_end_dateArr[0]."'";


					}


					//$trn_class = "1";


			  }


			  else {


					$trn_end_date = 'null';


					//$trn_class = "0";


			  }


			  


			  $updateSQL = sprintf("UPDATE tbl_trans SET


										trn_cust_phone = %s,


										trn_name = %s,


										trn_has_file = %s,


										trn_prd_code = %s,


										trn_prd_type = %s,


										trn_quantity = %s,


										trn_unit_price = %s,


										trn_vat = %s,


										trn_amount_withoutVAT = %s,


										trn_amount = %s,


										


										trn_detail = %s,


										trn_img = (select case '".$_FILES['trn_img']['name'][0]."' when '' then trn_img


																					else %s


														  end from dual),


										trn_type_code = %s,


										


										trn_during = %s,


										trn_end_date = ".$trn_end_date.",
										trn_start_date = ".$trn_start_date.",


										trn_deliver_type = %s,


										trn_deliver_address = %s,


										trn_updated = SYSDATE(),


										trn_updated_by = UCASE(%s),


										


										trn_giay = %s,


										trn_can = %s,


										trn_be = %s,


										trn_xen = %s,


										trn_ghim = %s,


										trn_somau = %s,


										trn_solien = %s,


										trn_option = %s,


										


										


										prg_step2_by = UCASE(%s),


										prg_step3_by = UCASE(%s),


										prg_step4_by = UCASE(%s),


										prg_pending_by = UCASE(%s),


										prg_pending_from_dt = SYSDATE(),


										prg_issue_value = %s,


										prg_issue_by = %s,


										prg_issue_dt = IF(TRIM('".$_POST['prg_issue_value']."') = '',null,SYSDATE()),


										prg_issue_from = '".$_POST['prg_issue_from']."',


										prg_note = %s,


										trn_fulltext_search = %s


									WHERE trn_id = %s",


								   GetSQLValueString($_POST['cust_phone'], "text"),


								   GetSQLValueString($_POST['trn_name'], "text"),


								   GetSQLValueString($_POST['trn_has_file'], "text"),


								   GetSQLValueString($_POST['trn_prd_code'], "text"),


								   GetSQLValueString($_POST['trn_prd_type'], "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_quantity']), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_unit_price']), "text"),


								   GetSQLValueString($_POST['trn_vat'], "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_amount_withoutVAT']), "text"),


								   GetSQLValueString(str_replace(',','',$_POST['trn_amount']), "text"),


								   


								   GetSQLValueString($_POST['trn_detail'], "text"),


								   GetSQLValueString($duongdan, "text"),


								   GetSQLValueString($_POST['trn_type_code'], "text"),


								   


								   GetSQLValueString($_POST['trn_during'], "text"),


								   GetSQLValueString($_POST['trn_deliver_type'], "text"),


								   GetSQLValueString($_POST['trn_deliver_address'], "text"),


								   GetSQLValueString($_SESSION['MM_Username'], "text"),


								   


								   GetSQLValueString($_POST['trn_giay'], "text"),


								   GetSQLValueString($_POST['trn_can'], "text"),


								   GetSQLValueString($_POST['trn_be'], "text"),


								   GetSQLValueString($_POST['trn_xen'], "text"),


								   GetSQLValueString($_POST['trn_ghim'], "text"),


								   GetSQLValueString($_POST['trn_somau'], "text"),


								   GetSQLValueString($_POST['trn_solien'], "text"),


								   GetSQLValueString($trn_option, "text"),


								   





								   GetSQLValueString($prg_step2_by, "text"),


								   GetSQLValueString($_POST['prg_step3_by'], "text"),


								   GetSQLValueString($_POST['prg_step4_by'], "text"),


								   GetSQLValueString($prg_pending_by, "text"),


								   GetSQLValueString(str_replace(',','',$_POST['prg_issue_value']), "text"),


								   GetSQLValueString($_SESSION['MM_Username'], "text"),


								   GetSQLValueString($_POST['prg_note'], "text"),


								   GetSQLValueString($fulltext_search, "text"),


								   GetSQLValueString($_POST['trn_id'], "text")


								   );


			mysql_select_db($dbhost, $db);


			$Result1 = mysql_query($updateSQL, $db) or die(mysqli_error($db));//echo "-->".$updateSQL."<!--";

			
			


			
			if (($_POST['trn_payment_type_tm'] != "0" && $_POST['trn_payment_type_tm'] != "") || 
			    ($_POST['trn_payment_type_ck'] != "0" && $_POST['trn_payment_type_ck'] != "")) {
			
			$updateSQLPayment = sprintf("INSERT INTO tbl_payment_hisv2(

											payment_id,

											payment_status,

											trn_id,

											trn_ref,

											trn_payment_tm,

											trn_payment_ck,

											trn_createdby,

											trn_created,

											trn_auth_by,

											trn_auth_date) 

											VALUES (

											IFNULL((select max(t.payment_id) + 1 from tbl_payment_hisv2 t),1),

											'".$payment_status."',

											'".$_POST['trn_id']."',

											'".$_POST['trn_ref']."',

											

											'%s',

											'%s',

											UCASE('%s'),

											SYSDATE(),

											UCASE('$auth_by'),

											$auth_date)",
									

									   str_replace(',','',$_POST['trn_payment_type_tm']),

									   str_replace(',','',$_POST['trn_payment_type_ck']),

									   $_SESSION['MM_Username']

									   );

									   //echo '-->'.$updateSQLPayment.'<!--';

					mysql_select_db($dbhost, $db);

					$Result1 = mysql_query($updateSQLPayment, $db) or die(mysql_error());
	  }


			//if ($_POST['trn_payment'] != "0" && $_POST['trn_payment'] != "") {


				


			


					$updateSQL = sprintf("SELECT sum(trn_payment_tm + trn_payment_ck) as trn_payment


										  from tbl_payment_hisv2 a


										  WHERE a.trn_ref = '%s'",


										  $_POST['trn_ref']);


						mysql_select_db($dbhost, $db);


						$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


						$trn_payment_his = 0;


						if ($fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC)) {


							$trn_payment_his = $fetchData["trn_payment"];


						}
						
						
						$updateSQL = sprintf("SELECT a.trn_id

										  from tbl_trans a

										  WHERE a.trn_ref = %s

										  order by a.trn_payment desc limit 0,1",

										  GetSQLValueString($_POST['trn_ref'], "text"));

						mysql_select_db($dbhost, $db);
						$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());


						$trn_id_update = $_POST['trn_id'];

						if ($fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC)) {

							$trn_id_update = $fetchData["trn_id"];

						}
						
						
						
						
						

						$updateSQL = sprintf("UPDATE tbl_trans SET


						prg_payment_dt = SYSDATE(),
						trn_payment = %s,
						trn_payment_type_tm = %s,
						trn_payment_type_ck = %s
						


						WHERE trn_id = %s",
						$trn_payment_his,
						str_replace(',','',$_POST['trn_payment_type_tm']),
						str_replace(',','',$_POST['trn_payment_type_ck']),

						GetSQLValueString($trn_id_update, "text")


						);


						mysql_select_db($dbhost, $db);


						$Result1 = mysql_query($updateSQL, $db) or die(mysqli_error($db));
						
						
						
						
						
						
						$updateSQL = sprintf("UPDATE tbl_trans SET

						prg_payment_dt = null,
						trn_payment = null,
						prg_payment_tm_add = 0,
						prg_payment_ck_add = 0,
						trn_payment_type_tm = 0,
						trn_payment_type_ck = 0
						

						WHERE trn_id <> %s and trn_ref = %s",
						GetSQLValueString($trn_id_update, "text"),
						GetSQLValueString($_POST['trn_ref'], "text")

						);

						mysql_select_db($dbhost, $db);

						$Result1 = mysql_query($updateSQL, $db) or die(mysqli_error($db));



			//}


	  }

	  
		$trn_id = $_POST['trn_id'];
		if ($trn_id == '') {
			$updateSQL = sprintf("SELECT max(CAST( t.trn_id AS UNSIGNED )) as trn_id

								  from tbl_trans t

								  WHERE t.trn_created_by = UCASE('%s')",

								  $_SESSION['MM_Username']);

			mysql_select_db($dbhost, $db);

			$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
			$fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC);
			$trn_id = $fetchData["trn_id"];
		} 
		
		
		header("Location: index.php?mode=viewdetail&id=".$trn_id);
		exit();

			  

	  //echo "-->".$updateSQL."<!--";


	  


	  


	  //echo '11111111111111111111111111111111111111111111111';


	  if ($_POST["action_code"] == "1")


		echo "--><script type=\"text/javascript\">$( document ).ready(function() {


						$.growl.notice({ message: \"Update đơn hàng thành công!\" });


					});</script><!--";


	  else 


		echo "--><script type=\"text/javascript\">$( document ).ready(function() {


						$.growl.notice({ message: \"Thêm mới đơn hàng thành công!\" });


					});</script><!--";


		$_POST["action_code"] = 1;


		


	  if (isset($_SERVER['QUERY_STRING'])) {


		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";


		$updateGoTo .= $_SERVER['QUERY_STRING'];


	  }


		


	} else {


		/*if ($row_check["ref"] != 0)


			echo "<script language=\"javascript\">


					$( document ).ready(function() {


						hideDesign('');


						changecolor();


						


						conf = false;


						if ($(\"#action_code\").val() == '1') {


							conf = confirm('Bạn muốn update hóa đơn này ?')


						} else if ($(\"#action_code\").val() == '2') {


							conf = confirm('Bạn muốn thêm sản phẩm vào hóa đơn này ?')


						} else {


							conf = confirm('Bạn muốn tạo đơn hàng mới ?')


						}


						if (!conf) {


							searchref('".$_POST['trn_ref']."',$(\"#action_code\").val(),".$_POST['trn_id'].");


							$('#trn_ref').focus();


						} else {


							$('#confirm_update').val('1');


							save_validate();


						}


					});


					


				</script>";*/


				//echo 'hhhhhhhhhhhhhhhhh';


}


} elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "changePrd") && ($_POST['trn_ref'] != '')) {


	echo "--><script language=\"javascript\">


			$( document ).ready(function() {


					//alert($(\"#action_code\").val());


					searchorder(1,1);


				});


		 </script><!--";


}





$width = "98";





$titlelink = "X&#7917; l&#253;";


if ($_SESSION['step'] == "SALE") {


	$titlelink = "Giao h&#224;ng";


}


if ($_SESSION['step'] == "DESIGN") {


	$titlelink = "Thi&#7871;t k&#7871;";


}


if ($_SESSION['step'] == "BUILD") {


	$titlelink = "S&#7843;n xu&#7845;t";


}


if ($_SESSION['step'] == "DELIVER") {


	$titlelink = "Giao h&#224;ng";


}


if ($_SESSION['step'] == "CARE") {


	$titlelink = "Ch&#259;m s&#243;c";


}





$index_origin=$mysqlIns->get_trans_by_id($_GET['id']);


echo '-->';


?>








<script type="text/javascript">


		<?php if ($_GET['id']!="") { ?>


		function loadAvailbleOrderAdd(id)


		{


			if (id == "") return;


			$('#trn_id').val($('#trn_id_' +id).val());


			


			$('#cust_name').val($('#cust_name_' +id).val());


			$('#cust_company').val($('#cust_company_' +id).val());


			$('#cust_email').val($('#cust_email_' +id).val());


			$('#cust_phone').val($('#cust_phone_' +id).val());





			$('#trn_ref').val($('#trn_ref_' +id).val());


			$('#trn_name').val($('#trn_name_' +id).val());





			if ($('#trn_has_file_' +id).val() == '1') {


				$('input[name=trn_has_file]').prop('checked',true);


			} else {


				$('input[name=trn_has_file]').prop('checked',false);


			}


			


			//alert($('#trn_prd_code_' +id).val());


			$('#trn_prd_code').val($('#trn_prd_code_' +id).val());


			$('#trn_prd_type').val($('#trn_prd_type_' +id).val());


			<?php 


			$option_val = $index_origin[0]["trn_option"];


			$option_val_arr = explode("@",$index_origin[0]["trn_option"]);


			for($i=0;$i<count($option_val_arr)-1;$i++)


			{


				$option_val_arr_key = explode("=",$option_val_arr[$i]);


				//echo '<input type="hidden" id="trn_'.$option_val_arr_key[0].'_'.$index_origin[0]["trn_id"].'" value="'.$option_val_arr_key[1].'">';


				echo 'if ($(\'#trn_'.strtoupper($option_val_arr_key[0]).'\').val() != null) $(\'#trn_'.strtoupper($option_val_arr_key[0]).'\').val($(\'#trn_'.strtoupper($option_val_arr_key[0]).'_\' +id).val());';


				echo PHP_EOL;


			}


			?>


			


			/*if ($('#trn_can').val() != null) $('#trn_can').val($('#trn_can_' +id).val());


			if ($('#trn_be').val() != null) $('#trn_be').val($('#trn_be_' +id).val());


			if ($('#trn_xen').val() != null) $('#trn_xen').val($('#trn_xen_' +id).val());


			if ($('#trn_ghim').val() != null) $('#trn_ghim').val($('#trn_ghim_' +id).val());


			if ($('#trn_somau').val() != null) $('#trn_somau').val($('#trn_somau_' +id).val());


			if ($('#trn_solien').val() != null) $('#trn_solien').val($('#trn_solien_' +id).val());


			*/


			


			$('#trn_quantity').val($('#trn_quantity_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_unit_price').val($('#trn_unit_price_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			


			


			


			if ($('#trn_vat_' +id).val() == 10) {


				$('#trn_vat_v10').prop('checked',true);


				$('#trn_vat_v0').prop('checked',false);


			} else {


				$('#trn_vat_v10').prop('checked',false);


				$('#trn_vat_v0').prop('checked',true);


			}


			


			$('#trn_payment').val($('#trn_payment_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

			

			//$('#trn_payment_type_tm').val($('#trn_payment_type_tm_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			//$('#trn_payment_type_ck').val($('#trn_payment_type_ck_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_payment_auth').html($('#trn_payment_auth_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_detail').val($('#trn_detail_' +id).val());


			


			/*if ($('#trn_class_' +id).val() == 1) {


				$('#trn_class_v0').prop('checked',false);


				$('#trn_class_v1').prop('checked',true);


				changecolor();


			} else {


				$('#trn_class_v0').prop('checked',true);


				$('#trn_class_v1').prop('checked',false);


				changecolor();


			}*/


			if ($('#trn_end_date_' +id).val() != '') {


				changecolor();


			} else {


				changecolor();


			}





			if ($('#trn_type_code_' +id).val() == 1) {


				$('#trn_type_code_v1').prop('checked',true);


				$('#trn_type_code_v0').prop('checked',false);


			} else {


				$('#trn_type_code_v1').prop('checked',false);


				$('#trn_type_code_v0').prop('checked',true);


			}


			


			$('#trn_during').val($('#trn_during_' +id).val());


			$('#trn_end_date').val($('#trn_end_date_' +id).val());
			$('#trn_start_date').val($('#trn_start_date_' +id).val());

			


			if ($('#trn_deliver_type_' +id).val() == 1) {


				$('#trn_deliver_type_v1').prop('checked',true);


				$('#trn_deliver_type_v0').prop('checked',false);


			} else {


				$('#trn_deliver_type_v1').prop('checked',false);


				$('#trn_deliver_type_v0').prop('checked',true);


			}


			


			$('#trn_deliver_address').val($('#trn_deliver_address_' +id).val());


			$('#trn_design_path').val($('#trn_design_path_' +id).val());


			$('#prg_step2_by').val($('#prg_step2_by_' +id).val());


			$('#design_user_value').html($('#prg_step2_by_' +id).val());


			$('#prg_step2_dt1').html($('#prg_step2_dt1_' +id).val());


			$('#prg_step2_dt2').html($('#prg_step2_dt2_' +id).val());


			$('#prg_step2_dt3').html($('#prg_step2_dt3_' +id).val());


			


			$('#prg_step3_by').val($('#prg_step3_by_' +id).val());


			$('#build_user_value').html($('#prg_step3_by_' +id).val());


			


			$('#prg_step3_dt1').html($('#prg_step3_dt1_' +id).val());


			$('#prg_step3_dt2').html($('#prg_step3_dt2_' +id).val());


			$('#prg_issue_date').html($('#prg_issue_date_' +id).val());


			$('#prg_issue_value').val($('#prg_issue_value_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#prg_issue_from').val($('#prg_issue_from_' +id).val());


			


			$('#prg_step4_by').val($('#prg_step4_by_' +id).val());


			$('#deliver_user_value').html($('#prg_step4_by_' +id).val());


			


			$('#prg_step4_dt1').html($('#prg_step4_dt1_' +id).val());


			$('#prg_step4_dt2').html($('#prg_step4_dt2_' +id).val());


			


			$('#prg_note').val($('#prg_note_' +id).val());


			<?php if(strpos($index_origin[0]["trn_img"],'.')) {?>


			var imgarr = $('#trn_img_' +id).val().split(';');


			var imgsrc = "";


			for (var i = 0; i < imgarr.length; i ++) {


				imgsrc = imgsrc + "<img src=\""+imgarr[i]+"\" height=100>"


			}


			$('#trn_img_show').html(imgsrc);


			<?php }?>


			


			$('#trn_amount').val($('#trn_amount_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_amount_withoutVAT').val($('#trn_amount_withoutVAT_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			//trn_payment_remain = parseInt($('#trn_total_amount').html()) - parseInt($('#trn_payment').val());


			//alert($('#trn_total_amount').html());


			//$('#trn_payment_remain').val(trn_payment_remain);


			


			/*trn_amount_withoutVAT = parseInt($('#trn_quantity_' +id).val()) * parseInt($('#trn_unit_price_' +id).val());


			$('#trn_amount_withoutVAT').val(trn_amount_withoutVAT);


			


			VAT = 0;


			if ($('#trn_vat_' +id).val() == 10) VAT = 10;


			trn_amount = trn_amount_withoutVAT + trn_amount_withoutVAT / 100 * VAT;


			$('#trn_amount').val(trn_amount);


			


			trn_payment_remain = trn_amount - parseInt($('#trn_payment').val());


			$('#trn_payment_remain').val(trn_payment_remain);*/


			//$("#action_code").val('1');


			searchorder(1,1);


			


		}


		


		$( document ).ready(function() {


			


			loadAvailbleOrderAdd('<?php echo $_GET['id'] ?>');


			hideDesign('');


			changecolor();





			$("#action_code").val('1');


			$("#trn_id").val('<?php echo $_GET['id'] ?>');


			//searchref($('#trn_ref').val(),$("#action_code").val(),$('#trn_id').val());
			
			


		});


		<?php } else { ?>


		


		$( document ).ready(function() {


			hideDesign('');


			changecolor();


		});


				


		<?php } ?>


       $(function() {


               $("#trn_end_date").datepicker({ dateFormat: "dd-mm-yy" })
			   $("#trn_start_date").datepicker({ dateFormat: "dd-mm-yy" })


       });


       


       $( document ).ready(function() {


		//alert('<?=$_POST['trn_id']?>');


		//searchorder(event,1,$('#<?=$_POST['trn_id']?>').val());


		//calTotalAmount();
		
		


	});


   </script>


 


<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">


<thead>





<tr>


		<td align="center" valign="middle" nowrap="nowrap"><img src="images/order11.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=all">T&#7845;t c&#7843;</a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		<?php if ($_SESSION['step']=='SALE' || $_SESSION['step']== null) { ?>


		<td align="center" valign="middle"><img src="images/hotclock.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=hot">&#272;&#417;n h&#224;ng g&#7845;p</a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		<?php } ?>


		


		<?php if ($_SESSION['step']=='CARE') { ?>


		<td align="center" valign="middle"><img src="images/payment.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=debit">Kh&#225;ch h&#224;ng n&#7907;</a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		<?php } ?>


			


		<?php if ($_SESSION['step']!='SALE' || $_SESSION['step']== null) { ?>


		<td align="center" valign="middle"><img src="images/order1.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=wait">Ch&#432;a <?php echo $titlelink; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		<?php if ($_SESSION['step']!='CARE' && $_SESSION['step']!='DELIVER') { ?>


		<td align="center" valign="middle"><img src="images/order2.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=on">&#272;ang <?php echo $titlelink; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		<?php } ?>


		<?php } ?>


		<td align="center" valign="middle"><img src="images/order3.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?vw=complete">&#272;&#227; <?php echo $titlelink	; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>


		


		<td width="80%" align="center" valign="middle">


		<!--<img src="upload/free-happy-new-year-clipart-banners-6.jpg" height=21>


		<img src="upload/2015.jpeg" height=21>


		<img src="upload/88e0f16114a1e011c87b797513095a20.jpg" height=21>-->


		</td>


		<td align="center" valign="middle"><img src="images/expand.png" height=25></td>


		<td align="center" valign="middle" nowrap="nowrap">


		<font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?search=advance">T&#236;m ki&#7871;m n&#226;ng cao</b></font>


		</td>





	</tr>


	<tr  height="1">


	


		<td width="10%" colspan="4" align="center" height="4"></td>


		


	</tr>


</thead>


</table>





<form id="saveTran" name="saveTran" method="POST" action="?mode=addnew_contract"  enctype="multipart/form-data">


<?php 





$disabled = ' disabled ';


$selectDisabledStyle = ' style ="background-color: #ebebe4;" ';


if ($_SESSION['MM_Isadmin'] == 1 || $_GET['mode'] == 'addnew_contract') {


	$disabled  = '';


	$selectDisabledStyle='';


} else {


	if (strtoupper($_SESSION['MM_Username']) == strtoupper($index_origin[0]["prg_step1_by"]) &&


		($index_origin[0]["prg_status"] < 42)


		) 


	{


		$disabled  = '';


		$selectDisabledStyle='';


	}


}





$option_val = $index_origin[0]["trn_option"];


$option_val_arr = explode("@",$index_origin[0]["trn_option"]);


for($i=0;$i<count($option_val_arr)-1;$i++)


{


	$option_val_arr_key = explode("=",$option_val_arr[$i]);


	echo '<input type="hidden" id="trn_'.strtoupper($option_val_arr_key[0]).'_'.$index_origin[0]["trn_id"].'" value="'.strtoupper($option_val_arr_key[1]).'">';


}





echo '


<input type="hidden" id="cust_name_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["cust_name"].'">


<input type="hidden" id="cust_company_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["cust_company"].'">


<input type="hidden" id="cust_email_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["cust_email"].'">


<input type="hidden" id="cust_phone_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["cust_phone"].'">





<input type="hidden" id="trn_id_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_id"].'">


<input type="hidden" id="trn_ref_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_ref"].'">


<input type="hidden" id="trn_name_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_name"].'">





<input type="hidden" id="trn_has_file_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_has_file"].'">





<input type="hidden" id="trn_prd_code_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_prd_code"].'">


<input type="hidden" id="trn_prd_type_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_prd_type"].'">





<input type="hidden" id="trn_quantity_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_quantity"].'">


<input type="hidden" id="trn_unit_price_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_unit_price"].'">


<input type="hidden" id="trn_vat_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_vat"].'">


<input type="hidden" id="trn_payment_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_payment_all"].'">


<input type="hidden" id="trn_payment_auth_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_payment_auth"].'">





<input type="hidden" id="trn_amount_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_amount"].'">


<input type="hidden" id="trn_amount_withoutVAT_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_amount_withoutVAT"].'">


<input type="hidden" id="trn_payment_remain_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_payment_remain"].'">





<input type="hidden" id="trn_detail_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_detail"].'">





<input type="hidden" id="trn_type_code_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_type_code"].'">


<input type="hidden" id="trn_payment_type_tm_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_payment_type_tm"].'">


<input type="hidden" id="trn_payment_type_ck_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_payment_type_ck"].'">


<input type="hidden" id="trn_during_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_during"].'">


<input type="hidden" id="trn_end_date_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_end_date_f"].'">
<input type="hidden" id="trn_start_date_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_start_date_f"].'">


<input type="hidden" id="trn_deliver_type_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_deliver_type"].'">


<input type="hidden" id="trn_deliver_address_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_deliver_address"].'">


<input type="hidden" id="prg_step2_by_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step2_by"].'">


<input type="hidden" id="prg_step2_dt1_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step2_dt1"].'">


<input type="hidden" id="prg_step2_dt2_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step2_dt2"].'">


<input type="hidden" id="prg_step2_dt3_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step2_dt3"].'">





<input type="hidden" id="prg_step3_by_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step3_by"].'">


<input type="hidden" id="prg_step3_dt1_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step3_dt1"].'">


<input type="hidden" id="prg_step3_dt2_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step3_dt2"].'">


<input type="hidden" id="prg_issue_date_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_issue_dt"].'">





<input type="hidden" id="prg_issue_value_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_issue_value"].'">


<input type="hidden" id="prg_issue_from_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_issue_from"].'">





<input type="hidden" id="prg_step4_by_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step4_by"].'">


<input type="hidden" id="prg_step4_dt1_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step4_dt1"].'">


<input type="hidden" id="prg_step4_dt2_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_step4_dt2"].'">





<input type="hidden" id="prg_note_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["prg_note"].'">





<input type="hidden" id="trn_img_'.$index_origin[0]["trn_id"].'" value="'.$index_origin[0]["trn_img"].'">





<input type="hidden" id="confirm_update" name="confirm_update" value="0">


<input type="hidden" id="action_code" name="action_code" value="'.$_POST["action_code"].'">





<input type="hidden" id="trn_id" name="trn_id" value="'.$_POST['trn_id'].'">


<input type="hidden" id="trn_disabled" name="trn_disabled" value="'.trim($disabled).'">


<input type="hidden" id="prg_status" name="prg_status" value="'.$index_origin[0]["prg_status"].'">


';


?>


<table>





<!--


<tr>


		<th colspan="6" valign="center" >


		<img border="0" src="images/categories_002.png" width="25" height="20" align="middle"><b>


		<font color="#800000" size="2"> 


		Qu&#7843;n l&#253; h&#7907;p &#273;&#7891;ng </font></b></th></tr>


		


	-->


<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">


<thead>


<tr height="2" >





		<td width="40%" colspan="6" align="center" valign="middle"></td>


		


	</tr>


</thead>


</table>


  <table width="<?php echo $width; ?>%" border="0" cellspacing="0" cellpadding="0" align="center" >


  <thead>


  





	<tr><td colspan=2 width="100%">


	<fieldset><legend>Thông tin khách hàng</legend>


	<table width="100%" cellspacing="0" cellpadding="0">


    <tr>


      <td scope="row" align="right" nowrap="nowrap"><b>Tên KH&nbsp;</b></td>


      <td align="left" nowrap="nowrap"><label for="name"></label>


	  <input onblur="searchcust(event,this.value);" name="cust_name" value="<?php echo isset($_POST['cust_name']) ? $_POST['cust_name'] : '' ?>"


	  type="text" id="cust_name" size="25" placeholder="" style="border:1px solid #DADADA;"/>


	  


      </td>


	  <td scope="row" align="right" nowrap="nowrap"><b>Tên Cty&nbsp;</b></td>


      <td align="left" nowrap="nowrap"><label for="name"></label>


	  <input onblur="searchcust(event,this.value);" name="cust_company" value="<?php echo isset($_POST['cust_company']) ? $_POST['cust_company'] : '' ?>"


	  type="text" id="cust_company" size="40" style="border:1px solid #DADADA;"/>


     </td>


	 <td scope="row" align="right" nowrap="nowrap"><b>Phone &nbsp;</b></td>


      <td scope="row" align="left" nowrap="nowrap">


		<input maxlength="15" onblur="checkPhone();" onkeypress="return onlynumber(event);" onblur="searchcust(event,this.value);" name="cust_phone" value="<?php echo isset($_POST['cust_phone']) ? $_POST['cust_phone'] : '' ?>"


		type="text" id="cust_phone" size="20" placeholder=""/> <font color="red">(*)</font>


	  </td>


	 <td scope="row" align="right" nowrap="nowrap"><b>eMail &nbsp;</b></td>


      <td scope="row" align="left" nowrap="nowrap">


		<input onblur="this.value = this.value.replace(/ /g,'').replace(/\</g,'').replace(/\>/g,''); searchcust(event,this.value);" name="cust_email" value="<?php echo isset($_POST['cust_email']) ? $_POST['cust_email'] : '' ?>"


		type="text" id="cust_email" size="35" placeholder=""/>


	  </td>


      


    </tr>





	





	</table>


	


	


	</fieldset>


	</td></tr>


	<tr><td colspan=2 width="100%"><div id="content" height="1"></div></td></tr>


	<tr><td colspan=2 width="100%">





	<tr><td colspan=2 width="100%">


	<fieldset><legend>Yêu cầu về sản phẩm</legend>


	<table width="100%" cellspacing="0" cellpadding="0">


	<tr>


      <td width="17%" scope="row" align="right"><b>Ngày nhập: &nbsp;</b></td>


      <td width="83%" align="left" nowrap="nowrap"><label for="name"></label>
	  <input maxlength="10" autocomplete="off" onkeypress="return onlydate(event);" <?php echo $disabled; ?> onchange="check2();" name="trn_start_date" value="<?php echo isset($_POST['trn_start_date']) ? $_POST['trn_start_date'] : '' ?>"

			type="text" id="trn_start_date" size="10" style="border:1px solid #DADADA;"/> (DD-MM-YYYY)
	 
		<b>Số hóa đơn &nbsp;</b>

	  <?php $maxref = $mysqlIns->get_ref_max(); ?>


	  <input maxlength="10" autocomplete="off" onkeypress="return onlynumber(event);" onblur="searchorder(event,1,this.value);" onchange="clearDate();" name="trn_ref" type="text" id="trn_ref" value="<?php echo $_POST['trn_ref'] != "" ? $_POST['trn_ref'] : $maxref[0]['maxref'] + 1; ?>"


	  size="10" placeholder="" style="border:1px solid #DADADA;"/> <font color="red">(*)</font>


	  <b>&nbsp;&nbsp;&nbsp;Ti&#234;u &#273;&#7873;&nbsp;&nbsp;</b>


	  <input maxlength="50" onblur="searchorder(event,2,this.value);" name="trn_name" type="text" id="trn_name" value="<?php echo isset($_POST['trn_name']) ? $_POST['trn_name'] : '' ?>"


	  size="35" style="border:1px solid #DADADA;" /> <font color="red">(*)</font>


      <span style="background-color: yellow;" onclick="hideDesign(this);"><label><b>&nbsp;&nbsp;&nbsp;&#272;&#227; c&#243; file&nbsp;&nbsp;</b>


	  <input <?php echo $disabled; ?> name="trn_has_file" type="checkbox" value='1' <?php if (isset($_POST['trn_has_file']) && $_POST['trn_has_file'] == '1') echo 'checked'; ?>


	   ></label></span>


      </td>


    </tr>


	<tr>


	<td scope="row" align="right"><b>Sản phẩm &nbsp;</b></td>


      <td><label for="trn_prd_code"></label>


        <select onchange="changePrd();" <?php echo $disabled.$selectDisabledStyle; ?> name="trn_prd_code" id="trn_prd_code" style="border:1px solid #DADADA;">


<?php





echo "<!--";


$tbl_product=$mysqlIns->select_tbl_product();


echo "-->";





for($i=0;$i<count($tbl_product);$i++)


{


	if ($i == 0) {


		$temporder = $tbl_product[$i]['prd_order'];


		$tempprd = $tbl_product[$i]['prd_code'];


	} else {


		if ($temporder > $tbl_product[$i]['prd_order']) {


			$temporder = $tbl_product[$i]['prd_order'];


			$tempprd = $tbl_product[$i]['prd_code'];


		}


	}


	if (isset($_POST['trn_prd_code']) && ($_POST['trn_prd_code'] == $tbl_product[$i]['prd_code'])) {


		echo '<option selected="selected" value="'.$tbl_product[$i]['prd_code'].'">'.$tbl_product[$i]['prd_name'].'</option>';


	} else {


		echo '<option value="'.$tbl_product[$i]['prd_code'].'">'.$tbl_product[$i]['prd_name'].'</option>';


	}


}


?>


</select> <font color="red">(*)</font>


<span id="option">


<?php


if (isset($_POST['trn_prd_code'])) { 


	$trn_prd_code = $_POST['trn_prd_code'];


} else {


	if ($_GET['id']== "") {


		$trn_prd_code = $tempprd;


	} else {


		$trn_prd_code = $index_origin[0]["trn_prd_code"];


	}


}











echo "<!--";


$tbl_product_option=$mysqlIns->select_tbl_product_option($trn_prd_code);


echo "-->";


for($i=0;$i<count($tbl_product_option);$i++)


{


	//giấy


	//Cán


	//bế


	//xén


	//ghim


	//số màu


	//số liên


	echo "<!--";


	$tbl_product_type=$mysqlIns->select_tbl_product_option_list($trn_prd_code,strtoupper($tbl_product_option[$i]['tp_option_code']));


	echo "-->";


	//echo strtoupper($_POST['trn_'.strtoupper($tbl_product_option[$i]['tp_option_code'])]);


	//echo strtoupper($tbl_product_type[$j]['tp_code']);


	if (count($tbl_product_type) > 0) {


			echo '<b>&nbsp;&nbsp;&nbsp;'.$tbl_product_option[$i]['tp_option'].'&nbsp;</b>


			<select '.$disabled.$selectDisabledStyle.' name="trn_'.strtoupper($tbl_product_option[$i]['tp_option_code']).'" id="trn_'.strtoupper($tbl_product_option[$i]['tp_option_code']).'" style="border:1px solid #DADADA;">';


			


			for($j=0;$j<count($tbl_product_type);$j++)


			{


				if (isset($_POST['trn_'.strtoupper($tbl_product_option[$i]['tp_option_code'])]) && (strtoupper($_POST['trn_'.strtoupper($tbl_product_option[$i]['tp_option_code'])]) == strtoupper($tbl_product_type[$j]['tp_code']))) {


					echo '<option selected="selected" value="'.strtoupper($tbl_product_type[$j]['tp_code']).'">'.$tbl_product_type[$j]['tp_name'].'</option>';


				} elseif ($tbl_product_type[$j]['tp_checked'] == 1) {


					echo '<option selected="selected" value="'.strtoupper($tbl_product_type[$j]['tp_code']).'">'.$tbl_product_type[$j]['tp_name'].'</option>';


				} else {


					echo '<option value="'.strtoupper($tbl_product_type[$j]['tp_code']).'">'.$tbl_product_type[$j]['tp_name'].'</option>';


				}





			}


			echo '</select>';


	}


}


?>





</span>





	  </tr>


	<tr>


	<td scope="row" align="right"><b>Số lượng&nbsp;&nbsp;&nbsp;</b></td>


	<td><input maxlength="6"  onfocus="replace_comas(this);" autocomplete="off" onblur="return_comas(this); calTotal();" onkeypress="return onlynumber(event);" <?php echo $disabled; ?> onkeyup="calTotal();" name="trn_quantity" value="<?php echo isset($_POST['trn_quantity']) ? $_POST['trn_quantity'] : '' ?>" 	type="text" id="trn_quantity" size="5" style="border:1px solid #DADADA;"/>&nbsp;


	  <b>&nbsp;&nbsp;&nbsp;Đơn giá&nbsp;&nbsp;&nbsp;</b>


	  <input maxlength="10" onfocus="replace_comas(this);" autocomplete="off" onblur="return_comas(this); calTotal();" onkeypress="return onlynumber(event);" <?php echo $disabled; ?> onkeyup="calTotal();" name="trn_unit_price" value="<?php echo isset($_POST['trn_unit_price']) ? $_POST['trn_unit_price'] : '' ?>" type="text" id="trn_unit_price" size="15" style="border:1px solid #DADADA;"/>&nbsp;(VND)


	  <b>&nbsp;&nbsp;&nbsp;Thành tiền&nbsp;&nbsp;<font color="red">


	  </font></b>


	  <input <?php echo $disabled; ?> type="text" STYLE="background-color: #ebebe4;" readonly id="trn_amount_withoutVAT" name="trn_amount_withoutVAT" value="<?php echo isset($_POST['trn_amount_withoutVAT']) ? $_POST['trn_amount_withoutVAT'] : '' ?>" size="14">


	  <b>&nbsp;&nbsp;&nbsp;</b>


			<input <?php echo $disabled; ?> onclick="calTotal();" type="radio" name="trn_vat" <?php echo $_POST['trn_vat'] == 0 ? '' : 'checked' ?>


			id="trn_vat_v10" value="10"><label for="trn_vat_v10">&nbsp;Có VAT (10%)&nbsp;&nbsp;&nbsp;</label>


			<input <?php echo $disabled; ?> onclick="calTotal();" type="radio" name="trn_vat" <?php echo $_POST['trn_vat'] == 0 ? 'checked' : '' ?>


			id="trn_vat_v0" value="0" ><label for="trn_vat_v0">&nbsp;Không có VAT</label>


			


	   


	  </td>


    </tr>


	<div id="dialog_block_group" title="Chi tiết">


</div>


	<tr>


	<td scope="row" align="right"><b>Tổng tiền phải thu&nbsp;&nbsp;&nbsp;</b></td>


	<td>


	  <b><font color="red"><!--<span id="trn_amount"></span>--></font></b>


	  <input <?php echo $disabled; ?> type="text" STYLE="background-color: #ebebe4;" readonly id="trn_amount" name="trn_amount" value="<?php echo isset($_POST['trn_amount']) ? $_POST['trn_amount'] : '' ?>" size="14">


	  


	  <b>&nbsp;&nbsp;&nbsp;T&#7893;ng &#273;&#417;n h&#224;ng&nbsp;<font size="2" color="blue"><span id='trn_total_amount_text'></span></font></b>


	<label for="trn_payment_type_tm">&nbsp;Tiền mặt&nbsp;</label>	


	<input size="9" type="text" autocomplete="off" name="trn_payment_type_tm" onfocus="replace_comas(this);" onblur=" return_comas(this);" onkeypress="return onlynumber1(event);" <?php echo $disabled; ?> <?php /*echo isset($_POST['trn_payment_type_tm']) ? $_POST['trn_payment_type_tm'] : '0'*/ ?>			id="trn_payment_type_tm" value="<?php /*echo isset($_POST['trn_payment_type_tm']) ? $_POST['trn_payment_type_tm'] : ''*/ ?>">


	<label for="trn_payment_type_ck">&nbsp;Chuyển khoản&nbsp;</label>		


	<input size="9" type="text" autocomplete="off" name="trn_payment_type_ck" onfocus="replace_comas(this);" onblur="return_comas(this);" onkeypress="return onlynumber1(event);" <?php echo $disabled; ?> <?php echo isset($_POST['trn_payment_type_ck']) ? $_POST['trn_payment_type_ck'] : '0' ?>			id="trn_payment_type_ck" value="<?php echo isset($_POST['trn_payment_type_ck']) ? $_POST['trn_payment_type_ck'] : '' ?>">


	<b>&nbsp;&nbsp;&nbsp;Tổng đã trả (VNĐ)&nbsp;</b>


	<input STYLE="background-color: #ebebe4;" readonly maxlength="10" autocomplete="off" <?php echo $disabled; ?> onkeyup="" name="trn_payment" value="<?php echo isset($_POST['trn_payment']) ? $_POST['trn_payment'] : '' ?>"


		type="text" id="trn_payment" size="9" style="border:1px solid #DADADA;"/>&nbsp;
		<span class="dialog_block_group" title="Xem lịch sử đặt cọc" id="trn_payment_his" value="<?php echo isset($_POST['trn_id']) ? $_POST['trn_id'] : '' ?>"><img src="images/history.png" height="18"> L&#7883;ch s&#7917;</span>


	</tr>

	<!--

	<tr>


		<td scope="row" align="right"><b>Xác thực đã thu&nbsp;&nbsp;&nbsp;</b></td>


		<td>	 


		<?php


	  if ((strpos($_SESSION['MM_group'],'CARE,') !== false) || ($_SESSION['MM_Isadmin'] == "1")) {


      ?>




	  <b><font size=4><span id="payment_auth_status" <?php if ($index_origin[0]['payment_status']!="1") echo "style=\"cursor: pointer;\" onclick=\"updatePaymentAuth()\"";?>>&nbsp;<?php if ($index_origin[0]['payment_status']=="1") echo "<font color='#ccc'>A</font>"; else echo "U";?></span></b></font>&nbsp;<span id='trn_payment_auth'><?php echo isset($_POST['trn_payment_auth']) ? $_POST['trn_payment_auth'] : '' ?></span> (VND) 


	  <span class="dialog_block_group" title="Xem lịch sử đặt cọc" id="trn_payment_his" value="<?php echo isset($_POST['trn_id']) ? $_POST['trn_id'] : '' ?>"><img src="images/history.png" height="18"></span>


	  <?php


	  }


      ?>


	   <b>&nbsp;&nbsp;&nbsp;Còn thiếu&nbsp;&nbsp;<font color="red"></font></b>


	   <input <?php echo $disabled; ?> type="text" STYLE="background-color: #ebebe4;" readonly id="trn_payment_remain" name="trn_payment_remain" value="<?php echo isset($_POST['trn_payment_remain']) ? $_POST['trn_payment_remain'] : '' ?>" size="14">


	  </td>


    </tr>-->


	


	<tr>


		<td scope="row" align="right"><b>Nội dung yêu cầu &nbsp;</b></td>


      <td scope="row" align="left">


		<textarea <?php echo $disabled; ?> name="trn_detail" type="text" id="trn_detail" cols="100" rows="4" style="border:1px solid #DADADA;"><?php echo isset($_POST['trn_detail']) ? $_POST['trn_detail'] : '' ?></textarea>


	  </td>


      


    </tr>


	<tr>


		<td scope="row" align="right"><b>Upload hóa đơn/Yêu cầu &nbsp;</b></td>


      <td scope="row" align="left">


		<input multiple="" <?php echo $disabled; ?> id="trn_img" type="file" name="trn_img[]" size="21" onclick="javascript:$('#trn_img_show').html('');">


	  </td>


      


    </tr>


	<tr>


		<td scope="row" align="right"></td>


      <td scope="row" align="left"><span id="trn_img_show"></span></td>


      


    </tr>





	</table>


	</fieldset>


	</td></tr>


	


	


    <tr><td colspan=2 width="100%">


	<fieldset id="delivertable"><legend>Yêu cầu về thời hạn</legend>


	<table width="100%" cellspacing="0" cellpadding="0">


    


	  <!--<tr height="35">


      <td scope="row" align="right"><b>Phân loại đơn hàng &nbsp;</b></td>


      <td width="80%" align="left"><label for="name"></label>


			<input type="radio" name="trn_class" <?php echo $_POST['trn_class'] == 0 ? 'checked' : '' ?>


			id="trn_class_v0" value="0" onclick="changecolor();" checked>&nbsp;Đơn hàng thường&nbsp;&nbsp;&nbsp;


			<input type="radio" name="trn_class" <?php echo $_POST['trn_class'] == 1 ? 'checked' : '' ?>


			id="trn_class_v1" value="1" onclick="changecolor();">&nbsp;Đơn hàng gấp


			


	 


     </td>


    </tr>-->


	


	<tr>


      <td width="17%" scope="row" align="right"><b>Hẹn trả hàng &nbsp;</b></td>


      <td width="83%" align="left"><label for="name"></label>


			<input <?php echo $disabled; ?> type="radio" name="trn_type_code" <?php echo $_POST['trn_type_code'] == 0 ? 'checked' : '' ?>


			id="trn_type_code_v0" value="0"><label for="trn_type_code_v0">&nbsp;Sau khi duyệt thiết kế&nbsp;</label>


			<input maxlength="3" onkeypress="return onlynumber(event);" <?php echo $disabled; ?> onchange="check1();" name="trn_during" value="<?php echo isset($_POST['trn_during']) ? $_POST['trn_during'] : '4' ?>"


			type="text" id="trn_during" size="1" style="border:1px solid #DADADA;"/> (ngày)


			


			


			<input <?php echo $disabled; ?> type="radio" name="trn_type_code" <?php echo $_POST['trn_type_code'] == 1 ? 'checked' : '' ?>


			id="trn_type_code_v1" value="1"><label for="trn_type_code_v1">&nbsp;Chốt ngày giao&nbsp;</label>


			<input maxlength="10"  onkeypress="return onlydate(event);" <?php echo $disabled; ?> onchange="check2();" name="trn_end_date" value="<?php echo isset($_POST['trn_end_date']) ? $_POST['trn_end_date'] : '' ?>"


			type="text" id="trn_end_date" size="10" style="border:1px solid #DADADA;"/> (Định dạng: DD-MM-YYYY)


	 


     </td>


    </tr>


	<tr>


      <td scope="row" align="right"><b>Địa chỉ giao hàng &nbsp;</b></td>


      <td width="80%" align="left"><label for="name"></label>


			<input <?php echo $disabled; ?> type="radio" name="trn_deliver_type" <?php echo $_POST['trn_deliver_type'] == 0 ? 'checked' : '' ?>


			id="trn_deliver_type_v0" value="0"><label for="trn_deliver_type_v0">&nbsp;Khách đến lấy&nbsp;&nbsp;&nbsp;</label>


			


			<input <?php echo $disabled; ?> type="radio" name="trn_deliver_type" <?php echo $_POST['trn_deliver_type'] == 1 ? 'checked' : '' ?>


			id="trn_deliver_type_v1" value="1"><label for="trn_deliver_type_v1">


			&nbsp;Giao tận nơi cho khách


			, Địa chỉ giao&nbsp;</label>


			


			


			<input <?php echo $disabled; ?> onchange="check3();"


			name="trn_deliver_address" value="<?php echo isset($_POST['trn_deliver_address']) ? $_POST['trn_deliver_address'] : '' ?>"


			type="text" id="trn_deliver_address" size="70" style="border:1px solid #DADADA;"/>


	 


     </td>


    </tr>


	


	</table>


	</fieldset>


	</td></tr>


	


	<tr><td colspan=2 width="100%">


	<fieldset id="delivertable"><legend>Trạng thái/Tiến độ đơn hàng</legend>


	<table width="100%" cellspacing="0" cellpadding="0">


    <tr id="rowdesign">


	<td width="17%" scope="row" align="right"  nowrap="nowrap"><b>Người thiết kế &nbsp;</b></td>


      <td valign="middle" nowrap="nowrap"><label for="status"><img src="images/designer.png" width="16"></label>


        <select <?php echo $disabled.$selectDisabledStyle; ?> name="prg_step2_by" id="prg_step2_by" style="border:1px solid #DADADA;">


<?php


echo "<!--";


$tbl_user_design=$mysqlIns->select_tbl_user("DESIGN");


echo "-->";


for($i=0;$i<count($tbl_user_design);$i++)


{


	$locked = '';


	$prefixed = '';


	if ($tbl_user_design[$i]['user_stat'] != 'O') {


		$locked = ' (Locked)';


		$prefixed = '***';


	}


	if (isset($_POST['prg_step2_by']) && ($_POST['prg_step2_by'] == $tbl_user_design[$i]['user_name_u'])) {


		echo '<option selected="selected" value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	} else {


		echo '<option value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	}





}


?>


      </select>


	  &nbsp;<span id="design_user_value"></span>&nbsp;&nbsp;


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Bắt đầu: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step2_dt1"></span></font></b>


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;"> 


			Xong: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step2_dt2"></span></font></b>


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Duyệt: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step2_dt3"></span></font></b>


	  </td>


	  


	  </tr>


	  <tr>


	  <td scope="row" align="right" nowrap="nowrap"><b>Người Sản xuất &nbsp;</b></td>


      <td valign="middle" nowrap="nowrap"><label for="status"><img src="images/builder.png" width="16"></label>


        <select <?php echo $disabled.$selectDisabledStyle; ?> name="prg_step3_by" id="prg_step3_by" style="border:1px solid #DADADA;">


<?php


echo "<!--";


$tbl_user_design=$mysqlIns->select_tbl_user("BUILD");


echo "-->";


for($i=0;$i<count($tbl_user_design);$i++)


{


	$locked = '';


	$prefixed = '';


	if ($tbl_user_design[$i]['user_stat'] != 'O') {


		$locked = ' (Locked)';


		$prefixed = '***';


	}


	


	if (isset($_POST['prg_step3_by']) && ($_POST['prg_step3_by'] == $tbl_user_design[$i]['user_name_u'])) {


		echo '<option selected="selected" value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	} else {


		echo '<option value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	}





}


?>


      </select>


&nbsp;<span id="build_user_value"></span>&nbsp;&nbsp;


	  


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Bắt đầu: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step3_dt1"></span></font></b>


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			lỗi: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_issue_date"></span></font></b>


			&nbsp;&nbsp;Thiệt hại 


			<input maxlength="15" onfocus="replace_comas(this);" onblur="return_comas(this);" onkeypress="return onlynumber(event);" <?php echo $disabled; ?> name="prg_issue_value" value="<?php echo isset($_POST['prg_issue_value']) ? $_POST['prg_issue_value'] : '' ?>"


			type="text" id="prg_issue_value" size="11" style="border:1px solid #DADADA;"/> 


	   <select width="10" <?php echo $disabled.$selectDisabledStyle; ?> name="prg_issue_from" id="prg_issue_from" style="border:1px solid #DADADA;">


<option value="">--Chọn--</option>


	   


	   <?php


echo $_GET['id'].'@'.$_POST['trn_id']."<!--";


$tran_id=(isset($_GET['id']) && $_GET['id'] != "")?$_GET['id']:$_POST['trn_id'];


$user_issue_list=$mysqlIns->get_user_issue_list($tran_id);


echo "-->";


for($i=0;$i<count($user_issue_list);$i++)


{


	if (isset($_POST['prg_issue_from']) && ($_POST['prg_issue_from'] == $user_issue_list[$i]['prg_issue_from'])) {


		echo '<option selected="selected" value="'.$user_issue_list[$i]['prg_issue_from'].'">'.$user_issue_list[$i]['prg_issue_from'].'</option>';


	} else {


		echo '<option value="'.$user_issue_list[$i]['prg_issue_from'].'">'.$prefixed.$user_issue_list[$i]['prg_issue_from'].'</option>';


	}





}


?>


       </select>


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Xong: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step3_dt2"></span></font></b>


	  </td>


	  </tr>


	  <tr>


	  <td scope="row" align="right" nowrap="nowrap"><b>Người giao hàng &nbsp;</b></td>


      <td valign="middle" nowrap="nowrap"><label for="status"><img src="images/deliver.png" width="16"></label>


        <select <?php echo $disabled.$selectDisabledStyle; ?> name="prg_step4_by" id="prg_step4_by" style="border:1px solid #DADADA;">


<?php


echo "<!--";


$tbl_user_design=$mysqlIns->select_tbl_user("DELIVER");


echo "-->";


for($i=0;$i<count($tbl_user_design);$i++)


{


	$locked = '';


	$prefixed = '';


	if ($tbl_user_design[$i]['user_stat'] != 'O') {


		$locked = ' (Locked)';


		$prefixed = '***';


	}


	if (isset($_POST['prg_step4_by']) && ($_POST['prg_step4_by'] == $tbl_user_design[$i]['user_name_u'])) {


		echo '<option selected="selected" value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	} else {


		echo '<option value="'.$tbl_user_design[$i]['user_name_u'].'">'.$prefixed.$tbl_user_design[$i]['user_name_u'].' - '.$tbl_user_design[$i]['user_fullname'].$locked.'</option>';


	}





}


?>


      </select>


	  &nbsp;<span id="deliver_user_value"></span>&nbsp;&nbsp;


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Bắt đầu: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step4_dt1"></span></font></b>


	  </td>


	  <td nowrap="nowrap">


			


	  </td>


	  <td nowrap="nowrap">


			


	  </td>


	  <td nowrap="nowrap" style="padding-left:5px;">


			Xong: 


	  </td>


	  <td nowrap="nowrap">


			<b><font color="black"><span id="prg_step4_dt2"></span></font></b>


	  </td>


	  </tr>


	  


	


	<tr>


		<td scope="row" align="right" ><b>Ý kiến khách hàng &nbsp;</b></td>


      <td scope="row" align="left" colspan="7">


		<textarea <?php echo $disabled; ?> name="prg_note" type="text" id="prg_note" cols="100" rows="3" style="border:1px solid #DADADA;"><?php echo isset($_POST['prg_note']) ? $_POST['prg_note'] : '' ?></textarea>


	  </td>


      


    </tr>


	</table>


	</fieldset>


	</td></tr>


	


	<tr >


	 

      <td align="center" colspan=2 nowrap="nowrap">


	  


	  <table width="80%" cellspacing="0" cellpadding="0">


<thead>


<tr height="2" >





		<td id="action_button" width="20%" align="center" valign="middle">


		<div class="container" >




<a href="javascript:" class="button" onclick="return save_validate();"><span><img src="images/save.png" height="30"/></span>Lưu đơn hàng</a>





</div>


		


		</td>


		


		<td width="20%" align="center" valign="middle">


		<div class="container" >





<a href="javascript:" class="button" onclick='window.open("invoice-print.php?ref=<?php if ($index_origin[0]["trn_ref"] != "") echo $index_origin[0]["trn_ref"]; else if ($_POST['trn_ref']!="") echo $_POST['trn_ref']; else echo $maxref[0]['maxref'] + 1;?>", "print-invoice", "width=600, height=650")'><span><img src="images/print.png" height="30"/></span>In biên nhận</a>





</div>


		


		</td>


		


		


<?php if ((($_SESSION['MM_Isadmin'] == 1) || ($index_origin[0]["prg_pending_by"] == strtoupper($_SESSION['MM_Username']))) &&


			($_GET["mode"] == "viewdetail" || 


			(isset($_POST["MM_update"]) && $_POST["MM_update"] == "form1"))


		 ) 


		 { ?>


		<td width="20%" align="center" valign="middle">





		


		<div class="container" >








<?php //if ($disabled != '') {


		//echo '<a href="javascript:" class="button" onclick="return save_validate();"><span><img src="images/save.png" height="30"/></span>Update đơn hàng</a>';


	//} else {


		if ($index_origin[0]["prg_status"] < 42) {


			echo '<a href="?mode=viewdetail&id='.$_REQUEST['id'].'&do=xuly&status='.$index_origin[0]["prg_status"].'&ref='.$index_origin[0]["trn_ref"].'" class="button" ><span><img src="images/check.png" height="30"/></span>Xử lý đơn hàng</a>';


			//echo '<a href="?mode=viewdetail&id='.$_REQUEST['id'].'&do=revert&status='.$index_origin[0]["prg_status"].'&ref='.$index_origin[0]["trn_ref"].'" class="button" ><span><img src="images/check.png" height="30"/></span>Back lại trạng thái</a>';


		}


	//} 


?>





</div>


	  </td>


	  


<td width="20%" align="center" valign="middle">


		<div class="container" >


<?php //if ($disabled != '') {


		//echo '<a href="javascript:" class="button" onclick="return save_validate();"><span><img src="images/save.png" height="30"/></span>Update đơn hàng</a>';


	//} else {


		if ($index_origin[0]["prg_status"] > 12) {


			echo '<a href="?mode=viewdetail&id='.$_REQUEST['id'].'&do=revert&status='.$index_origin[0]["prg_status"].'&ref='.$index_origin[0]["trn_ref"].'" class="button" ><span><img src="images/refresh.png" height="30"/></span>Back lại trạng thái</a>';


			//echo '<a href="?mode=viewdetail&id='.$_REQUEST['id'].'&do=revert&status='.$index_origin[0]["prg_status"].'&ref='.$index_origin[0]["trn_ref"].'" class="button" ><span><img src="images/check.png" height="30"/></span>Back lại trạng thái</a>';


		}


	//} 


?>


</div>


		


		</td>


	  


<?php }?>


	</tr>


</thead>


</table>





	  


	  </td>


	  


	  <td align="left" nowrap="nowrap">


	  


    </tr>


  </table>


  <input type="hidden" id="MM_update" name="MM_update" value="form1" />





</table>


</form>


<?php


if (isset($ds_nguoidung) && $ds_nguoidung != null)


mysql_free_result($ds_nguoidung);


?>





<script type="text/javascript">


			$( document ).ready(function() {

				//loadAvailbleOrderAdd();

				//calTotalAmount();
				
				$('#trn_payment_type_ck').val('0');
				$('#trn_payment_type_tm').val('0');


			});


		function fitScreen() {


			//alert($('#action_code').val());


			if ($('#action_code').val() == '0') {


				$('#content').html('');


			}


		}


        function changecolor() {


			if ($('#trn_end_date').val().length > 0) {


				document.getElementById('delivertable').style.backgroundColor = '#ff7373';


			} else {


				document.getElementById('delivertable').style.backgroundColor = '';


			}


		}


		


		function checkPhone()


		{


			


			$('#cust_phone').val($('#cust_phone').val().replace(/ /g,'').replace(/\./g,'').replace(/\-/g,'')); 


			if ($('#cust_phone').val() == "") {


				return;


			}


			


			var x=$('#cust_phone').val();


			var regex=/^[0-9]+$/;


			if (!x.match(regex))


			{


				//return false;


				$('#cust_phone').focus();


				$.growl.error({ message: "Số điện thoại phải dạng số!" });


			} 


			


			//return true;


		}





		function save_validate() {


			if ($('#cust_phone').val() == "") {





				//alert('Bạn chưa nhập số điện thoại khách hàng!');


				$.growl.error({ message: "Bạn chưa nhập số điện thoại khách hàng!" });


				$('#cust_phone').focus();


				return false;


			}


			else if ($('#trn_ref').val() == "") {


				//alert('Bạn chưa nhập mã hóa đơn!');


				$.growl.error({ message: "Bạn chưa nhập mã hóa đơn!" });


				$('#trn_ref').focus();


				return false;


			}


			else if ($('#trn_name').val() == "") {


				//alert('Bạn chưa nhập tiêu đề đơn hàng!');


				$.growl.error({ message: "Bạn chưa nhập tiêu đề đơn hàng!" });


				$('#trn_name').focus();


				return false;


			}


			else if ($('#trn_prd_code').val() == "") {


				//alert('Bạn chưa chọn sản phẩm!');


				$.growl.error({ message: "Bạn chưa chọn sản phẩm!" });


				$('#trn_prd_code').focus();


				return false;


			}


			


			conf = false;


			if ($("#action_code").val() == '1') {


				//conf = confirm('Update ngay phải không bạn?')


			} else if ($("#action_code").val() == '2') {


				//conf = confirm('Thêm sản phẩm này vào cùng hóa đơn phía trên phải không bạn?')


			} else {


				//conf = confirm('Lưu đơn hàng mới này nhé bạn?')


				$("#action_code").val('0');


			}


			//if (!conf) {


			//	//searchref($('#trn_ref').val(),$("#action_code").val(),$('#trn_id').val());


			//	$('#trn_ref').focus();


			//	return false;


			//} else {


				$('#confirm_update').val('1');


			//}


			


			//document.getElementById("saveTran").action = "";


			makeBlockUI();


			document.getElementById("saveTran").submit();





		}		


		function searchcust(e,val)


		{


			//if (e.keyCode != 9) return false;


			//if (val == '') return false;


			//var delay=500;//1 seconds


			//setTimeout(function(){


			search();


			param = 'cust_name=' + $('#cust_name').val() + 


						'&cust_company=' + $('#cust_company').val() + 


						'&cust_email=' + $('#cust_email').val() +


						'&cust_phone=' + $('#cust_phone').val() +


						//'&ignoreid=' + <?php echo $_GET['id']==""?"-1":$_GET['id']; ?> +


						'&trn_ref=' + $('#trn_ref').val() +


						'&action_code=' + $('#option_code').val() +


						'&trn_id=' + $('#trn_id').val();


			//alert(param);


			$.ajax({


				url : 'json_search_cust.php',


				data :  param,


				type : 'get',


				dataType : '',


				success : function (result)


				{


					


						// Gán kết quả vào div#content


						$('#content').html(result);


					


				}


			});


			//your code to be executed after 1 seconds


			//},delay); 


			


			


		}


		


		function searchorder(e,code,val)


		{


			//if (e.keyCode != 9) return false;


			if (val == '') return false;


			if (code == 2) {


				if ($('#trn_ref').val() != "") return false;


			}


			//alert($('#action_code').val());
			param = 'trn_ref=' + $('#trn_ref').val() +

						'&trn_name=' + $('#trn_name').val() +

						'&search_code=' + code +

						'&action_code=' + $('#action_code').val() +

						'&trn_id=' + $('#trn_id').val();
			//alert(param);


			search();


			$.ajax({


				url : 'json_search_cust.php',


				data :  param,


				type : 'get',


				dataType : '',


				success : function (result)


				{


					


						// Gán kết quả vào div#content


						$('#content').html(result);


					


				}


			});


		}


		


		


		function search()


		{


			loading = '<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">';


			loading += '<tr bgcolor="#ffffff">';


			loading += '<td align="center" colspan=\'15\'><img src="images/loadingjson.gif" height="50"></td>';


			loading += '</tr>';


			loading += '<tr bgcolor="#bdb9b9" height="1">';


			loading += '<td align="left" colspan="15"></td>';


			loading += '</tr>';


			loading += '</table>';





			$('#content').html(loading);


			


			//var delay=500;//1 seconds


			//setTimeout(function(){





			//your code to be executed after 1 seconds


			//},delay); 


		}


		


		function calTotal(){


			if (($('#trn_quantity').val() != "") && ($('#trn_unit_price').val() != "")){


				trn_amount_withoutVAT = parseInt($('#trn_quantity').val().replace(/,/g,'')) * parseInt($('#trn_unit_price').val().replace(/,/g,''));


				$('#trn_amount_withoutVAT').val(trn_amount_withoutVAT.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


				


				VAT = 0;


				//alert($('#trn_vat_v10').prop("checked"));


				if ($('#trn_vat_v10').prop("checked")) VAT = 10;


				trn_amount = trn_amount_withoutVAT + trn_amount_withoutVAT / 100 * VAT;


				$('#trn_amount').val(trn_amount.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			}


			


			//if ($('#trn_unit_price').val() == "") trn_unit_price = 0;


			trn_payment_type_tm = 0;


			trn_payment_type_ck = 0;


			if ($('#trn_payment_type_tm').val() == "") {


				trn_payment_type_tm = 0;


			} else {


				trn_payment_type_tm = parseInt($('#trn_payment_type_tm').val());


			}


								


			if ($('#trn_payment_type_ck').val() == "") {


				trn_payment_type_ck = 0;


			} else {


				trn_payment_type_ck = parseInt($('#trn_payment_type_ck').val());


			}


			if ($('#trn_payment_auth').html() == "") {


				$('#trn_payment_auth').html('0')


			} 


			if (($('#trn_quantity').val() != "") && ($('#trn_unit_price').val() != "")){


				calTotalAmount();


			}


		}


		


		function calTotalAmount() {


			


			trn_payment = 0;


			trn_payment_type_tm = 0;


			trn_payment_type_ck = 0;


			if ($('#trn_payment').val() != "") {


				trn_payment = eval($('#trn_payment').val().replace(/,/g,''));


			}


			if ($('#trn_payment_type_tm').val() != "") {


				//trn_payment_type_tm = eval($('#trn_payment_type_tm').val().replace(/,/g,''));


			}


			if ($('#trn_payment_type_ck').val() != "") {


				//trn_payment_type_ck = eval($('#trn_payment_type_ck').val().replace(/,/g,''));


			}


			if ($('#trn_payment_auth').html() == "") {


				$('#trn_payment_auth').html('0')


			} 


			trn_payment = trn_payment_type_tm + trn_payment_type_ck;


			//$('#trn_payment').val(trn_payment.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_payment_type_tm').val(eval(trn_payment_type_tm).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


			$('#trn_payment_type_ck').val(eval(trn_payment_type_ck).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


								


			trn_amount_id = 0;


			trn_amount = 0;


			if ($('#action_code').val() == '2') {





			} else {


				//alert($('#trn_amount').val());


				if ($('#trn_id').val() != "") {


				id = $('#trn_id').val();


				if ($('#trn_amount_' +id).val() != "") {


					trn_amount_id = $('#trn_amount_' +id).val().replace(/,/g,'');


				}


			}


			}


			//alert($('#trn_id').val());


			


			


					


			if ($('#trn_amount').val() != "") {


				trn_amount = $('#trn_amount').val().replace(/,/g,'');


			}


				


			//alert($('#trn_total_amount').val());


			//alert('trn_total_amount=' + $('#trn_total_amount').val() + ',trn_amount_id=' + trn_amount_id + ',trn_amount:' +trn_amount + ',trn_payment:' + trn_payment);


			total_amount = parseInt($('#trn_amount_all_' + id).val().replace(/,/g,''));

						   


			$('#trn_total_amount_text').html(total_amount.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			


			total_remain = total_amount - parseInt(trn_payment);


			$('#trn_payment_remain').val(total_remain.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));


		}


		


		function check1() {


			if ($('#trn_during').val() != "") {


				$('#trn_type_code_v0').prop('checked',true);


				$('#trn_type_code_v1').prop('checked',false);


				$('#trn_end_date').val('');


				changecolor();


			}


		}


		


		function check2() {


			if ($('#trn_end_date').val() != "") {


				$('#trn_type_code_v0').prop('checked',false);


				$('#trn_type_code_v1').prop('checked',true);


				$('#trn_during').val('');


				changecolor();


			}


		}


		


		function check3() {


			if ($('#trn_deliver_address').val() != "") {


				$('#trn_deliver_type_v0').prop('checked',false);


				$('#trn_deliver_type_v1').prop('checked',true);


			}


		}


		


		function clearDate() {


			if ($('#trn_ref').val() != $('#trn_ref_<?php echo $_GET['id'] ?>').val()) {


				$('#prg_step2_dt1').html('');


				$('#prg_step2_dt2').html('');


				$('#prg_step2_dt3').html('');


				$('#prg_step3_dt1').html('');


				$('#prg_step3_dt2').html('');


				$('#prg_issue_date').html('');


				$('#prg_step4_dt1').html('');


				$('#prg_step4_dt2').html('');


			} else {


				$('#prg_step2_dt1').html($('#prg_step2_dt1_<?php echo $_GET['id'] ?>').val());


				$('#prg_step2_dt2').html($('#prg_step2_dt2_<?php echo $_GET['id'] ?>').val());


				$('#prg_step2_dt3').html($('#prg_step2_dt3_<?php echo $_GET['id'] ?>').val());


				$('#prg_step3_dt1').html($('#prg_step3_dt1_<?php echo $_GET['id'] ?>').val());


				$('#prg_step3_dt2').html($('#prg_step3_dt2_<?php echo $_GET['id'] ?>').val());


				$('#prg_issue_date').html($('#prg_issue_date_<?php echo $_GET['id'] ?>').val());


				$('#prg_step4_dt1').html($('#prg_step4_dt1_<?php echo $_GET['id'] ?>').val());


				$('#prg_step4_dt2').html($('#prg_step4_dt2_<?php echo $_GET['id'] ?>').val());


			}


		}


		


		function hideDesign(obj){


			//toggle_it("rowdesign");


			//alert($('#trn_has_file').is(':checked'));





			if ($('input[name=trn_has_file]').is(':checked')) { 


					document.getElementById("rowdesign").style.display = 'none' 


			  } else { 


					document.getElementById("rowdesign").style.display = '' 


			  }  


		}


		


		function changePrd() {


			makeBlockUI();


			$("#MM_update").val("changePrd");


			$('#trn_giay_<?php echo $_GET['id'] ?>').val("");


			$('#trn_can_<?php echo $_GET['id'] ?>').val("");


			$('#trn_be_<?php echo $_GET['id'] ?>').val("");


			$('#trn_xen_<?php echo $_GET['id'] ?>').val("");


			$('#trn_ghim_<?php echo $_GET['id'] ?>').val("");


			$('#trn_somau_<?php echo $_GET['id'] ?>').val("");


			$('#trn_solien_<?php echo $_GET['id'] ?>').val("");


			


			document.getElementById("saveTran").submit();


		}


		


		


		$(".dialog_block_group").click(function(){	


				


				makeBlockUI();


					//alert('y');


				var id=$("#trn_id").val();	


				//alert(id);


				$.ajax({


						    	    


						type: "GET",      


						url: 'json_payment_his.php',		      


						data: "id=" + id + "&ref=" + $("#trn_ref").val(),


						


						


						success: function(resp){


							 


							$.blockUI({ 


								message: resp, 


								css: { 


									top:  '20%',


									left: '27%',


									width: '600px' 


								} 


							});


							$('.blockOverlay').attr('title','Click to unblock').click($.unblockUI); 


						//$("#dialog_block_group").html(resp);


								


						},      


						error: function(e){


							$.unblockUI;


							alert('Error: ' + e.responseText);


						}


					});	    


			});   


			


		function updatePaymentAuth() {


			if ($('#payment_auth_status').html() == "<font color=\"#ccc\">A</font>") return;


			


			loading = '<img src="images/loadingjson.gif" height="20">';


			$('#trn_payment_auth').html(loading);


			var isudp_payment = false;


			var param_payment = 'trn_id=' + $('#trn_id').val() + 


						'&trn_ref=' + $('#trn_ref').val() + 


						'&editval=' + $('#trn_payment').val() + 


						'&objid=trn_payment_' + $('#trn_id').val();


			//alert(param_payment);


			$.ajax({


				url : 'json_upd_payment.php',


				data : param_payment,


				type : 'get',


				dataType : '',


				success : function (result)


				{


					$('#trn_payment_auth').html($('#trn_payment').val());


					$('#payment_auth_status').html("<font color='#ccc'>A</font>");


					$('#payment_auth_status').attr("style","");


				}


			});


		}


</script>