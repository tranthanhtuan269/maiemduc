<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
if (!isset($_SESSION)) {
  session_start();
}

require_once('./global.php'); 
$trn_id =$_REQUEST['trn_id'];
$editval_tm =str_replace(',','',$_REQUEST['editval_tm']);$editval_ck =str_replace(',','',$_REQUEST['editval_ck']);$editval = str_replace(',','',$_REQUEST['editval']);
$objid =$_REQUEST['objid'];
$trn_ref =$_REQUEST['trn_ref'];
if ($objid == "trn_payment_type_tm_".$trn_id || $objid == "trn_payment_type_ck_".$trn_id) {
	$updateSQL = sprintf("UPDATE tbl_trans SET
									trn_payment = 0,
									trn_payment_remain = 0
								  WHERE trn_ref = '%s'",
							   $trn_ref
							   );
	echo $updateSQL;
	mysql_select_db($dbhost, $db);
	
	
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	$updateSQL = sprintf("SELECT sum(trn_amount) trn_total_amount
						  from tbl_trans a
						  WHERE a.trn_ref = '%s'",
					      $trn_ref);
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		$fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC);
	$total_amount = $fetchData["trn_total_amount"];
	

	$updateSQL = sprintf("SELECT *
						  from tbl_trans a
						  WHERE a.trn_ref = '%s'",
					      $trn_ref);
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());

	$trn_payment_type_tm_his = 0;
	$trn_payment_type_ck_his = 0;
	
	if ($fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC)) {
		$trn_payment_type_tm = $fetchData["trn_payment_type_tm"];
		$trn_payment_type_ck = $fetchData["trn_payment_type_ck"];
	}
	$trn_payment_type_tm_his = str_replace(',','',$editval_tm) - $trn_payment_type_tm_his;
	$trn_payment_type_ck_his = str_replace(',','',$editval_ck) - $trn_payment_type_ck_his;
	$trn_payment_add = $trn_payment_type_tm_his + $trn_payment_type_ck_his;
	$auth_by = $_SESSION['MM_Username'];
	$auth_date = "SYSDATE()";	
	
	$updateSQL = sprintf("UPDATE tbl_trans SET
									prg_step5_dt1 = SYSDATE(), 
									prg_payment_dt = SYSDATE(),
									prg_step5_by = UCASE('".$_SESSION['MM_Username']."'),
									trn_payment = %s,		
									prg_payment_tm_add = %s - $trn_payment_type_tm,									
									prg_payment_ck_add = %s - $trn_payment_type_ck,
									trn_payment_type_tm = %s,									
									trn_payment_type_ck = %s,
									trn_payment_remain = ".$total_amount." - %s
								  WHERE trn_ref = %s",
							   $editval,
							   $editval_tm,							   
							   $editval_ck,
							   $editval_tm,							   
							   $editval_ck,
							   $editval,
							   $trn_ref
							   );
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	
	$updateSQL = sprintf("INSERT INTO tbl_payment_his(
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
									'1',
									'%s',
									'%s',
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
							   $trn_id,
							   $trn_ref,
							   str_replace(',','',$editval),							   
							   str_replace(',','',$editval_tm),							   
							   str_replace(',','',$editval_ck),
							   str_replace(',','',$trn_payment_add),
							   str_replace(',','',$trn_payment_type_tm_his),
							   str_replace(',','',$trn_payment_type_ck_his),
								$_SESSION['MM_Username']
							   );
	echo $updateSQL;
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
} elseif ($objid == "trn_payment_remain_".$trn_id) {
	$updateSQL = sprintf("UPDATE tbl_trans SET
									trn_payment = 0,
									trn_payment_remain = 0
								  WHERE trn_ref = '%s'",
							   $trn_ref
							   );
	echo $updateSQL;
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	
	$updateSQL = sprintf("SELECT sum(trn_amount) trn_total_amount
						  from tbl_trans a
						  WHERE a.trn_ref = '%s'",
					      $trn_ref);
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		$fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC);
	$total_amount = $fetchData["trn_total_amount"];
	
	$updateSQL = sprintf("SELECT *
						  from tbl_trans a
						  WHERE a.trn_ref = '%s'",
					      $trn_ref);
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		$fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC);;
	
	$trn_payment_type_tm_his = 0;
	$trn_payment_type_ck_his = 0;
	
	if ($fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC)) {
		$trn_payment_type_tm = $fetchData["trn_payment_type_tm"];
		$trn_payment_type_ck = $fetchData["trn_payment_type_ck"];
	}
	$trn_payment_type_tm_his = str_replace(',','',$editval_tm) - $trn_payment_type_tm_his;
	$trn_payment_type_ck_his = str_replace(',','',$editval_ck) - $trn_payment_type_ck_his;
	$trn_payment_add = $trn_payment_type_tm_his + $trn_payment_type_ck_his;
	$auth_by = $_SESSION['MM_Username'];
	$auth_date = "SYSDATE()";
	
	
	$updateSQL = sprintf("UPDATE tbl_trans SET
									prg_step5_dt1 = SYSDATE(), 
									prg_payment_dt = SYSDATE(),
									prg_step5_by = UCASE('".$_SESSION['MM_Username']."'),
									trn_payment_remain = %s,
									trn_payment = ".$total_amount." - %s,									
									trn_payment_tm_add = %s - $trn_payment_type_tm,									
									trn_payment_ck_add = %s - $trn_payment_type_ck,
									trn_payment_type_tm = %s,									
									trn_payment_type_ck = %s
								  WHERE trn_ref = %s",
							   $editval,
							   $editval,
							   $editval_tm,							   
							   $editval_ck,							   
							   $editval_tm,							   
							   $editval_ck,
							   $trn_ref
							   );
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	
	$updateSQL = sprintf("INSERT INTO tbl_payment_his(
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
									'1',
									'%s',
									'%s',
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
							   $trn_id,
							   $trn_ref,
							   str_replace(',','',$editval),							   
							   str_replace(',','',$editval_tm),							   
							   str_replace(',','',$editval_ck),
							   str_replace(',','',$trn_payment_add),
							   str_replace(',','',$trn_payment_type_tm_his),
							   str_replace(',','',$trn_payment_type_ck_his),
								$_SESSION['MM_Username']
							   );
	echo $updateSQL;
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
} elseif ($objid == "trn_end_date_".$trn_id) {
	if ($editval == "") { 
		$valueDate = "null";
	} elseif(strlen($editval)==10) {
		$valueDateArr = split('-', $editval);
		$valueDate = $valueDateArr[2].'-'.$valueDateArr[1].'-'.$valueDateArr[0];
		$updateSQL = sprintf("UPDATE tbl_trans SET
									trn_end_date = '%s',
									trn_end_date_his = CONCAT('Updated from: ',
															  SYSDATE(),
															  ', Value: ',
															  IFNULL('%s',''),
															  ', By: ',
															  '%s',
															  '<br>',
															  IFNULL(trn_end_date_his,CONCAT( 'Updated from: ',
																							  IFNULL(trn_start_date,''),
																							  ', Value: ',
																							  IFNULL(trn_during,''), 
																							  IFNULL(trn_end_date,''),
																							  ', By: ',
																							  IFNULL(trn_created_by,'')))
															  ),
									
									trn_during = null
								  WHERE trn_id = %s",
							   $valueDate,
							   $valueDate,
							   strtoupper($_SESSION['MM_Username']),
							   $trn_id
							   );
		//echo $updateSQL;
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		
		$updateSQL = sprintf("SELECT case 
							when (a.trn_end_date is not null and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , DATE(a.trn_end_date))
							when (a.trn_end_date is not null and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , DATE(a.trn_end_date))
							when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
							when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
							else '(?)'
						 end as today_duration
						 from tbl_trans a
										
						  WHERE a.trn_id = %s",
					   $trn_id);
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		$fetchData = mysql_fetch_array($Result1,MYSQL_ASSOC);
		echo '@'.$fetchData["today_duration"].'@'; 
	} else {
		$updateSQL = sprintf("UPDATE tbl_trans SET
									trn_during = '%s',
									trn_end_date_his = CONCAT('Updated from: ',
															  SYSDATE(),
															  ', Value: ',
															  IFNULL('%s',''),
															  ', By: ',
															  '%s',
															  '<br>',
															  IFNULL(trn_end_date_his,CONCAT( 'Updated from: ',
																							  IFNULL(trn_start_date,''),
																							  ', Value: ',
																							  IFNULL(trn_during,''), 
																							  IFNULL(trn_end_date,''),
																							  ', By: ',
																							  prg_step1_by))
															  ),
									
									trn_end_date = null
								  WHERE trn_id = %s",
							   $editval,
							   
							   $editval,
							   strtoupper($_SESSION['MM_Username']),
							   $trn_id
							   );
							   
		/*$updateSQL = sprintf("UPDATE tbl_trans
					  SET trn_end_date_his = CONCAT(IFNULL(trn_end_date_his,''),' <br>Updated from: ',SYSDATE(),', Value: ',IFNULL(trn_during,''), IFNULL(trn_end_date,''),', By: ',prg_step1_by),
						  trn_during = %s,
						  trn_end_date = null
					  WHERE trn_id = %s",
					   $editval,
					   $trn_id
					   );*/
					   
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	}
} elseif ($objid == "trn_note_".$trn_id) {
	if ($editval == "") { 
		$valueDate = "null";
	} else {
		$valueDate = "SYSDATE()";
	}

	$updateSQL = sprintf("UPDATE tbl_trans SET
									prg_step5_dt2 = ".$valueDate.", 
									prg_step5_by2 = UCASE('".$_SESSION['MM_Username']."'),
									prg_note = '%s'
								  WHERE trn_id = %s",
								  
							   $editval,
							   $trn_id
							   );
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
}
?><?php mysql_close($db); ?>-->