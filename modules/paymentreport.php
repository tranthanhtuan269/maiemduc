

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




if (!isset($_POST['report_date_from']) && $_SESSION['report_date_from'] == null) {



		$day = '';



		$_SESSION['report_date_from'] = $day;

} else {



	$day = $_POST['report_date_from'];



	$_SESSION['report_date_from'] = $day;

}





if (!isset($_POST['report_date_to'])) {



		$day = trim(date('d-m-Y')).'';



		$_SESSION['report_date_to'] = $day;

} else {



	$day = $_POST['report_date_to'];



	$_SESSION['report_date_to'] = $day;

}




$width = 98;


?>


<script src="lib/js/Highcharts-4.0.4/js/highcharts.js"></script>


<script src="lib/js/Highcharts-4.0.4/js/highcharts-3d.js"></script>


<script src="lib/js/Highcharts-4.0.4/js/modules/exporting.js"></script>



<script language="javascript">



$(function() {



               $("#report_date_from").datepicker({ dateFormat: "dd-mm-yy" })

			   $("#report_date_to").datepicker({ dateFormat: "dd-mm-yy" })



       });



</script>




<style type="text/css">





</style>




<form id="filterDate" name="filterDate" method="POST">

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >





	


<tr height="10">


	


		<td width="1%" colspan="4" align="left" height="2" style="padding-left:5px;padding-right:5px;padding-bottom:5px;">


		<img src="images/payment.png" height=25>


		</td>


		


		<td width="100%" colspan="4" align="left" height="2">


		<font size="2"><b>Thống kê công nợ 

		<?=isset($_SESSION['report_date_from'])?'t&#7915; '.$_SESSION['report_date_from']:''?> 

		<?=isset($_SESSION['report_date_from']) && isset($_SESSION['report_date_t&#243;'])?'-':''?>

		<?=isset($_SESSION['report_date_to'])?'&#273;&#7871;n '.$_SESSION['report_date_to']:''?></b> </font>


		</td>

		<td nowrap="nowrap">

			<b>Từ ngày nh&#7853;p &#273;&#417;n&nbsp;</b> <input type="text" id="report_date_from" maxlength=10 size=10 name="report_date_from" value="<?php if (isset($_SESSION['report_date_from'])) { echo $_SESSION['report_date_from'];} else {echo '';}?>">

			&nbsp;&nbsp;&nbsp;

			<b>Đến ngày nh&#7853;p &#273;&#417;n&nbsp;</b> <input type="text" id="report_date_to" maxlength=10 size=10 name="report_date_to" value="<?php if (isset($_SESSION['report_date_to'])) { echo $_SESSION['report_date_to'];} else {echo '';}?>">

			&nbsp;&nbsp;

			<input type="submit" name="btnreport_date" id="btnreport_date" value="Xem" onclick="makeBlockUI();"/>

		</td>


		


	</tr>


</table>


</form>


		

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >


<thead>





	<tr  height="4" >


		<td width="30%" colspan="4" align="center" height="4" valign="top">


				<table width="100%" cellspacing="0" cellpadding="0" style="padding-top:10px;padding-left:5px">

							<tr>

								<td width="100%" valign="top">

								

								<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">



<thead>







<tr height="25" bgcolor="#eeedfb" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">



	<td nowrap="nowrap" align="left" style="padding-left:10px;padding-right:10px;border-bottom: 1px solid #c2c2c2;" width="1%"><b>Th&#225;ng (Theo ng&#224;y nh&#7853;p &#273;&#417;n)</b></td>



	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:10px;border-left: 1px solid #c2c2c2;border-bottom: 1px solid #c2c2c2; border-collapse:none;" width="40%"><b>Doanh s&#7889;</b></td>



	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:10px;border-left: 1px solid #c2c2c2;border-bottom: 1px solid #c2c2c2; border-collapse:none;" width="30%"><b>S&#7889; ti&#7873;n Kh&#225;ch h&#224;ng c&#242;n n&#7907;</b></td>



	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:10px;border-left: 1px solid #c2c2c2;border-bottom: 1px solid #c2c2c2; border-collapse:none;" width="30%"><b>S&#7889; ti&#7873;n Kh&#225;ch h&#224;ng c&#242;n n&#7907; l&#361;y k&#7871;</b></td>





