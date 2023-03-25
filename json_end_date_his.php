<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
require_once('./global.php'); 
require("src/mysql_function.php");
$mysqlIns=new mysql(); $mysqlIns->link=$db;

$id =$_REQUEST['id'];

$index_chuaxuly=$mysqlIns->get_end_date_his($id);
?>-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link type="text/css" rel="stylesheet" href="lib/css/niceforms-default.css" />

<style>
	body {
	font-size:	7px;
	font-family: "Lucida Grande","Lucida Sans Unicode", Tahoma, Sans-Serif;
	
	}
</style>
</head>
<body bgcolor="#eeedfb">
<table bgcolor="#eeedfb" width="600" cellspacing="10" cellpadding="10" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<tr bgcolor="#eeedfb"><td>
<b><font size=2>Lịch sử hẹn trả hàng: </font><font color="red" size=2><?php echo $index_chuaxuly[0]["trn_name"]; ?></font></b><br><br>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" bgcolor="#eeedfb">

<thead>
<tr bgcolor="#eeedfb">
	<td style="padding-left:6px; border-left: 1px solid #c2c2c2; border-collapse:none;" align="left" nowrap="nowrap"><b><font size=2>Ngày Update</font></b></td>
	<td style="padding-left:6px; border-left: 1px solid #c2c2c2; border-collapse:none;" align="left" nowrap="nowrap"><b><font size=2>Giá trị</font></b></td>
	<td style="padding-left:6px; border-left: 1px solid #c2c2c2; border-collapse:none;" align="left" nowrap="nowrap"><b><font size=2>Người Update</font></b></td>
</tr>
</thead>
<tbody>
<?php
for($i=0;$i<count($index_chuaxuly);$i++)
{
	$trn_end_date_part_item_arr = explode("<br>",$index_chuaxuly[$i]["trn_end_date_his"]);
	for ($j = 0; $j < count($trn_end_date_part_item_arr); $j ++) {
		echo '</tr><tr height="1" bgcolor="gray">
		<td width="100%" colspan="6" align="left" valign="middle">
		
		</td>';
		echo '<tr bgcolor="#ffffff">';
		$trn_end_date_part_item_part = explode(", ",$trn_end_date_part_item_arr[$j]);
		for ($k = 0; $k < count($trn_end_date_part_item_part); $k ++) {
			$trn_end_date_part_item_part_key = explode(": ",$trn_end_date_part_item_part[$k]);
			//echo '<td style="border-left: 1px solid #c2c2c2; border-collapse:none;" style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b>'.$trn_end_date_part_item_part_key[0].'</b></td>';
			echo '<td style="padding-left:6px; border-left: 1px solid #c2c2c2; border-collapse:none;" style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><font size=2>'.$trn_end_date_part_item_part_key[1].'</font></td>';
		}
		
		
	}

}
?>
</tbody>
</table>
</td>
</tr>
</table>
</body>
</html>
<script type="text/javascript"> </script>
<?php mysql_close($db); ?>