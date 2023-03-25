
<?php
header('Content-type: text/html; charset=utf-8'); require_once('./global.php'); 

$ischeck = $_REQUEST["ischeck"];

if ($ischeck == 2) {
	return;
} else {

if (!isset($_SESSION)) {
  session_start();
}

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

$isearch = $_REQUEST["isearch"];
$username = $_SESSION['MM_Username'];
if (isset($_REQUEST["search"])) {
	
	if ($_REQUEST["search"] == 'user'){
		$cust_phone = $_REQUEST['id'];
	} elseif ($_REQUEST["search"] == 'staff'){
		$isearch ='1';
		$username = $_REQUEST['id'];
	}
	
}
$lastrow = 0;
if (isset($_REQUEST["lastrow"])) {
	$lastrow = $_REQUEST["lastrow"];
}


if ($_REQUEST['vw'] == "") {
	$vw = "";
} else {
	$vw = $_REQUEST['vw'];
}

$cust_name =strtoupper(utf8convert($_REQUEST['cust_name']));
$cust_company =strtoupper(utf8convert($_REQUEST['cust_company']));
$cust_email =$_REQUEST['cust_email'];
$cust_phone =$_REQUEST['cust_phone'];
if ($_POST['trn_end_date']=="") {
		$trn_end_date = 'null';
		//$trn_class = "1";
  }
  else {
		$trn_end_dateArr = explode("-",$_POST['trn_end_date']);
		if (strlen($trn_end_dateArr[2]) == 4) {
			$trn_end_date = "'".$trn_end_dateArr[2].'-'.$trn_end_dateArr[1].'-'.$trn_end_dateArr[0]."'";
		}
		//$trn_class = "0";
  }
	  
$prg_status =$_REQUEST['prg_status'];
$trn_ref =$_REQUEST['trn_ref'];
$trn_name =strtoupper(utf8convert($_REQUEST['trn_name']));
$trn_prd_code =$_REQUEST['trn_prd_code'];

echo '<!--';
require("src/mysql_function.php");
$mysqlIns=new mysql(); $mysqlIns->link=$db;
$index_chuaxuly=$mysqlIns->get_all_cuocgoi_chua_xl_index(
$isearch,
$vw,
$_SESSION['step'],
$username,
$_SESSION['MM_Isadmin'],
$cust_name,
$cust_company,
$cust_email,
$cust_phone,
$trn_end_date,
$prg_status,
$trn_ref,
$trn_name,
$trn_prd_code,
$lastrow,
$ischeck 
);
echo '-->';
$width = "97.9";
if ($ischeck == 0 || $ischeck == 3) {



if ($_SESSION['MM_Isadmin'] != 1 && !(strpos($_SESSION['MM_group'],'DESIGN,')) !== false) {
	$styleEditEndate = '';
} else {
	$styleEditEndate = 'style="background-color: yellow;"';
}
echo $styleEditEndate;
if ($_SESSION['MM_Isadmin'] != 1 && !(strpos($_SESSION['MM_group'],'CARE,')) !== false) {
	$styleEditCare = '';
} else {
	$styleEditCare = 'style="background-color: yellow;"';
}


?>

<?php if ($_SESSION['step'] == "CARE") { ?>


<?php
$previousref= "";
$displayref= "";
$trn_total_pay = "";
$trn_total_amount = "";
$$trn_payment_remain = "";
$currentref = ' ';
for($i=0;$i<count($index_chuaxuly);$i++)
{
$previousref=$currentref;
$currentref = $index_chuaxuly[$i]["trn_ref"];
$displayref=$index_chuaxuly[$i]["trn_ref"];
$trn_total_pay=$index_chuaxuly[$i]["trn_total_pay"];
$trn_total_amount=$index_chuaxuly[$i]["trn_total_amount"];


$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$persentage = "10";
$status = "Ch&#7901; thi&#7871;t k&#7871;";
$saler = "ManhPD";
$pending = "GiangTT";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";

if ($index_chuaxuly[$i]["trn_payment_remain"] > 0 &&  $index_chuaxuly[$i]["ispaymentok"] !='1')
{
	$color = "#faf9a8"; // vang
} elseif ($index_chuaxuly[$i]["prg_note"] != null)
{
	$color = "#a8d2fa"; // xanh
} else {
	$color = "#ffffff"; // tráng
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
	
$stt = $lastrow + $i+ 1;

if (strlen($index_chuaxuly[$i]["prg_note"]) < 1)
	$note = 'Click to input';
else
	$note = trim($index_chuaxuly[$i]["prg_note"]);

if ($previousref != $currentref) {
	echo '<tr height="1" bgcolor="gray">
		<td width="100%" colspan="17" align="left" valign="middle">
		
		</td>';
} else {
	$displayref = '';
	$trn_total_pay= '';
	$trn_total_amount='';
}

if (strpos($index_chuaxuly[$i]["grp_img_step1"],',') !== false) {
	$grp_img_step1 = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_step1"].'"><img src="images/noicon1.png" width="16"></abbr>';
} else {
	$grp_img_step1 = '<img src="'.$index_chuaxuly[$i]["grp_img_step1"].'" width="16">';
}
if (strpos($index_chuaxuly[$i]["grp_img_pending"],',') !== false) {
	$grp_img_pending = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_pending"].'"><img src="images/noicon1.png" width="16"></abbr>';
} else {
	$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["grp_img_pending"].'" width="16">';
}	


$trn_payment_remain = $trn_total_amount - $trn_total_pay;
if ($trn_payment_remain == 0) $trn_payment_remain = "";


if ($index_chuaxuly[$i]["prg_step4_dt2_f"] != null) {
	$prg_step4_dt2 = $index_chuaxuly[$i]["prg_step4_dt2_f"];
} else {
	$prg_step4_dt2 = $index_chuaxuly[$i]["prg_status_f"];
}

$trn_total_amount_f =  number_format($trn_total_amount, 0, '.', ',');
$trn_payment_remain_f =  number_format($trn_payment_remain, 0, '.', ',');
$trn_total_pay_f =  number_format($trn_total_pay, 0, '.', ',');


echo '
<input type="hidden" id="hid_trn_payment_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_total_pay.'">
<input type="hidden" id="hid_trn_payment_remain_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_payment_remain.'">
<input type="hidden" id="hid_trn_ref_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_ref"].'">

<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$stt.'</td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$displayref.'&nbsp;</td>
		<td style="padding-left:0px;" width="10" valign="top"><b><img src="images/viewinfo.png" height="14"></b></td>
		<td style="padding-left:0px;"><b><a onclick="makeBlockUI();" href="index.php?mode=viewdetail&id='.$index_chuaxuly[$i]["trn_id"].'"class="login-window" id="trn_name_'.$index_chuaxuly[$i][0].'" >'.$index_chuaxuly[$i]["trn_name"].'</a></b></td>

		<td style="padding-left:0px;" width="10" valign="top"><b><img src="images/user.png"></b></td>
		<td align="left" nowrap="nowrap" style="padding-left:0px;"><b><a onclick="makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["cust_id"].'" >'.$index_chuaxuly[$i]["cust_name"].'</a></b></td>
		<td align="left" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prd_name"].'<!</a></td>
		
		
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$prg_step4_dt2.'</td>
		<td align="right" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<span id="trn_sub_amount_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["trn_amount_f"].'</span></td>
		<td align="right" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<span id="trn_amount_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_total_amount_f.'</span></td>
		<td align="right" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<span '.$styleEditCare.' onclick="setEdit(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_payment_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_total_pay_f.'</span></td>
		<td align="right" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<span '.$styleEditCare.' onclick="setEdit(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_payment_remain_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_payment_remain_f.'</span></td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<b><a onclick="makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step1_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step1_by"].'" >'.$grp_img_step1.$index_chuaxuly[$i]["prg_step1_by"].'</a></b></td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<b><a onclick="makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step4_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step4_by"].'" ><img src="images/deliver.png" width="16">	'.$index_chuaxuly[$i]["prg_step4_by"].'</a></b>&nbsp;</td>
		<td style="padding-left:0px;"><span '.$styleEditCare.' onclick="setEditNote(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_note_'.$index_chuaxuly[$i]["trn_id"].'">'.$note.'</span></td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;<span id="trn_action_'.$index_chuaxuly[$i]["trn_id"].'">
		';
//echo $index_chuaxuly[$i]["prg_step5_dt2"];
if (((strpos($_SESSION['MM_group'],'CARE,') !== false) || ($_SESSION['MM_Isadmin'] == "1"))
	&& ($trn_payment_remain != "")
	&& ($index_chuaxuly[$i]["prg_step4_dt2"] != null)
)
{
	if ($previousref != $currentref) {
		$prg_status = '42';
		echo '<b><a onclick="javascript:if (confirm(\'Bạn muốn update trạng thái đơn hàng ['.$index_chuaxuly[$i]["trn_ref"].'] thành đã thanh toán ?\')) { makeBlockUI(); } else { return false;}" href="?act='.$_REQUEST['act'].'&vw='.$vw.'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&ref='.$index_chuaxuly[$i]["trn_ref"].'&status='.$prg_status.'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/payment_complete.png" height="19"></a></b>';
	}
}
echo '</span></td>';
echo '<td nowrap="nowrap"><span id="trn_action_'.$index_chuaxuly[$i]["trn_id"].'">';
//echo $index_chuaxuly[$i]["prg_step5_dt2"];
if (((strpos($_SESSION['MM_group'],'CARE,') !== false) || ($_SESSION['MM_Isadmin'] == "1"))
	&& ($index_chuaxuly[$i]["prg_step5_dt2"] == null || $index_chuaxuly[$i]["prg_step5_dt2"] == '')
	&& ($index_chuaxuly[$i]["prg_step4_dt2"] != null)
)
{
		$prg_status = '51';
		echo '&nbsp;&nbsp;<b><a onclick="javascript:if (confirm(\'Bạn muốn update trạng thái đơn hàng ['.$index_chuaxuly[$i]["trn_name"].'] thành đã chăm sóc ?\')) { makeBlockUI(); } else { return false;}" href="?act='.$_REQUEST['act'].'&vw='.$vw.'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status='.$prg_status.'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/complete.png" height="15"></a></b>';
}
		echo '</span>&nbsp;&nbsp;</td>

		</tr>';

}

} else { ?>

<?php
$previousref= "";
for($i=0;$i<count($index_chuaxuly);$i++)
{
$previousref=$currentref;
$currentref = $index_chuaxuly[$i]["trn_ref"];


$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$persentage = "10";
$status = "Ch&#7901; thi&#7871;t k&#7871;";
$saler = "ManhPD";
$pending = "GiangTT";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";

if ($index_chuaxuly[$i]["prg_status"] > 41)
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
	

if (strpos($index_chuaxuly[$i]["grp_img_step1"],',') !== false) {
	$grp_img_step1 = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_step1"].'"><img src="images/noicon1.png" width="16"></abbr>';
} else {
	$grp_img_step1 = '<img src="'.$index_chuaxuly[$i]["grp_img_step1"].'" width="16">';
}
if (strpos($index_chuaxuly[$i]["grp_img_pending"],',') !== false) {
	$grp_img_pending = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_pending"].'"><img src="images/noicon1.png" width="16"></abbr>';
} else {
	$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["grp_img_pending"].'" width="16">';
}	

$stt = $lastrow + $i+ 1;
$trn_end_date_his ='';
$errImg = '';
$colspan=' colspan=1 ';
$ishowAction = 0;
if (((strtoupper($_SESSION['MM_Username']) == $index_chuaxuly[$i]["prg_pending_by"]) || ($_SESSION['MM_Isadmin'] == "1")) &&
	$index_chuaxuly[$i]["prg_step4_dt2"] == null)
{
		$ishowAction = 1;
}

if ($index_chuaxuly[$i]["trn_end_date_his"] != null) {
	$trn_end_date_his = '<image src="images/history.png" height="16">';
}

if ($index_chuaxuly[$i]["prg_status"] != '31') {
	$colspan=' colspan=2 ';
}  else {
	if ($index_chuaxuly[$i]["prg_issue_dt"] != null) {
		$errImg='<img src="images/error.png" height="14">';
	}
}



if ($previousref != $currentref) {
	echo '<tr height="1" bgcolor="gray">
		<td width="100%" colspan="18" align="left" valign="middle">
		
		</td>';
}
echo '
<tr id="tr_'.$index_chuaxuly[$i]["trn_id"].'" height="22" bgcolor="'.$color.'" 

>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$stt.'</td>
		<td style="padding-left:0px;" width="10" valign="top"><b><img src="images/viewinfo.png" height="14"></b></td>
		<td 
		onmouseover="
$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
"
onmouseout="
$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))

"

		style="padding-left:0px;"><b><a onclick="makeBlockUI();" href="index.php?mode=viewdetail&id='.$index_chuaxuly[$i]["trn_id"].'"class="login-window" id="trn_name_'.$index_chuaxuly[$i][0].'" >'.$index_chuaxuly[$i]["trn_name"].'</a></b></td>

		<td style="padding-left:0px;" width="10" valign="top"><b><img src="images/user.png"></b></td>
		<td align="left" style="padding-left:0px;"><b><a onclick="makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["cust_id"].'" >'.$index_chuaxuly[$i]["cust_name"].'</a></b></td>
		<td align="left" nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prd_name"].'</a></td>
		
		
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_start_date_f"].'</td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<span '.$styleEditEndate.' onclick="setEditEndDate(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_end_date_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["trn_end_date_f"].'</span>
		&nbsp;<span class="dialog_block_group" value="'.$index_chuaxuly[$i]["trn_id"].'" title="'.$index_chuaxuly[$i]["trn_name"].'" id="trn_end_date_his_'.$index_chuaxuly[$i]["trn_id"].'" >'.$trn_end_date_his.'</span>
		</td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_step2_dt3_f"].'</td>
		
		<td align="right" nowrap="nowrap" style="padding-right:15px;">&nbsp;&nbsp;<span id="date_duration_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["today_duration"].'</span></td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<b><a onclick="makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step1_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step1_by"].'" >'.$grp_img_step1.$index_chuaxuly[$i]["prg_step1_by"].'</a></b></td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;<b><a onclick="makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_pending_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_pending_by"].'" >'.$grp_img_pending.$index_chuaxuly[$i]["prg_pending_by"].'</a></b></td>
		
		<td nowrap="nowrap" width="70"><div class="progress">
      <span style="width: '.$index_chuaxuly[$i]["prg_percent_f"].'%;"><span>'.$index_chuaxuly[$i]["prg_percent_f"].'%</span></span>
    </div></td>
	<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_status_f"].'</td>
		<td nowrap="nowrap" style="padding-left:0px;">&nbsp;&nbsp;'.$errImg.$index_chuaxuly[$i]["prg_pending_from_dt_f"].'</td>';
		

if ($ishowAction==1)
{
		echo '<td id="icon_complete_'.$index_chuaxuly[$i][0].'" nowrap="nowrap" style="padding-left:0px;" >';
		echo '<b><a onmouseover="
		$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
		$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
		$(\'#icon_complete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#DF0101\')
		
		" 
		onmouseout="
		$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
		$(\'#icon_complete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'\')" onclick="javascript:if (confirm(\'Ồ YEEE! Update đơn hàng ['.$index_chuaxuly[$i]["trn_name"].'] thành ['.$index_chuaxuly[$i]["prg_action_f"].'] nha bạn?\')) { makeBlockUI(); } else { return false;}" href="'.$_SERVER['REQUEST_URI'].'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status='.$index_chuaxuly[$i]["prg_status"].'" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/complete.png" height="14"></a></b>';
		echo '</td>';
		if ($index_chuaxuly[$i]["prg_status"] > 31) {
				echo '<td id="icon_error_'.$index_chuaxuly[$i][0].'" nowrap="nowrap" style="padding-left:0px;">';
				echo '<b><a onmouseover="
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#icon_error_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
				$(\'#icon_error_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'\')
				" onclick="javascript:if (confirm(\'Ố! Đơn hàng ['.$index_chuaxuly[$i]["trn_name"].'] bị lỗi phải không bạn?\')) { makeBlockUI(); } else { return false;}" href="'.$_SERVER['REQUEST_URI'].'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status=err" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/error.png" height="14"></a></b>';
				//echo '&nbsp;';
				echo '</td>';
		} else {
			echo '<td nowrap="nowrap" style="padding-left:0px;">&nbsp;</td>';
		}
		
} else {
		if ($index_chuaxuly[$i]["prg_status"] > 31) {
				echo '<td id="icon_error_'.$index_chuaxuly[$i][0].'" nowrap="nowrap" style="padding-left:0px;">';
				echo '<b><a onmouseover="
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#icon_error_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
				$(\'#icon_error_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'\')
				" onclick="javascript:if (confirm(\'Ố! Đơn hàng ['.$index_chuaxuly[$i]["trn_name"].'] bị lỗi phải không bạn?\')) { makeBlockUI(); } else { return false;}" href="'.$_SERVER['REQUEST_URI'].'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status=err" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/error.png" height="14"></a></b>';
				//echo '&nbsp;';
				echo '</td>';
		} else {
			echo '<td nowrap="nowrap" style="padding-left:6px;" colspan="2">&nbsp;</td>';
		}
		
}		


	if ($_SESSION['MM_Isadmin'] == 1) {
		echo '<td id="icon_delete_'.$index_chuaxuly[$i][0].'" nowrap="nowrap">
		<b><a onmouseover="
		$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
				$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
		$(\'#icon_delete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#DF0101\')
		" 
		
		onmouseout="
		$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
		$(\'#icon_delete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'\')
		" onclick="javascript:if (confirm(\'Bạn muốn xóa sản phẩm ['.$index_chuaxuly[$i]["trn_name"].'] ?\')) { makeBlockUI(); } else { return false;}" href="?act='.$_REQUEST['act'].'&vw='.$vw.'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status=del&ref='.$index_chuaxuly[$i]["trn_ref"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/metro-icon.png" height="16"></a></b></td>
		';
	} else {
		echo '<td nowrap="nowrap" style="padding-left:6px;" >&nbsp;</td>';
	}

echo '</tr>';

}

}

if (count($index_chuaxuly) == 0) { ?>
<script type="text/javascript">
	data = '<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow" >';
	data += '<tr bgcolor="#ffffff" height="20">';
	data += '<td align="center" colspan="15"><b>No more data</b></td>';
	data += '</tr>';
	data += '<tr bgcolor="#bdb9b9" height="1">';
	data += '<td align="left" colspan="15"></td>';
	data += '</tr>';
	data += '</table>';
	$('#rowload').html(data);
</script>
<?php } 
} elseif ($ischeck == 1 || $ischeck == 4) {
	//echo $lastrow.'@'.count($index_chuaxuly).'@'.$_SESSION['limitrow'];
	if (count($index_chuaxuly) == 0) {
		echo '';
	} else {
?>
		<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow" >
	 <tr id="tr_showmore" onmouseover="
	 $('#tr_showmore').attr('old_bgcolor',$('#tr_showmore').attr('bgcolor'));
	 $('#tr_showmore').attr('bgcolor','#E0F8E0');"
	  onmouseout="
	 $('#tr_showmore').attr('bgcolor',$('#tr_showmore').attr('old_bgcolor')); 
	 $('#tr_showmore').attr('bgcolor',$('#tr_showmore').attr('old_bgcolor')); " 
	  onclick="loadmore();" 
	  bgcolor="#ffffff">
	 <td align="center" colspan='15'><span id="readmore_img"><img src="images/readmore.png" height="20"></span></td>
	 </tr>
	 <tr bgcolor="#bdb9b9" height="1">
	 <td align="left" colspan="15"></td>
	 </tr>
	 </table>
<?php
	}
}
}
?>

<script type="text/javascript">
$( function()
{
    var targets = $( '[rel~=tooltip]' ),
        target  = false,
        tooltip = false,
        title   = false;
 
    targets.bind( 'mouseenter', function()
    {
        target  = $( this );
        tip     = target.attr( 'title' );
        tooltip = $( '<div id="tooltip"></div>' );
 
        if( !tip || tip == '' )
            return false;
 
        target.removeAttr( 'title' );
        tooltip.css( 'opacity', 0 )
               .html( tip )
               .appendTo( 'body' );
 
        var init_tooltip = function()
        {
            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );
 
            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = target.offset().top - tooltip.outerHeight() - 20;
 
            if( pos_left < 0 )
            {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass( 'left' );
            }
            else
                tooltip.removeClass( 'left' );
 
            if( pos_left + tooltip.outerWidth() > $( window ).width() )
            {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass( 'right' );
            }
            else
                tooltip.removeClass( 'right' );
 
            if( pos_top < 0 )
            {
                var pos_top  = target.offset().top + target.outerHeight();
                tooltip.addClass( 'top' );
            }
            else
                tooltip.removeClass( 'top' );
 
            tooltip.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=10', opacity: 1 }, 50 );
        };
 
        init_tooltip();
        $( window ).resize( init_tooltip );
 
        var remove_tooltip = function()
        {
            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
            {
                $( this ).remove();
            });
 
            target.attr( 'title', tip );
        };
 
        target.bind( 'mouseleave', remove_tooltip );
        tooltip.bind( 'click', remove_tooltip );
    });
});

$(".dialog_block_group").click(function(){	
				//alert('asd');
				makeBlockUI();
					//alert('y');
				var id=$(this).attr('value');	
				$.ajax({
						    	    
						type: "GET",      
						url: 'json_end_date_his.php',		      
						data: "id=" + id,
						
						
						success: function(resp){
							 
							$.blockUI({ 
								message: resp, 
								css: { 
									top:  '20%',
									left: '27%',
									width: '600px' 
								} 
							});
							$('.blockOverlay').attr('title','Click to unblock').click($.unblockUI); 
						//$("#dialog_block_group").html(resp);
								
						},      
						error: function(e){
							$.unblockUI;
							alert('Error: ' + e.responseText);
						}
					});	    
			});  
</script><?php mysql_close($db); ?>