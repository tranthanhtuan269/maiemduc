<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
require_once('./global.php'); 
require("src/mysql_function.php");
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_REQUEST['cust_name'])) $_REQUEST['cust_name'] = "";
if (!isset($_REQUEST['cust_company'])) $_REQUEST['cust_company'] = "";
if (!isset($_REQUEST['cust_email'])) $_REQUEST['cust_email'] = "";
if (!isset($_REQUEST['cust_phone'])) $_REQUEST['cust_phone'] = "";
if (!isset($_REQUEST['ignoreid'])) $_REQUEST['ignoreid'] = "";
if (!isset($_REQUEST['trn_ref'])) $_REQUEST['trn_ref'] = "";
if (!isset($_REQUEST['trn_name'])) $_REQUEST['trn_name'] = "";
if (!isset($_REQUEST['trn_id'])) $_REQUEST['trn_id'] = "";
if (!isset($_REQUEST['action_code'])) $_REQUEST['action_code'] = "";
if (!isset($_REQUEST['search_code'])) $_REQUEST['search_code'] = "";
if (!isset($_REQUEST['search_code'])) $_REQUEST['search_code'] = "";
$disabled="";
$selectDisabledStyle = "";


function utf8convert($str) {
	if(!$str) return false;
	$utf8 = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd'=>'đ|Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			);
	foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
return $str;
}

$mysqlIns=new mysql(); $mysqlIns->link=$db;
$maxref = $mysqlIns->get_ref_max();

$cust_name =strtoupper(utf8convert($_REQUEST['cust_name']));
$cust_company =strtoupper(utf8convert($_REQUEST['cust_company']));
$cust_email =$_REQUEST['cust_email'];
$cust_phone =$_REQUEST['cust_phone'];
$ignoreid =$_REQUEST['ignoreid'];
$trn_ref =$_REQUEST['trn_ref'];
$trn_name =strtoupper(utf8convert($_REQUEST['trn_name']));
$trn_id =$_REQUEST['trn_id'];
$action_code =$_REQUEST['action_code'];
$search_code  = $_REQUEST['search_code'];


$index_chuaxuly=$mysqlIns->search_autocomplete(
$ignoreid,
$cust_name,
$cust_company,
$cust_email,
$cust_phone,
$trn_ref,
$trn_name,
$search_code
);
$width = "97.9";
?>-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to Administrator system</title>
	
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #c2c2c2; border-collapse:none;" >
<thead>

<?php 


