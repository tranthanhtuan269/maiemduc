<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
if (!isset($_SESSION)) {
  session_start();
}

require_once('./global.php'); 
$prd_code =$_REQUEST['prd_code'];
$grp_code =$_REQUEST['grp_code'];
$tp_option_code =$_REQUEST['type'];
$tp_code = pack("H*" , $_REQUEST['opt']);
$editval =$_REQUEST['editval'];
if ($editval =='0') {
	$updateSQL = sprintf("DELETE from tbl_product_mark
							
							where mrk_prd_code = '%s'
							and   mrk_grp_code = '%s'
							and (('".$tp_option_code."' = '' and (mrk_type is null or mrk_type = '')) or mrk_type = '%s')
							and (('".$tp_code."' = '' and (mrk_option is null or mrk_option = '')) or mrk_option = '%s') 
							",
						   $prd_code,
						   $grp_code,
						   $tp_option_code,
						   $tp_code
						   );
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		echo $updateSQL;
} else {
	$updateSQL = sprintf("select count(1) cnt
				  from tbl_product_mark
				  WHERE mrk_prd_code = '%s'
				  and mrk_grp_code = '%s'
				  and (('".$tp_option_code."' = '' and (mrk_type is null or mrk_type = '')) or mrk_type = '%s')
				  and (('".$tp_code."' = '' and (mrk_option is null or mrk_option = '')) or mrk_option = '%s')
				  ",
				  $prd_code,
				  $grp_code,
				  $tp_option_code,
				  $tp_code);
				  
	mysql_select_db($dbhost, $db);
	$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
    $foundUser = mysql_num_rows($Result1);
    $fetchUser = mysql_fetch_array($Result1,MYSQL_ASSOC);
	echo $updateSQL;
	if ($fetchUser["cnt"] > 0) {
		$updateSQL = sprintf("UPDATE tbl_product_mark
							set mrk_point = '%s',
								mrk_created = SYSDATE(),
								mrk_created_by = '%s'
							where mrk_prd_code = '%s'
							and   mrk_grp_code = '%s'
							and (('".$tp_option_code."' = '' and (mrk_type is null or mrk_type = '')) or mrk_type = '%s')
							and (('".$tp_code."' = '' and (mrk_option is null or mrk_option = '')) or mrk_option = '%s') 
							",
							$editval,
							strtoupper($_SESSION['MM_Username']),
						   $prd_code,
						   $grp_code,
						   $tp_option_code,
						   $tp_code
						   );
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		echo $updateSQL;
	} else {
		$updateSQL = sprintf("INSERT INTO tbl_product_mark
							(
								mrk_prd_code,
								mrk_grp_code,
								mrk_type,
								mrk_option,
								mrk_point,
								mrk_created,
								mrk_created_by
							)
							VALUES
							(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							SYSDATE(),
							'%s'
							)",
						   $prd_code,
						   $grp_code,
						   $tp_option_code,
						   $tp_code,
						   $editval,
						   strtoupper($_SESSION['MM_Username'])
						   );
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
		echo $updateSQL;
	}
}
	

?>
<?php mysql_close($db); ?>
-->