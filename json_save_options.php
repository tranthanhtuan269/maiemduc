<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
if (!isset($_SESSION)) {
  session_start();
}

require_once('./global.php'); 
$prd =$_REQUEST['prd'];
$tpcode =$_REQUEST['tpcode'];
$tpid =$_REQUEST['tpid'];

	$updateSQL =  sprintf("UPDATE tbl_product_type
					SET tp_checked = 0
				  WHERE tp_class= '%s'
				  and tp_option_code = '%s'",
				  $prd,
				  $tpcode);
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	    
	$updateSQL =  sprintf("UPDATE tbl_product_type
				  SET tp_checked = 1
				  WHERE tp_id= '%s'",
				  $tpid);
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	
	echo '@'.$Result1.'@';
?>
<?php mysql_close($db); ?>
-->