$isexistid = false;
$total_amount = 0;
$total_pay = 0;
if (count($index_chuaxuly) > 0) { ?>
<tr bgcolor="#ffffff">
	<td align="left" >&nbsp;<b>STT</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7889; H&#272;</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b></b></td>
	<td align="left" style="padding-left:6px;" colspan="1" nowrap="nowrap"><b>&#272;&#417;n h&#224;ng&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b></b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>T&#234;n KH</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7843;n ph&#7849;m</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7889; l&#432;&#7907;ng</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Y&#234;u c&#7847;u</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>VAT</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Giao h&#224;ng</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ng&#224;y k&#253; H&#272;</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ng&#224;y h&#7865;n</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>&#272;&#227; tr&#7843; h&#224;ng</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Kinh doanh</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Tr&#7841;ng th&#225;i</b></td>
</tr>
<tr bgcolor="#bdb9b9" height="1">
	<td align="left" colspan="16"></td>
	
	
	
</tr>
</thead>
<tbody>

<?php
//echo 'sadasdasd'.$trn_id;
$total_pay = $index_chuaxuly[0]["trn_payment"];
for($i=0;$i<count($index_chuaxuly);$i++)
{
if ($total_pay < $index_chuaxuly[$i]["trn_payment"]) {
	$total_pay = $index_chuaxuly[$i]["trn_payment"];
}
$total_amount = $total_amount + $index_chuaxuly[$i]["trn_amount"];
$trn_id_first = $index_chuaxuly[0]["trn_id"];

if ($trn_id == $index_chuaxuly[$i]["trn_id"]) {
	$isexistid = true;
}

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$persentage = "10";
$status = "Ch&#7901; thi&#7871;t k&#7871;";
$saler = "ManhPD";
$pending = "GiangTT";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";

if ($index_chuaxuly[$i]["prg_status"] == "42")
{
	$color = "#a8d2fa"; // xanh
} else if ($index_chuaxuly[$i]["trn_end_date"] != "") {
	$color = "#ff7373"; // do
} else {
	if ($_SESSION['step'] == "DESIGN") {
		if ($index_chuaxuly[$i]["prg_status"] == "21") $color = "#faf9a8"; // vang
		if ($index_chuaxuly[$i]["prg_status"] == "22") $color = "#faf9a8"; // vang
		if ($index_chuaxuly[$i]["prg_status"] == "23") $color = "#a8d2fa"; // xanh
	}
	if ($_SESSION['step'] == "BUILD") {
		if ($index_chuaxuly[$i]["prg_status"] == "31") $color = "#faf9a8"; // vang
		if ($index_chuaxuly[$i]["prg_status"] == "32") $color = "#a8d2fa"; // xanh
	}
	if ($_SESSION['step'] == "DELIVER") {
		if ($index_chuaxuly[$i]["prg_status"] == "41") $color = "#faf9a8"; // vang
		if ($index_chuaxuly[$i]["prg_status"] == "42") $color = "#a8d2fa"; // xanh
	}
}

$time_format=$mysqlIns->FormatDateTime_index($index_chuaxuly[$i][1]);
$stt = $i+ 1;

$option = "";
if ($index_chuaxuly[$i]["trn_prd_code"]== null || $index_chuaxuly[$i]["trn_prd_code"]=="") {
	$trn_prd_code = 'CARD_VISIT';
} else {
	$trn_prd_code = $index_chuaxuly[$i]["trn_prd_code"];
}

echo "<!--";
$tbl_product_option=$mysqlIns->select_tbl_product_option($trn_prd_code);
echo "-->";
for($k=0;$k<count($tbl_product_option);$k++)
{
	//giấy
	//Cán
	//bế
	//xén
	//ghim
	//số màu
	//số liên
	echo "<!--";
	$tbl_product_type=$mysqlIns->select_tbl_product_option_list($trn_prd_code,$tbl_product_option[$k]['tp_option_code']);
	echo "-->";
	if (count($tbl_product_type) > 0) {
			$option = $option.'<b>&nbsp;&nbsp;&nbsp;'.$tbl_product_option[$k]['tp_option'].'&nbsp;</b>
			<select '.$disabled.$selectDisabledStyle.' name="trn_'.$tbl_product_option[$k]['tp_option_code'].'" id="trn_'.$tbl_product_option[$k]['tp_option_code'].'" style="border:1px solid #DADADA;">';
			
			// 
			$option_val = "";
			$option_val_arr = explode("@",$index_chuaxuly[$i]["trn_option"]);
			for($l=0;$l<count($option_val_arr)-1;$l++)
			{	
				$option_val_arr_key = explode("=",$option_val_arr[$l]);
				if ($option_val_arr_key[0] == $tbl_product_option[$k]['tp_option_code']) {
					$option_val = $option_val_arr_key[1];
				}
			}
			
			for($j=0;$j<count($tbl_product_type);$j++)
			{
				$selected = '';
				if ($option_val == $tbl_product_type[$j]['tp_code']) {
					$selected = 'selected';
				}
				$option = $option.'<option value="'.$tbl_product_type[$j]['tp_code'].'" '.$selected.'>'.$tbl_product_type[$j]['tp_name'].'</option>';
			}
			
			$option = $option.'</select>';
	}
}

$author = strtoupper($index_chuaxuly[$i]["prg_step1_by"]);

echo '
<div style="visibility: hidden; display: block;
line-height:0;
height: 0;
overflow: hidden;" id="option_'.$index_chuaxuly[$i]["trn_id"].'" heigh="1">'.$option.'</div>';

echo '
<input type="hidden" id="trn_id_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_id"].'">
<input type="hidden" id="cust_name_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["cust_name"].'">
<input type="hidden" id="cust_company_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["cust_company"].'">
<input type="hidden" id="cust_email_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["cust_email"].'">
<input type="hidden" id="cust_phone_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["cust_phone"].'">

<input type="hidden" id="trn_ref_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_ref"].'">
<input type="hidden" id="trn_name_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_name"].'">
<input type="hidden" id="trn_has_file_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_has_file"].'">


<input type="hidden" id="trn_prd_code_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_prd_code"].'">
<input type="hidden" id="trn_prd_type_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_prd_type"].'">

