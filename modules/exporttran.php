
<?php
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

$width = 98;
?>
<script src="lib/js/Highcharts-4.0.4/js/highcharts.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/highcharts-3d.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/modules/exporting.js"></script>


<style type="text/css">
${demo.css}
</style>
<script type="text/javascript">
       $(function() {
               $("#report_from").datepicker({ dateFormat: "dd-mm-yy" });
	       $("#report_to").datepicker({ dateFormat: "dd-mm-yy" });
       });

   </script>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="4">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

<tr>
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/typeicon2.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=ranklist">Sản phẩm</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/usericon2.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=rankuser">Nhân viên</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/user.png" height=20></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=rankcustf">Khách hàng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/customersicon.png" height=20></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=custlist">DS Khách hàng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/92586_excel_512x512.png" height=20></td>

		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=exporttran">Export &#273;&#417;n h&#224;ng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap" width="80%"></td>
		<td align="center" valign="middle" nowrap="nowrap" width="10%">
		
		</td>
		
	</tr>
	<tr  height="4">
	
		<td width="10%" colspan="4" align="center" height="4"></td>
		
	</tr>
</thead>
</table>


<?php

		$date = date('Y-m-d');

		$timestamp = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");

		

		

		if(isset($_POST['report_from']) && $_POST['report_from'] != '') {

			$report_from = $_POST['report_from'];

		} else {

			$report_from = date('d-m-Y');

		}

		if(isset($_POST['report_to']) && $_POST['report_to'] != '') {

			$report_to = $_POST['report_to'];

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

//echo $report_from;  
echo '<!--';
$export_excel_trans=$mysqlIns->export_excel_trans($report_from,$report_to);
echo '-->';
?><?php

		$date = date('Y-m-d');

		$timestamp = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");

		

		

		if(isset($_POST['report_from']) && $_POST['report_from'] != '') {

			$report_from = $_POST['report_from'];

		} else {

			$report_from = date('d-m-Y');

		}

		if(isset($_POST['report_to']) && $_POST['report_to'] != '') {

			$report_to = $_POST['report_to'];

		} else {

			$report_to = date('d-m-Y');

		}

		

		?>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="20">
		<td width="10%" colspan="4" align="center" height="2">
			<table>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>
				
					<b>Từ </b>
					<input maxlength="10" onkeypress="return onlydate(event);" name="report_from" type="text" id="report_from" size="7" style="border:1px solid #DADADA;" value="<?php echo $report_from;?>" />

					<b>Đến </b>
					<input maxlength="10" onkeypress="return onlydate(event);" name="report_to" type="text" id="report_to" size="7" style="border:1px solid #DADADA;" value="<?php echo $report_to; ?>" />
				</td>
				<td>
					<a href="javascript:" onclick="callajax_export();"><img src="images/export.gif" height=25></a>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</thead>
</table>

<form name="hid_post" id="hid_post" method="POST">
	<input type="hidden" id="hid_report_month" name="hid_report_month" value="">
</form>

<script type="text/javascript">
	function callajax_export() {
		var param = "export_excel_trans.php?report_from=" + $('#report_from').val() + "&report_to=" + $('#report_to').val();
		//alert(param);
		/*$.ajax({
				url : 'export_excel.php',
				data :  param,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					
						// Gán kết quả vào div#content
						//alert(result);
					
				}
			});*/
			window.open(param);
	}
</script>
