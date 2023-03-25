<?php 
header('Content-type: text/html; charset=utf-8'); 
if (!isset($_SESSION)) {
  session_start();
}

require_once('./global.php'); 
$trn_id =$_REQUEST['trn_id'];
$editval =$_REQUEST['editval'];

if ($editval !="" && $editval !="0" && $trn_id != "") {
	$updateSQL = sprintf("INSERT INTO tbl_payment_his(
									payment_id,
									payment_status,
									trn_id,
									trn_ref,
									trn_payment,
									trn_auth_by,
									trn_auth_date) 
									VALUES (
									(select max(t.payment_id) + 1 from tbl_payment_his t),
									'1',
									'%s',
									(select t.trn_ref from tbl_trans t where t.trn_id = '$trn_id'),
									'%s',
									UCASE('%s'),
									SYSDATE())",
							   $trn_id,
							   str_replace(',','',$editval),
							   $_SESSION['MM_Username']
							   );
	echo $updateSQL;
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
}
?><?php mysql_close($db); ?>