<input type="hidden" id="trn_quantity_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_quantity"].'">
<input type="hidden" id="trn_unit_price_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_unit_price"].'">
<input type="hidden" id="trn_vat_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_vat"].'">
<input type="hidden" id="trn_payment_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_payment_all"].'">
<input type="hidden" id="trn_payment_auth_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_payment_auth"].'">
<input type="hidden" id="trn_detail_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_detail"].'">

<input type="hidden" id="trn_amount_all_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_amount_all"].'">
<input type="hidden" id="trn_amount_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_amount"].'">
<input type="hidden" id="trn_amount_withoutVAT_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_amount_withoutVAT"].'">
<input type="hidden" id="trn_payment_remain_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_payment_remain"].'">


<input type="hidden" id="trn_type_code_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_type_code"].'">
<input type="hidden" id="trn_during_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_during"].'">
<input type="hidden" id="trn_end_date_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_end_date_f"].'">
<input type="hidden" id="trn_start_date_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_start_date_f"].'">
<input type="hidden" id="trn_deliver_type_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_deliver_type"].'">
<input type="hidden" id="trn_deliver_address_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_deliver_address"].'">

<input type="hidden" id="prg_step1_by_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step1_by"].'">
<input type="hidden" id="prg_step2_by_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step2_by"].'">
<input type="hidden" id="prg_step2_dt1_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step2_dt1"].'">
<input type="hidden" id="prg_step2_dt2_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step2_dt2"].'">
<input type="hidden" id="prg_step2_dt3_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step2_dt3"].'">

<input type="hidden" id="prg_step3_by_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step3_by"].'">
<input type="hidden" id="prg_step3_dt1_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step3_dt1"].'">
<input type="hidden" id="prg_step3_dt2_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step3_dt2"].'">
<input type="hidden" id="prg_issue_date_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_issue_dt"].'">

<input type="hidden" id="prg_issue_value_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_issue_value"].'">
<input type="hidden" id="prg_issue_from_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_issue_from"].'">

<input type="hidden" id="prg_step4_by_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step4_by"].'">
<input type="hidden" id="prg_step4_dt1_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step4_dt1"].'">
<input type="hidden" id="prg_step4_dt2_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_step4_dt2"].'">

<input type="hidden" id="prg_note_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_note"].'">

<input type="hidden" id="trn_img_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_img"].'">
<input type="hidden" id="prg_status_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["prg_status"].'">

<tr height="22" bgcolor="'.$color.'">
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$stt.'</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<a onclick="javascript: addOrder(\''.$index_chuaxuly[$i]["trn_id"].'\');" href="javascript:" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><b><img src="images/add1.png" height="14"> '.$index_chuaxuly[$i]["trn_ref"].'</b></a></td>
		<td align="left" style="padding-left:6px;" nowrap="nowrap"><b></b></td>
		<td style=\"padding-left:6px;\"><img src="images/viewinfo.png" height="14"><b><a onclick="javascript: loadAvailbleOrder(\''.$index_chuaxuly[$i]["trn_id"].'\');" href="javascript:" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" >'.$index_chuaxuly[$i]["trn_name"].'<!--yeucau--></a></b></td>
		<td align="left" style="padding-left:6px;" nowrap="nowrap"><b></b></td>
		<td align="left" style=\"padding-left:6px;\"><b>
		<a onclick="javascript: loadAvailbleCust(\''.$index_chuaxuly[$i]["trn_id"].'\');" href="javascript:" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/user.png">'.$index_chuaxuly[$i]["cust_name"].'</a></b></td>
		<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prd_name"].'<!--tencongtrinh--></a></td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_quantity"].'</td>
		<td style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_detail"].'</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_vat"].'%&nbsp;&nbsp;</td>
		<td style=\"padding-left:6px;\">'.$index_chuaxuly[$i]["trn_deliver_address"].'</td>
		
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_start_date_f"].'</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_end_date_f"].'</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_step4_dt2_f"].'</td>
		
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><img src="'.$iconsaler.'" width="16">	'.$index_chuaxuly[$i]["prg_step1_by"].'</b></td>
	
	<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_status_f"].'&nbsp;&nbsp;</td>	
		
	</tr>';
	
	echo '
	<input type="hidden" id="trn_total_pay_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$total_pay.'">
	<input type="hidden" id="trn_total_amount_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$total_amount.'">
	<input type="hidden" id="trn_author_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$author.'">
	';
	}

	if (!$isexistid) {
		$trn_id = $trn_id_first;
	}

} else {
?>

			<tr bgcolor="#ffffff" height="1">
			<td align="center" colspan="16">Ch&#432;a t&#7891;n h&#243;a &#273;&#417;n </td>
			</tr>
			

<?php } ?>