</tr>













										

										

										<?php //echo 'AAAAA'.$cust_phone;

											$get_cust_debit=$mysqlIns->get_cust_debit_by_month();

											$totaldebit = 0;

											for($i=0;$i<count($get_cust_debit);$i++)

											{

												$totaldebit = $totaldebit + $get_cust_debit[$i]['trn_payment_remain'];

											?>

											<tr bgcolor="#fff" height="25" style="padding-top:10px;">

												

												<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;	 border-bottom: 1px solid #c2c2c2;border-collapse:none;" width="1%">&nbsp;<b><?=$get_cust_debit[$i]['trn_start_date_order']?></b>&nbsp;</td>

												<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-bottom: 1px solid #c2c2c2;border-collapse:none;border-left: 1px solid #c2c2c2; border-collapse:none;" width="40%">&nbsp;<b><?=number_format($get_cust_debit[$i]['total_amount'], 0, '.', ',')?></b>&nbsp;</td>

												<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-bottom: 1px solid #c2c2c2;border-collapse:none;border-left: 1px solid #c2c2c2; border-collapse:none;" width="30%">&nbsp;<b><?=number_format($get_cust_debit[$i]['trn_payment_remain'], 0, '.', ',')?></b>&nbsp;</td>

												<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-bottom: 1px solid #c2c2c2;border-collapse:none;border-left: 1px solid #c2c2c2; border-collapse:none;" width="30%">&nbsp;<b><?=number_format($totaldebit, 0, '.', ',')?></b>&nbsp;</td>

												

											</tr>

											<?php

											}

										?>

										

										<tr bgcolor="#fafae0" height="30" color="yellow">

											<td colspan="4" nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;border-collapse:none;" width="1%">&nbsp;<b><font size="3">T&#7893;ng c&#242;n n&#7907;</b>:&nbsp;</font>

											&nbsp;<b><font size="3"><?=number_format($totaldebit, 0, '.', ',')?></b>&nbsp;</font>

											</td>

										</tr>

									</table>

								</td>

								

								<td style="padding-left:10px" valign="top">

								<table width="100%" cellspacing="0" cellpadding="0" >

									

										<tr height="25">

											<td colspan="7" bgcolor="#eeedfb" style="padding-left:9px;padding-right:6px;border-left: 1px solid #c2c2c2; border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;"><b>Lịch sử thanh toán (Hiển thị tối đa 500 lệnh mới nhất)</b></td>

										</tr>

										<tr height="25">

											

											<td align="left" bgcolor="#eeedfb" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Khách hàng</b>&nbsp;</td>

											<td align="left" bgcolor="#eeedfb" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Ngày thanh toán</b>&nbsp;</td>

											<td align="left" bgcolor="#eeedfb" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>User nhập</b>&nbsp;</td>

											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Tiền mặt</b>&nbsp;</td>

											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Chuyển khoản</b>&nbsp;</td>

											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Đơn hàng</b>&nbsp;</td>

											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Thừa</b>&nbsp;</td>

										</tr>

									

										<?php //echo 'AAAAA'.$cust_phone;

											$get_payment_pack=$mysqlIns->select_tbl_payment_pack("",$_SESSION['report_date_from'],$_SESSION['report_date_to']);

											for($i=0;$i<count($get_payment_pack);$i++)

											{

											

												$pack_trn_ids_f_template = "<a href='?mode=viewdetail&id={{0}}'><b>{{0}}</b></a>";

												$splitarr = explode("@",$get_payment_pack[$i]['pack_trn_ids']);

												$pack_trn_ids_f = "";

												for($j=0;$j<count($splitarr);$j++)

												{

													if ($splitarr[$j]!='') {

														if ($j < count($splitarr) - 1) {

															$pack_trn_ids_f = $pack_trn_ids_f.str_replace("{{0}}",str_replace(' ','',$splitarr[$j]),$pack_trn_ids_f_template)."<br>";

														} else {

															$pack_trn_ids_f = $pack_trn_ids_f.str_replace("{{0}}",str_replace(' ','',$splitarr[$j]),$pack_trn_ids_f_template);

														}

													}

												}

											

											?>

											<tr  height="25">

												

												<td align="left"  valign="top" bgcolor="#fff" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><a href="?search=user&id=<?=$get_payment_pack[$i]['pack_cust_phone']?>"><img src="images/user.png"><b><?=$get_payment_pack[$i]['cust_name']?></b></a></td>

												<td align="center"  valign="top" bgcolor="#fff" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><?=$get_payment_pack[$i]['pack_date']?></td>

												<td align="left"  valign="top" bgcolor="#fff" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><a href="?search=staff&id=<?=$get_payment_pack[$i]['pack_user']?>"><b><?=$get_payment_pack[$i]['pack_user']?></b></a></td>

												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><?=number_format($get_payment_pack[$i]['pack_payment_tm'], 0, '.', ',')?></td>

												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><?=number_format($get_payment_pack[$i]['pack_payment_ck'], 0, '.', ',')?></td>

												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><?=$pack_trn_ids_f?></td>

												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;"><?=number_format($get_payment_pack[$i]['pack_over'], 0, '.', ',')?></td>

											</tr>

											<?php

											}

										?>

										

									

									</table>


								</td>


							</tr>


						</table>







</td>




		


	</tr>


</thead>


</table>


