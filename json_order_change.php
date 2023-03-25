<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
if (!isset($_SESSION)) {
  session_start();
}

require_once('./global.php'); 
$type =$_REQUEST['type'];
$keyid =$_REQUEST['keyid'];
$editval =$_REQUEST['editval'];

if ($type == 'prd') {
	$updateSQL = sprintf("UPDATE tbl_product set prd_order = '%s'
				  WHERE prd_id = '%s'",
				  $editval,
				  $keyid);
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
} elseif ($type == 'opt') {
	$updateSQL = sprintf("UPDATE tbl_product_type set tp_option_order = '%s'
				  WHERE tp_id = '%s'",
				  $editval,
				  $keyid);
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
} elseif ($type == 'tp') {
	$updateSQL = sprintf("UPDATE tbl_product_type set tp_order = '%s'
				  WHERE tp_id = '%s'",
				  $editval,
				  $keyid);
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
}
?>
<?php mysql_close($db); ?>
-->