<tr bgcolor="#ffffff" height="1" id="rowaction">
			<td align="center" colspan="15"><font color="red" size="3"><u>Ch&#250; &#253;</u>: B&#7841;n &#273;ang thao t&#225;c </font><font color="blue" size="3"><b><span id='action_note'>Ch&#432;a x&#225;c &#273;&#7883;nh</span> </b></td>
			<td align="center" colspan="1" nowrap="nowrap" bgcolor="yellow">&nbsp;&nbsp;<a onclick="clearAvailbleOrder();" href=javascript:"><b>X&#243;a form d&#432;&#7899;i</b></u></a>&nbsp;&nbsp;</td>
			</tr>
<tr><td colspan=15 width="100%">
	<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>
<tr height="5" >

		<td width="40%" colspan="6" align="center" valign="middle"></td>
		
	</tr>
</thead>
</table>
</td></tr>
</tbody>
</table>
</body>
</html>

<script type="text/javascript">

		//alert($('#prg_step1_by_'+$('#trn_id').val()).val().toUpperCase())
			//$( document ).ready(function() {
			if ($('#trn_disabled').val() == 'disabled' ) {
				//document.getElementById("rowaction").style.display = 'none' 
				document.getElementById("action_button").style.display = 'none' ;
				<?php if ($stt == 0) { ?>
				$('#action_note').html('<img src="images/Handshake.png" height="14"> T&#7841;o h&#243;a &#273;&#417;n m&#7899;i (Số hóa đơn <font color=red><?=$maxref[0]['maxref'] + 1?></font>)');
				//clearMakeNewOrder();
				<?php } else { ?>
				$('#action_note').html('<img src="images/Handshake.png" height="14"> Xem hóa đơn cũ');
				<?php } ?>
				//alert($('#trn_total_amount_text').html());
				//calTotal();
				//calTotalAmount();
				//$('#trn_id').val('');
				
			} else {
				document.getElementById("rowaction").style.display = ''
				<?php if ($search_code == 2) { ?>
				$('#trn_id').val('');
				$('#action_code').val('0');
				$('#action_note').html('<img src="images/Handshake.png" height="14"> T&#7841;o h&#243;a &#273;&#417;n m&#7899;i (Số hóa đơn <font color=red><?=$maxref[0]['maxref'] + 1?></font>)');
				$('#trn_img_show').html('');
				$('#prg_status').val('12');
				enabledOrder()
				<?php } else { ?>
					<?php if (count($index_chuaxuly) > 0) { ?>
						
						if ($('#action_code').val() =='1') {
							$('#action_note').html('<img src="images/viewinfo.png" height="14"> Update h&#243;a &#273;&#417;n c&#361; ' + $('#trn_ref').val() + ' (ID: ' + $('#trn_id').val() + ')');
						} else {
							$('#action_code').val('2');
							$('#trn_img_show').html('');
							$('#prg_status').val('12');
							$('#action_note').html('<img src="images/add1.png" height="14"> T&#7841;o th&#234;m s&#7843;n ph&#7849;m trong c&#249;ng h&#243;a &#273;&#417;n ' + $('#trn_ref').val());
						}
					<?php } else { ?>
						$('#trn_id').val('');
						$('#action_code').val('0');
						$('#action_note').html('<img src="images/Handshake.png" height="14"> T&#7841;o h&#243;a &#273;&#417;n m&#7899;i (Số hóa đơn <font color=red><?=$maxref[0]['maxref'] + 1?></font>)');
						$('#trn_img_show').html('');
						$('#prg_status').val('12');
						enabledOrder()
					<?php } ?>	
				<?php } ?>
			}
			
			$('#trn_payment').val($('#trn_payment_'+$('#trn_id').val()).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#trn_total_amount_text').html($('#trn_amount_all_'+$('#trn_id').val()).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

			//calTotalAmount();
			//alert('<?php echo strtoupper($author); ?>');
			<?php if ($author == "" || $_SESSION['MM_Isadmin'] == 1) { ?>
				enabledOrder();
			<?php } elseif (strtoupper($_SESSION['MM_Username']) == strtoupper($author)) { ?>
				//alert($('#prg_status_'+$('#trn_id').val()).val())
				if ($('#prg_status_'+$('#trn_id').val()).val() < 42) {
					enabledOrder();
				} else {
					disabledOrder();
					$('#action_note').html('Xem Đơn hàng đã hoàn thành');
				}
			<?php } else { ?>
				disabledOrder();
			<?php } ?>
			//});
			//calTotalAmount();
			if ($('#trn_payment_auth').html() == "") {
				$('#trn_payment_auth').html('0')
			} 
			
		function disabledOrder()
		{
			$('input[name=trn_has_file]').attr('disabled','disabled');
			
			$('#trn_ref').prop('disabled',false);
			$('#trn_name').prop('disabled',false);
			
			$('#trn_prd_code').attr('disabled','disabled');
			$('#trn_prd_code').attr('style','background-color: #ebebe4;');
			
			//$('#option').html($('#option_' +id).html());
			
			$('#trn_quantity').attr('disabled','disabled');
			$('#trn_unit_price').attr('disabled','disabled');
			
			$('#trn_vat_v10').attr('disabled','disabled');
			$('#trn_vat_v0').attr('disabled','disabled');
			
			$('#trn_payment').attr('disabled','disabled');
			$('#trn_detail').attr('disabled','disabled');
			
			$('#trn_type_code_v1').attr('disabled','disabled');
			$('#trn_type_code_v0').attr('disabled','disabled');
			
			$('#trn_during').attr('disabled','disabled');
			$('#trn_end_date').attr('disabled','disabled');
			$('#trn_start_date').attr('disabled','disabled');
			
			$('#trn_deliver_type_v1').attr('disabled','disabled');
			$('#trn_deliver_type_v0').attr('disabled','disabled');
			
			$('#trn_deliver_address').attr('disabled','disabled');
			$('#prg_step2_by').attr('disabled','disabled');
			$('#design_user_value').html('');
			$('#prg_step2_by').attr('style','background-color: #ebebe4;');

			$('#prg_step3_by').attr('disabled','disabled');
			$('#build_user_value').html('');
			$('#prg_step3_by').attr('style','background-color: #ebebe4;');
			$('#prg_issue_value').attr('disabled','disabled');
			$('#prg_issue_from').attr('disabled','disabled');
			$('#prg_issue_from').attr('style','background-color: #ebebe4;');
			
			$('#prg_step4_by').attr('disabled','disabled');
			$('#deliver_user_value').html('');
			$('#prg_step4_by').attr('style','background-color: #ebebe4;');
			
			$('#prg_note').attr('disabled','disabled');

			$('#trn_amount').attr('disabled','disabled');
			$('#trn_amount_withoutVAT').attr('disabled','disabled');
			$('#prg_status').attr('disabled','disabled');

			document.getElementById("action_button").style.display = 'none' ;
			$('#action_note').html('Xem đơn hàng của người khác');
		}
		
		function enabledOrder()
		{
			$('input[name=trn_has_file]').prop('disabled',false);
			
			$('#trn_ref').prop('disabled',false);
			$('#trn_name').prop('disabled',false);
			
			
			$('#trn_prd_code').prop('disabled',false);
			$('#trn_prd_code').attr('style','');
			
			//$('#option').html($('#option_' +id).html());
			
			$('#trn_quantity').prop('disabled',false);
			$('#trn_unit_price').prop('disabled',false);
			
			$('#trn_vat_v10').prop('disabled',false);
			$('#trn_vat_v0').prop('disabled',false);
			
			$('#trn_payment').prop('disabled',false);
			$('#trn_detail').prop('disabled',false);
			
			$('#trn_type_code_v1').prop('disabled',false);
			$('#trn_type_code_v0').prop('disabled',false);
			
			$('#trn_during').prop('disabled',false);
			$('#trn_end_date').prop('disabled',false);
			$('#trn_start_date').prop('disabled',false);
			
			$('#trn_deliver_type_v1').prop('disabled',false);
			$('#trn_deliver_type_v0').prop('disabled',false);
			
			$('#trn_deliver_address').prop('disabled',false);
			$('#prg_step2_by').prop('disabled',false);
			$('#prg_step2_by').attr('style','');

			$('#prg_step3_by').prop('disabled',false);
			$('#prg_step3_by').attr('style','');
			$('#prg_issue_value').prop('disabled',false);
			
			$('#prg_issue_from').prop('disabled',false);
			$('#prg_issue_from').attr('style','');
			
			$('#prg_step4_by').prop('disabled',false);
			$('#prg_step4_by').attr('style','');
			
			$('#prg_note').prop('disabled',false);

			$('#trn_amount').prop('disabled',false);
			$('#trn_amount_withoutVAT').prop('disabled',false);
			$('#prg_status').prop('disabled',false);

			document.getElementById("action_button").style.display = '' ;
		}
		
		function clearMakeNewOrder()
		{
			//$('#trn_total_amount').val('0');
			$('#trn_total_pay_'+$('#trn_id').val()).html('0');
			$('#trn_payment_auth_'+$('#trn_id').val()).html('0');
			$('#trn_total_amount_text').html('0');
			$('#trn_payment_remain').val('0');
			$('#trn_img_show').html('');
			//enabledOrder();
		}
		
		function loadAvailbleOrder(id)
		{
			
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
			//alert($('#trn_has_file_' +id).val());
			$('#trn_prd_code').val($('#trn_prd_code_' +id).val());
			$('#trn_prd_type').val($('#trn_prd_type_' +id).val());
			
			$('#option').html($('#option_' +id).html());
			
			$('#trn_quantity').val($('#trn_quantity_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#trn_unit_price').val($('#trn_unit_price_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			
			
			
			if ($('#trn_vat_' +id).val() == 10) {
				$('#trn_vat_v10').prop('checked',true);
				$('#trn_vat_v0').prop('checked',false);
			} else {
				$('#trn_vat_v10').prop('checked',false);
				$('#trn_vat_v0').prop('checked',true);
			}
			
			//alert($('#trn_payment_auth_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			
			
			$('#trn_payment').val($('#trn_payment_'+id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#trn_total_amount_text').html($('#trn_amount_all_'+$('#trn_id').val()).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
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
			
			var imgval = $('#trn_img_' +id).val();
			//alert(imgval.lastIndexOf('_'))
			//alert(imgval.length)
			if (imgval.lastIndexOf('_') < imgval.length - 2) {
				var imgarr = $('#trn_img_' +id).val().split(';');
				var imgsrc = "";
				for (var i = 0; i < imgarr.length; i ++) {
					imgsrc = imgsrc + "<img src=\""+imgarr[i]+"\" height=100>"
				}
				$('#trn_img_show').html(imgsrc);
			} else {
				$('#trn_img_show').html('');
			}
			
			
			$('#trn_amount').val($('#trn_amount_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#trn_amount_withoutVAT').val($('#trn_amount_withoutVAT_' +id).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#prg_status').val($('#prg_status_' +id).val());
			//$('#trn_payment_remain').val($('#trn_payment_remain_' +id).val());
			
			/*trn_amount_withoutVAT = parseInt($('#trn_quantity_' +id).val()) * parseInt($('#trn_unit_price_' +id).val());
			$('#trn_amount_withoutVAT').val(trn_amount_withoutVAT);
			
			VAT = 0;
			if ($('#trn_vat_' +id).val() == 10) VAT = 10;
			trn_amount = trn_amount_withoutVAT + trn_amount_withoutVAT / 100 * VAT;
			$('#trn_amount').val(trn_amount);
			
			trn_payment_remain = trn_amount - parseInt($('#trn_payment').val());
			$('#trn_payment_remain').val(trn_payment_remain);*/
			calTotalAmount();
			hideDesign('');
			changecolor();
			$('#action_note').html('<img src="images/viewinfo.png" height="14"> Update h&#243;a &#273;&#417;n c&#361; ' + $('#trn_ref_' +id).val() + ' (ID: '+id+')');
			$('#action_code').val('1');
			
			if ($('#prg_step1_by_'+id).val().toUpperCase() != '<?php echo strtoupper($_SESSION['MM_Username']);?>' && <?php echo $_SESSION['MM_Isadmin'] == null?0:$_SESSION['MM_Isadmin']; ?> != 1) {
				disabledOrder();
			} else if ($('#prg_step1_by_'+id).val().toUpperCase() == '<?php echo strtoupper($_SESSION['MM_Username']);?>') {
				if ($('#prg_status_'+id).val() < 42) {
					enabledOrder();
				} else {
					disabledOrder();
					$('#action_note').html('Xem Đơn hàng đã hoàn thành');
				}
				
			} else {
				enabledOrder();
			}
			//alert($('#trn_total_amount').val());
			$('#trn_total_amount_text').html($('#trn_amount_all_'+id).val());
			
			calTotalAmount();
		}
		
		function loadAvailbleCust(id)
		{
			$('#cust_name').val($('#cust_name_' +id).val());
			$('#cust_company').val($('#cust_company_' +id).val());
			$('#cust_email').val($('#cust_email_' +id).val());
			$('#cust_phone').val($('#cust_phone_' +id).val());

			$('#trn_ref').val('<?=$maxref[0]['maxref'] + 1?>');
			$('#trn_name').val('');
			
			$('input[name=trn_has_file]').prop('checked',false);
			
			//$('#trn_prd_code').val('');
			$('#trn_prd_type').val('');
			
			//$('#option').html('');
			
			$('#trn_quantity').val('');
			$('#trn_unit_price').val('');
			
			
			
			$('#trn_vat_v10').prop('checked',false);
			$('#trn_vat_v0').prop('checked',true);
			
			$('#trn_payment_type_ck').val('0');
			$('#trn_payment_type_tm').val('0');
			$('#trn_payment').val('0');
			$('#trn_payment_auth').html('');
			$('#trn_detail').val('');
			
			//$('#trn_class_v0').prop('checked',true);
			//$('#trn_class_v1').prop('checked',false);

			$('#trn_type_code_v1').prop('checked',false);
			$('#trn_type_code_v0').prop('checked',true);
			
			$('#trn_during').val('3');
			$('#trn_end_date').val('');
			$('#trn_start_date').val('');
			
			$('#trn_deliver_type_v1').prop('checked',false);
			$('#trn_deliver_type_v0').prop('checked',true);
			
			$('#trn_deliver_address').val('');
			$('#prg_step2_dt1').html('');
			$('#prg_step2_dt2').html('');
			$('#prg_step2_dt3').html('');
			
			$('#prg_step3_dt1').html('');
			$('#prg_step3_dt2').html('');
			$('#prg_issue_date').html('');
			$('#prg_issue_value').val('');
			$('#prg_issue_from').val('');
			
			$('#prg_step4_dt1').html('');
			$('#prg_step4_dt2').html('');
			
			$('#prg_note').val('');
			$('#trn_img_show').html('');
			
			$('#trn_amount_withoutVAT').val('');
			$('#trn_amount').val('');
			$('#trn_payment_remain').val('');
			$('#prg_status').val('');
			
			hideDesign('');
			changecolor();
			$('#action_note').html('<img src="images/user.png" height="14"> T&#7841;o h&#243;a &#273;&#417;n m&#7899;i (Số hóa đơn <font color=red><?=$maxref[0]['maxref'] + 1?></font>) c&#249;ng kh&#225;ch h&#224;ng ' + $('#cust_name_' +id).val());
			$('#action_code').val('3');
			
			//alert('asd');
			clearMakeNewOrder();
			enabledOrder();
			//calTotalAmount();
		}
		
		function addOrder(id)
		{
			$('#cust_name').val($('#cust_name_' +id).val());
			$('#cust_company').val($('#cust_company_' +id).val());
			$('#cust_email').val($('#cust_email_' +id).val());
			$('#cust_phone').val($('#cust_phone_' +id).val());

			$('#trn_id').val(id);
			$('#trn_ref').val($('#trn_ref_' +id).val());
			$('#trn_name').val('');
			
			$('input[name=trn_has_file]').prop('checked',false);
			
			//$('#trn_prd_code').val('');
			$('#trn_prd_type').val('');
			
			//$('#option').html('');
			
			$('#trn_quantity').val('');
			$('#trn_unit_price').val('');
			
			
			
			$('#trn_vat_v10').prop('checked',false);
			$('#trn_vat_v0').prop('checked',true);
			
			$('#trn_payment').val($('#trn_total_pay_'+id).val());
			$('#trn_payment_auth').html('');
			if ($('#trn_payment_auth').html() == "") {
				$('#trn_payment_auth').html('0')
			} 
			$('#trn_detail').val('');
			
			//$('#trn_class_v0').prop('checked',true);
			//$('#trn_class_v1').prop('checked',false);

			$('#trn_type_code_v1').prop('checked',false);
			$('#trn_type_code_v0').prop('checked',true);
			
			$('#trn_during').val('3');
			$('#trn_end_date').val('');
			$('#trn_start_date').val('');
			
			$('#trn_deliver_type_v1').prop('checked',false);
			$('#trn_deliver_type_v0').prop('checked',true);
			
			$('#trn_deliver_address').val('');
			$('#prg_step2_dt1').html('');
			$('#prg_step2_dt2').html('');
			$('#prg_step2_dt3').html('');
			
			$('#prg_step3_dt1').html('');
			$('#prg_step3_dt2').html('');
			$('#prg_issue_date').html('');
			$('#prg_issue_value').val('');
			$('#prg_issue_from').val('');
			
			$('#prg_step4_dt1').html('');
			$('#prg_step4_dt2').html('');
			
			$('#prg_note').val('');
			$('#trn_img_show').html('');
			
			$('#trn_amount_withoutVAT').val('');
			$('#trn_amount').val('');
			//$('#trn_payment_remain').val('');
			$('#prg_status').val('');
			
			//calTotalAmount();
			hideDesign('');
			changecolor();
			$('#action_code').val('2');
			searchorder(1,1);
			
			$('#action_note').html('<img src="images/add1.png" height="14"> T&#7841;o th&#234;m s&#7843;n ph&#7849;m trong c&#249;ng h&#243;a &#273;&#417;n');
			
			$('#trn_name').focus();
			if ($('#prg_step1_by_'+id).val() == null) {
				disabledOrder();
			} else {
				if ($('#prg_step1_by_'+id).val().toUpperCase() != '<?php echo strtoupper($_SESSION['MM_Username']);?>' && <?php echo $_SESSION['MM_Isadmin'] == null?0:$_SESSION['MM_Isadmin']; ?> != 1) {
					disabledOrder();
				} else if ($('#prg_step1_by_'+id).val().toUpperCase() == '<?php echo strtoupper($_SESSION['MM_Username']);?>') {
					if ($('#prg_status_'+id).val() < 42) {
						enabledOrder();
					} else {
						disabledOrder();
						$('#action_note').html('Xem Đơn hàng đã hoàn thành');
					}
					
				} else {
					enabledOrder();
				}
			}
			calTotalAmount();
		}
		
		function clearAvailbleOrder()
		{
			//$('#cust_name').val('');
			//$('#cust_company').val('');
			//$('#cust_email').val('');
			//$('#cust_phone').val('');

			$('#trn_ref').val('');
			$('#trn_name').val('');
			
			$('input[name=trn_has_file]').prop('checked',false);
			
			//$('#trn_prd_code').val('');
			$('#trn_prd_type').val('');
			
			//$('#option').html('');
			
			$('#trn_quantity').val('');
			$('#trn_unit_price').val('');
			
			$('#trn_amount_withoutVAT').val('');
			$('#trn_amount').val('');
			$('#trn_payment_remain').val('');
			
			$('#trn_vat_v0').prop('checked',true);
			
			$('#trn_payment').val('');
			$('#trn_payment_auth').html('');
			$('#trn_detail').val('');
			
			//$('#trn_class_v0').prop('checked',true);
			changecolor();

			//$('#trn_type_code_v1').prop('checked',true);

			$('#trn_during').val('3');
			$('#trn_end_date').val('');
			$('#trn_start_date').val('');
			
			$('#trn_deliver_type_v0').prop('checked',true);
			
			$('#trn_deliver_address').val('');
			$('#prg_step2_by').val('');
			$('#design_user_value').html('');
			$('#prg_step2_dt1').html('');
			$('#prg_step2_dt2').html('');
			$('#prg_step2_dt3').html('');
			
			$('#prg_step3_by').val('');
			$('#build_user_value').html('');
			$('#prg_step3_dt1').html('');
			$('#prg_step3_dt2').html('');
			$('#prg_issue_date').html('');
			$('#prg_issue_value').val('');
			$('#prg_issue_from').val('');
			
			$('#prg_step4_by').val('');
			$('#deliver_user_value').html('');
			$('#prg_step4_dt1').html('');
			$('#prg_step4_dt2').html('');
			
			$('#prg_note').val('');
			$('#trn_img_show').html('');
			$('#prg_status').val('');
			
			hideDesign('');
			changecolor();
			$('#action_note').html('<img src="images/Handshake.png" height="14"> T&#7841;o h&#243;a &#273;&#417;n m&#7899;i (Số hóa đơn <font color=red><?=$maxref[0]['maxref'] + 1?></font>)');
			$('#action_code').val('0');

			enabledOrder();
			calTotalAmount();
		}
    </script>	<?php mysql_close($db); ?>