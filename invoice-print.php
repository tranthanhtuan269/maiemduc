<?php ob_start();
header('Content-type: text/html; charset=utf-8'); 
	require_once('./global.php'); 

	function VndText($amount)
{
         if($amount <=0)
        {
            return $textnumber="";
        }
        $Text=array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $TextLuythua =array("","nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
        $textnumber = "";
        $length = strlen($amount);
        
        for ($i = 0; $i < $length; $i++)
        $unread[$i] = 0;
        
        for ($i = 0; $i < $length; $i++)
        {               
            $so = substr($amount, $length - $i -1 , 1);                
            
            if ( ($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)){
                for ($j = $i+1 ; $j < $length ; $j ++)
                {
                    $so1 = substr($amount,$length - $j -1, 1);
                    if ($so1 != 0)
                        break;
                }                       
                       
                if (intval(($j - $i )/3) > 0){
                    for ($k = $i ; $k <intval(($j-$i)/3)*3 + $i; $k++)
                        $unread[$k] =1;
                }
            }
        }
        
        for ($i = 0; $i < $length; $i++)
        {        
            $so = substr($amount,$length - $i -1, 1);       
            if ($unread[$i] ==1)
            continue;
            
            if ( ($i% 3 == 0) && ($i > 0))
            $textnumber = $TextLuythua[$i/3] ." ". $textnumber;     
            
            if ($i % 3 == 2 )
            $textnumber = 'trăm ' . $textnumber;
            
            if ($i % 3 == 1)
            $textnumber = 'mươi ' . $textnumber;
            
            
            $textnumber = $Text[$so] ." ". $textnumber;
        }
        
        //Phai de cac ham replace theo dung thu tu nhu the nay
        $textnumber = str_replace("không mươi", "lẻ", $textnumber);
        $textnumber = str_replace("lẻ không", "", $textnumber);
        $textnumber = str_replace("mươi không", "mươi", $textnumber);
        $textnumber = str_replace("một mươi", "mười", $textnumber);
        $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
        $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
        $textnumber = str_replace("mười năm", "mười lăm", $textnumber);
        
        return ucfirst($textnumber." đồng chẵn");
}


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

require("src/mysql_function.php");
$mysqlIns=new mysql(); $mysqlIns->link=$db;
echo '<!--';
$get_trans_by_ref = $mysqlIns->get_trans_by_ref($_REQUEST['ref']);
echo '-->';
?>


<head>
<style>

body {
	background: #fff;
	font-family: Tahoma, Sans-Serif;
	font-size: 15px;
}

table {
  white-space: normal;
  line-height: normal;
  font-weight: normal;
  font-size: medium;
  font-variant: normal;
  font-style: normal;
  color: -webkit-text;
  text-align: start;
}

td {
   font-size: 12px; 
}

</style>
</head>

<body cellspacing=0 cellpadding=0>

<table cellspacing="0" cellpadding="0" style="margin-top:0px;" width="100%">
<tr>
	<td width="40%" style="padding-right:20px;"><img src="images/logo-fennex-02.png" height=70></td>
	<td valign="top" colspan=2 align="left">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="30%"></td>
				<td align="right">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td nowrap="nowrap"><font size="1">VPGD: Số 3b, ngõ 109 Trường Chinh, phường Phương Liệt, quận Thanh Xuân, Hà Nội</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Email: infennex@gmail.com - Website: https://fennex.com.vn</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Holine/Phản ánh dịch vụ: 0903 202 038</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Kinh doanh nhận hàng: <b><?=strtoupper($get_trans_by_ref[0]['prg_step1_by'])?> <?=strtoupper($get_trans_by_ref[0]['user_phone'])?></b></font></td>
						</tr>
						
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan=2 align="left" style="padding-top:5px">
					<font size="4"><b>PHIẾU BIÊN NHẬN (liên 1)</b></font>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan=2 width="80%"></td>
	<td valign="top" align="left">
		Số: <b><?=$_REQUEST['ref']?></b>
	</td>
</tr>
<tr>
	<td colspan=3>Khách hàng: <b><?=$get_trans_by_ref[0]['cust_name']?></b></td>
</tr>
<?php
$address = "";
for($i=0;$i<count($get_trans_by_ref);$i++) {
	if (strlen($get_trans_by_ref[$i]['trn_deliver_address']) > 0) {
		$address = $get_trans_by_ref[$i]['trn_deliver_address'];
	}
}
?>
<tr>
	<td colspan=2 width="80%" >Địa chỉ: <b><?=$address?></b></td>
	<td valign="top" align="left">
		Tel: <b><?=$get_trans_by_ref[0]['cust_phone']?></b>
	</td>
</tr>
<tr>
	<td colspan=3>
		&nbsp;
	</td>
</tr>
<tr><td colspan=3>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #d4d4d4;border-collapse: collapse;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="border: 1px solid #d4d4d4;padding:4px;" width="5%">STT</td>
	<td nowrap="nowrap" width="30%" align="left" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="20%">Tên hàng</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="10%">Số lượng</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">Đơn giá</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">Thành tiền</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">VAT</td>
</tr>
</thead>
<tbody id="body_other">

<?php
if (count($get_trans_by_ref) > 0) {
$total = 0;
$total_withoutVAT = 0;
for($i=0;$i<count($get_trans_by_ref);$i++) {
$stt = $i+ 1;
$total = $get_trans_by_ref[$i]['trn_amount_all'];
$total_withoutVAT = $total_withoutVAT + $get_trans_by_ref[$i]['trn_amount_withoutVAT'];
$strgap = $get_trans_by_ref[$i]['trn_end_date']<>""?"[YC GẤP]":"";
?>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?> <?=$strgap?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?php if ($get_trans_by_ref[$i]['trn_vat'] == 10) echo "Có"; else echo "Không";?></b></td>
</tr>

<?php
}
?>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding:4px;" width="5%"><b></b></td>
	<td nowrap="nowrap" align="left" style="padding:4px; border-collapse: collapse;" width="20%">TỔNG CỘNG</td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="10%"><b></b></td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b><?=number_format($total_withoutVAT, 0, '.', ',');?></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b><?=number_format($total, 0, '.', ',');?></b></td>
</tr>
<?php } else { ?>

<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding:4px;" width="5%"><b></b></td>
	<td nowrap="nowrap" align="left" style="padding:4px; border-collapse: collapse;" width="20%">TỔNG CỘNG</td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="10%"><b></b></td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
</tr>
<?php
}
?>

</tbody>
</table>
</td>
</tr>
<tr>
	<td colspan=3>
		&nbsp;
	</td>
</tr>
<tr>
	<td colspan=3>Thành tiền bằng chữ: <b><?=VndText($total)?></b></td>
</tr>
<tr>
	<td colspan=3 >&nbsp;</td>
</tr>
<tr>
	<td colspan=2>Ngày nhận: <b><?php if (count($get_trans_by_ref) > 0) echo $get_trans_by_ref[0]['trn_start_date'];?></b></td>
	<td colspan=1>Tạm ứng: <b><?php if (count($get_trans_by_ref) > 0) echo number_format($get_trans_by_ref[0]['trn_payment_all'], 0, '.', ',');?></b></td>
</tr>
<tr>
	<td colspan=2>Ngày trả: <b></b></td>
	<td colspan=1>Còn lại: <b><?php if (count($get_trans_by_ref) > 0) echo number_format($total - $get_trans_by_ref[0]['trn_payment_all'], 0, '.', ',');?></b></td>
</tr>
<tr>
	<td colspan=3 ><br></td>
</tr>
<tr>
	<td colspan=1></td>
	<td colspan=2 align="right" nowrap="nowrap">Hà Nội, ngày ... tháng ... năm <b>20</b>...</td>
</tr>
<?php if ($get_trans_by_ref[0]['prg_note'] != "") {?>
<tr>
	<td colspan=3 ><br></td>
</tr>
<tr>
	<td colspan=3><b>Chú ý khi giao hàng:</b> <?=$get_trans_by_ref[0]['prg_note']?></td>
</tr>
<?php } ?>
<tr>
	<td colspan=3 height="10"></td>
</tr>
<tr>
	<td colspan=1 align="center" valign="top">KHÁCH HÀNG KÝ TÊN<br><i>(Quý khách vui lòng kiểm tra số lượng. Mọi thắc mắc về sau Fennex không chịu trách nhiệm)</i></td>
	
	<td colspan=2 align="center" valign="top">KINH DOANH KÝ TÊN</td>
</tr>
<!-- Type some HTML here -->

<?php 
if (count($get_trans_by_ref) <=5) {
$rowcnt = 200 - count($get_trans_by_ref) * 30;
?>


<tr>
	<td colspan=2 align="center" valign="top" colspan="2" height="<?=$rowcnt?>"></td>
</tr>

<table cellspacing="0" cellpadding="0" style="margin-top:0px;" width="100%">
<tr>
	<td width="40%" style="padding-right:20px;"><img src="images/logo-fennex-02.png" height=70></td>
	<td valign="top" colspan=2 align="left">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="30%"></td>
				<td align="right">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td nowrap="nowrap"><font size="1">VPGD: Số 3b, ngõ 109 Trường Chinh, phường Phương Liệt, quận Thanh Xuân, Hà Nội</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Email: infennex@gmail.com - Website: https://fennex.com.vn</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Holine/Phản ánh dịch vụ: 0903 202 038</font></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><font size="1">Kinh doanh nhận hàng: <b><?=strtoupper($get_trans_by_ref[0]['prg_step1_by'])?> <?=strtoupper($get_trans_by_ref[0]['user_phone'])?></b></font></td>
						</tr>
						
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan=2 align="left" style="padding-top:5px">
					<font size="4"><b>PHIẾU BIÊN NHẬN (liên 2)</b></font>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan=2 width="80%"></td>
	<td valign="top" align="left">
		Số: <b><?=$_REQUEST['ref']?></b>
	</td>
</tr>
<tr>
	<td colspan=3>Khách hàng: <b><?=$get_trans_by_ref[0]['cust_name']?></b></td>
</tr>
<?php
$address = "";
for($i=0;$i<count($get_trans_by_ref);$i++) {
	if (strlen($get_trans_by_ref[$i]['trn_deliver_address']) > 0) {
		$address = $get_trans_by_ref[$i]['trn_deliver_address'];
	}
}
?>
<tr>
	<td colspan=2 width="80%" >Địa chỉ: <b><?=$address?></b></td>
	<td valign="top" align="left">
		Tel: <b><?=$get_trans_by_ref[0]['cust_phone']?></b>
	</td>
</tr>
<tr>
	<td colspan=3>
		&nbsp;
	</td>
</tr>
<tr><td colspan=3>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #d4d4d4;border-collapse: collapse;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="border: 1px solid #d4d4d4;padding:4px;" width="5%">STT</td>
	<td nowrap="nowrap" width="30%" align="left" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="20%">Tên hàng</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="10%">Số lượng</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">Đơn giá</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">Thành tiền</td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px;border-collapse: collapse;" width="30%">VAT</td>
</tr>
</thead>
<tbody id="body_other">

<?php
if (count($get_trans_by_ref) > 0) {
$total = 0;
$total_withoutVAT = 0;
for($i=0;$i<count($get_trans_by_ref);$i++) {
$stt = $i+ 1;
$total = $total + $get_trans_by_ref[$i]['trn_amount'];
$total_withoutVAT = $total_withoutVAT + $get_trans_by_ref[$i]['trn_amount_withoutVAT'];
$strgap = $get_trans_by_ref[$i]['trn_end_date']<>""?"[YC GẤP]":"";
?>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?> <?=$strgap?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?php if ($get_trans_by_ref[$i]['trn_vat'] == 10) echo "Có"; else echo "Không";?></b></td>
</tr>

<?php
}
?>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding:4px;" width="5%"><b></b></td>
	<td nowrap="nowrap" align="left" style="padding:4px; border-collapse: collapse;" width="20%">TỔNG CỘNG</td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="10%"><b></b></td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b><?=number_format($total_withoutVAT, 0, '.', ',');?></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b><?=number_format($total, 0, '.', ',');?></b></td>
</tr>
<?php } else { ?>

<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr height="22" bgcolor="#F8F8F5" id="grp_ADMIN">
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px;"><?=$stt?></td>
		<td nowrap="nowrap" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_name']?></b></td>
		<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_quantity_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_unit_price_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b><?=$get_trans_by_ref[$i]['trn_amount_withoutVAT_f']?></b></td>
		<td nowrap="nowrap" align="right" valign="center" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;"><b></b></td>
</tr>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding:4px;" width="5%"><b></b></td>
	<td nowrap="nowrap" align="left" style="padding:4px; border-collapse: collapse;" width="20%">TỔNG CỘNG</td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="10%"><b></b></td>
	<td nowrap="nowrap" align="right" style="padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
	<td nowrap="nowrap" align="right" style="border: 1px solid #d4d4d4;padding:4px; border-collapse: collapse;" width="30%"><b></b></td>
</tr>
<?php
}
?>

</tbody>
</table>
</td>
</tr>
<tr>
	<td colspan=3>
		&nbsp;
	</td>
</tr>
<tr>
	<td colspan=3>Thành tiền bằng chữ: <b><?=VndText($total)?></b></td>
</tr>
<tr>
	<td colspan=3 >&nbsp;</td>
</tr>
<tr>
	<td colspan=2>Ngày nhận: <b><?php if (count($get_trans_by_ref) > 0) echo $get_trans_by_ref[0]['trn_start_date'];?></b></td>
	<td colspan=1>Tạm ứng: <b><?php if (count($get_trans_by_ref) > 0) echo number_format($get_trans_by_ref[0]['trn_payment_max'], 0, '.', ',');?></b></td>
</tr>
<tr>
	<td colspan=2>Ngày trả: <b></b></td>
	<td colspan=1>Còn lại: <b><?php if (count($get_trans_by_ref) > 0) echo number_format($total - $get_trans_by_ref[0]['trn_payment_max'], 0, '.', ',');?></b></td>
</tr>
<tr>
	<td colspan=3 ><br></td>
</tr>
<tr>
	<td colspan=1></td>
	<td colspan=2 align="right" nowrap="nowrap">Hà Nội, ngày ... tháng ... năm <b>20</b>...</td>
</tr>
<?php if ($get_trans_by_ref[0]['prg_note'] != "") {?>
<tr>
	<td colspan=3 ><br></td>
</tr>
<tr>
	<td colspan=3><b>Chú ý khi giao hàng:</b> <?=$get_trans_by_ref[0]['prg_note']?></td>
</tr>
<?php } ?>
<tr>
	<td colspan=3 ><br></td>
</tr>
<tr>
	<td colspan=1 align="center" valign="top">KHÁCH HÀNG KÝ TÊN<br><i>(Quý khách vui lòng kiểm tra số lượng. Mọi thắc mắc về sau Fennex không chịu trách nhiệm)</i></td>
	
	<td colspan=2 align="center" valign="top">KINH DOANH KÝ TÊN</td>
</tr>
<!-- Type some HTML here -->

<?php } ?>
</body>


<script type="text/javascript">
window.print();
</script>
<?php mysql_close($db); ?>