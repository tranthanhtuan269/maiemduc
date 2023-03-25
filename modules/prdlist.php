
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
echo "<!--";
// delete opt


if (isset($_POST['post_type'])) {
	if ($_POST['post_type'] == 'delopt') {
		$post_id = $_POST['post_id'];
		$post_value = $_POST['post_value'];
		$post_name = $_POST['post_name'];
		
		if ($post_value == 'tp') {
			$tbl_option_check_del=$mysqlIns->check_tbl_trans_exist_tp($_GET['prd'], $_GET['tp'], $post_name);
			if ($tbl_option_check_del == 0) {
				$result=$mysqlIns->delete_tbl_product_type($post_id);
			} else {
				echo '--><script language="javascript">$( document ).ready(function() {
						//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');
						$.growl.error({ message: "Đang tồn tại hóa đơn với thuộc tính này nên bạn không thể xóa, Lưu ý: Bạn có thể update thuộc tính nhưng sẽ làm thay đổi tất cả các hóa đơn khác" });
					});</script><!--';
			}
		} elseif ($post_value == 'opt') {
			$tbl_option_check_del=$mysqlIns->check_tbl_trans_exist_opt($_GET['prd'], $_GET['tp'], $post_name);
			if ($tbl_option_check_del == 0) {
				$result=$mysqlIns->delete_tbl_product_opt($post_id);
			} else {
				echo '--><script language="javascript">$( document ).ready(function() {
						//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');
						$.growl.error({ message: "Đang tồn tại hóa đơn với quy cách này nên bạn không thể xóa, Lưu ý: Bạn có thể update quy cách nhưng sẽ làm thay đổi tất cả các hóa đơn khác" });
					});</script><!--';
			}
			
		} elseif ($post_value == 'prd') {
			$tbl_option_check_del=$mysqlIns->check_tbl_trans_exist_prd($_GET['prd'], $_GET['tp'], $post_name);
			if ($tbl_option_check_del == 0) {
				$result=$mysqlIns->delete_tbl_product($post_id);
			} else {
				echo '--><script language="javascript">$( document ).ready(function() {
						//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');
						$.growl.error({ message: "Đang tồn tại hóa đơn với sản phẩm này nên bạn không thể xóa, Lưu ý: Bạn có thể update sản phẩm nhưng sẽ làm thay đổi tất cả các hóa đơn khác" });
					});</script><!--';
			}
			
		}
	}
}



// Add new tp
if($_POST['hid_act'] == 'addTp') {
	$tp_id = $_POST['tp_id'];
		
	$hid_prd_code = $_POST['hid_prd_code'];
	$hid_tp_option_code = $_POST['hid_opt_code'];
	
	$tp_name = $_POST['tp_name'];
	if ($_POST['hid_tp_code'] == '') 
		$tp_code = strtoupper(str_replace(' ','_',utf8convert(trim($tp_name))));
	else 
		$tp_code = $_POST['hid_tp_code'];
		
	$tp_checked = $_POST['tp_checked'];
	$tp_order = $_POST['tp_order'];
	
	//echo '-->';
	$tbl_option_check=$mysqlIns->check_tbl_product_type_exist_tp($hid_prd_code, $hid_tp_option_code, $tp_code);
	
	if ($tbl_option_check > 0) {
		if ($_POST['hid_tp_code'] == '') {
			echo "--><script language=\"javascript\">alert('Thuộc tính này đã tồn tại!'); </script><!--";
		} else {
			$tbl_product_type_update=$mysqlIns->update_tbl_product_type_tp($tp_id,$hid_prd_code,
																	 $hid_tp_option_code,
																	 $tp_code,
																	 $tp_name,
																	 $tp_checked,
																	 $tp_order
			);
		}
	} else {
		$tbl_product_type_add=$mysqlIns->add_tbl_product_type_tp($hid_prd_code,
																 $hid_tp_option_code,
																 $tp_code,
																 $tp_name,
																 $tp_checked,
																 $tp_order
		);
		if ($tbl_product_type_add > 0) {
			//echo "--><script language=\"javascript\">alert('Thêm thuộc tính thành công!'); </script><!--";
			
		} else {
			echo "--><script language=\"javascript\">alert('Có lỗi hệ thống'); </script><!--";
		}
	}
}

// Add new opt
if($_POST['hid_act'] == 'addOpt') {
	$tp_id = $_POST['tp_id'];
	
	$tp_option = $_POST['tp_option'];
	
	if ($_POST['hid_opt_code'] == '') 
		$tp_option_code = strtoupper(str_replace(' ','_',utf8convert(trim($tp_option))));
	else 
		$tp_option_code = $_POST['hid_opt_code'];
		
	
	$tp_option_order = $_POST['tp_option_order'];
	$hid_prd_code = $_POST['hid_prd_code'];
	
	$tbl_option_check=$mysqlIns->check_tbl_product_type_exist($hid_prd_code, $tp_option_code);
	if ($tbl_option_check > 0) {
		if ($_POST['hid_opt_code'] == '') {
			echo "--><script language=\"javascript\">alert('Quy cách này đã tồn tại!'); </script><!--";
		} else {
		
			$tbl_product_type_add=$mysqlIns->update_tbl_product_type($tp_id, $hid_prd_code,
																	 $tp_option,
																	 $tp_option_code,
																	 $tp_option_order
																	);
		}
	} else {
		$tbl_product_type_add=$mysqlIns->add_tbl_product_type(	 $hid_prd_code,
																 $tp_option,
																 $tp_option_code,
																 $tp_option_order
		);
		if ($tbl_product_type_add > 0) {
			//echo "--><script language=\"javascript\">alert('Thêm quy cách thành công!'); </script><!--";
			
		} else {
			echo "--><script language=\"javascript\">alert('Có lỗi hệ thống'); </script><!--";
		}
	}
}


// Add new group
if($_POST['hid_act'] == 'addPrd') {
	$prd_name = $_POST['prd_name'];
	
	if ($_POST['hid_prd'] == '') 
		$prd_code = strtoupper(str_replace(' ','_',utf8convert(trim($prd_name))));
	else 
		$prd_code = $_POST['hid_prd'];
		
	
	
	$prd_order = $_POST['prd_order'];
	$tbl_product_check=$mysqlIns->check_tbl_product_exist($prd_code);
	if ($tbl_product_check > 0) {
		if ($_POST['hid_prd'] == '') {
			echo "--><script language=\"javascript\">alert('Sản phẩm này đã tồn tại!'); </script><!--";
		} else {
		
			$tbl_product_add=$mysqlIns->update_tbl_product($prd_code,
													 $prd_name,
													 $prd_order);
		}
		
		
	} else {
		$tbl_product_add=$mysqlIns->add_tbl_product($prd_code,
													 $prd_name,
													 $prd_order
		);
		if ($tbl_product_add > 0) {
			//echo "--><script language=\"javascript\">alert('Thêm Sản phẩm thành công!'); </script><!--";
			
		} else {
			echo "--><script language=\"javascript\">alert('Có lỗi hệ thống'); </script><!--";
		}
	}
}

$tbl_product=$mysqlIns->view_report_product();
if (isset($_REQUEST['prd'])) {
	$select_tbl_product_bycode=$mysqlIns->select_tbl_product_bycode($_REQUEST['prd']);
}

if (isset($_REQUEST['tp'])) {
	$select_tbl_product_tp_bycode=$mysqlIns->select_tbl_product_tp_bycode($_REQUEST['tp']);
}

if (isset($_REQUEST['opt'])) {
	$select_tbl_product_opt_bycode=$mysqlIns->select_tbl_product_opt_bycode($_REQUEST['tp'],$_REQUEST['opt']);
}
echo "-->";
$width = 97;
?>
<div id="dialog_block_group" title="Chi tiết">
</div>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="5">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>



<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="2">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<tr>
<td width="40%" valign="top">
<fieldset><legend>Thêm Sản phẩm</legend>

<form id="addPrd" name="addPrd" method="POST">
<input type="hidden" id="hid_act" name="hid_act" value="addPrd">
<input type="hidden" id="tp_id" name="tp_id" value="<?=$select_tbl_product_bycode[0]['tp_id'];?>">
<input type="hidden" id="hid_prd" name="hid_prd" value="<?=$select_tbl_product_bycode[0]['prd_code'];?>">
<table width="100%" cellspacing="0" cellpadding="0">

<tbody>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="30%">
		<b>Tên SP</b><br>
		<input maxlength="255" name="prd_name" type="text" id="prd_name" size="30" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['prd_name'])) echo $_POST['prd_name']; else echo $select_tbl_product_bycode[0]['prd_name']; ?>"/>
		
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<b>Sắp xếp</b><br>
		<input maxlength="255" name="prd_order" type="text" id="prd_order" size="2" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['prd_order'])) echo $_POST['prd_order']; else echo $select_tbl_product_bycode[0]['prd_order']; ?>"/>
		
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<br>
		<input type="button" name="btnSave" id="btnSave" value="<?php if (isset($_REQUEST['prd'])) echo 'Update'; else echo 'Add'; ?>" onclick="return confirmSavePrd(this.value);"/>
	</td>
</tr>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" colspan=4>
		<b><a href="?mode=<?=$_REQUEST['mode']?>"><u>Thêm mới</u></a></b>
			  
	</td>
	
</tr>
</tbody>
</table>
</form>
</fieldset>


<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%"><b>Mã SP</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="30%"><b>Tên SP</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>Sắp xếp</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="40%"><b>Ng&#224;y t&#7841;o</b></td>
	<td nowrap="nowrap" align="center" style="padding-left:6px;padding-right:6px;" colspan=1 width="10%"><b>X&#7917; l&#253;</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php

for($i=0;$i<count($tbl_product);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($tbl_product[$i]['prd_code']  == $_REQUEST['prd']) {
	$color = '#E0F8E0';
}
	
$stt = $i+ 1;
if ($tbl_product[$i]['prd_stat'] == 'O') {
	$status = 'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = 'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}


echo '
<input type="hidden" id="hid_prd_id_'.$tbl_product[$i]['prd_id'].'" value="'.$tbl_product[$i]["prd_order"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'" id="prd_'.$tbl_product[$i]["prd_id"].'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" nowrap="nowrap" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&prd='.$tbl_product[$i]['prd_code'].'" ><b>'.$tbl_product[$i]['prd_code'].'</b></a></td>
		<td nowrap="nowrap" align="left" valign="bottom" style="padding-left:6px;">'.$tbl_product[$i]['prd_name'].'</td>
		<td nowrap="nowrap" align="center" valign="bottom" style="padding-left:6px;"><span style="background-color: yellow;" onclick="javascript:setEditOrder(this,\''.$tbl_product[$i]["prd_id"].'\',\'prd\');" id="prd_id_'.$tbl_product[$i]["prd_id"].'">'.$tbl_product[$i]['prd_order'].'</span></td>
		<td nowrap="nowrap" style="padding-left:6px;">'.$tbl_product[$i]['prd_created'].'</td>
		<td nowrap="nowrap" align="center" style="padding-left:2px;padding-right:2px;" id="prd_icon_stat_'.$tbl_product[$i]["prd_id"].'" 
		onmouseover="
				$(\'#prd_'.$tbl_product[$i]["prd_id"].'\').attr(\'old_bgcolor\',$(\'#prd_'.$tbl_product[$i]["prd_id"].'\').attr(\'bgcolor\'));
				$(\'#prd_'.$tbl_product[$i]["prd_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#prd_icon_stat_'.$tbl_product[$i]["prd_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#prd_'.$tbl_product[$i]["prd_id"].'\').attr(\'bgcolor\',$(\'#prd_'.$tbl_product[$i]["prd_id"].'\').attr(\'old_bgcolor\'))
				$(\'#prd_icon_stat_'.$tbl_product[$i]["prd_id"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a onclick="javascript:if (confirm(\'Bạn muốn xóa sản phẩm?\')) { submitDelOpt(\''.$tbl_product[$i]["prd_code"].'\',\'prd\',null); } else { return false;}" href="javascript:" ><img src="images/metro-icon.png" height="15"></a></b>
		
		</td>
		
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>

</tbody>
</table>


</td>
<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="20%" valign="top">
<?php if ($_REQUEST['prd'] == '') {?>
<table width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr height="78">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%">
	</td>
</tr>

</tbody>
</table>
<?php } else { ?>


<fieldset><legend>Thêm quy cách</legend>
<form id="addOpt" name="addOpt" method="POST">
<input type="hidden" id="tp_id" name="tp_id" value="<?=$select_tbl_product_tp_bycode[0]['tp_id'];?>">
<input type="hidden" id="hid_act" name="hid_act" value="addOpt">
<input type="hidden" id="hid_prd_code" name="hid_prd_code" value="<?php echo $_REQUEST['prd'];?>">
<input type="hidden" id="hid_opt_code" name="hid_opt_code" value="<?=$select_tbl_product_tp_bycode[0]['tp_option_code'];?>">
<table width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%">
		<b>Quy cách</b><br>
		<input maxlength="255" name="tp_option" type="text" id="tp_option" size="13" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['tp_option'])) echo $_POST['tp_option']; else echo $select_tbl_product_tp_bycode[0]['tp_option']; ?>"/>
			  
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<b>Sắp xếp</b><br>
		<input maxlength="255" name="tp_option_order" type="text" id="tp_option_order" size="2" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['tp_option_order'])) echo $_POST['tp_option_order']; else echo $select_tbl_product_tp_bycode[0]['tp_option_order']; ?>"/>
		
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<br>
		<input type="button" name="btnSave" id="btnSave" value="<?php if (isset($_REQUEST['tp'])) echo 'Update'; else echo 'Add'; ?>" onclick="return confirmSaveOpt(this.value);"/>
	</td>
</tr>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" colspan=4>
		<b><a href="?mode=<?=$_REQUEST['mode']?>&prd=<?=$_REQUEST['prd']?>"><u>Thêm mới</u></a></b>
			  
	</td>
	
</tr>
</tbody>
</table>
</fieldset>
</form>
<?php } ?>

<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap"align="left" style="padding-left:6px;" width="15%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="50%" ><b>Quy cách</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="30%" ><b>Sắp xếp</b></td>
	<td nowrap="nowrap" align="center" style="padding-left:2px;padding-right:6px;" width="1%" ><b>Xử lý</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php
echo '<!--';
$select_tbl_product_type_all=$mysqlIns->select_tbl_product_type_all($_REQUEST['prd']);
echo '-->';
for($i=0;$i<count($select_tbl_product_type_all);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($select_tbl_product_type_all[$i]['tp_option_code']  == $_REQUEST['tp']) {
	$color = '#E0F8E0';
}
	
$stt = $i+ 1;


echo '
<input type="hidden" id="hid_tp_id_'.$select_tbl_product_type_all[$i]['tp_id'].'" value="'.$select_tbl_product_type_all[$i]["tp_option_order"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'" id="tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" nowrap="nowrap" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&prd='.$_REQUEST['prd'].'&tp='.$select_tbl_product_type_all[$i]['tp_option_code'].'" ><b>'.$select_tbl_product_type_all[$i]['tp_option'].'</b></a></td>
		<td align="center" nowrap="nowrap" style="padding-left:6px;"><span style="background-color: yellow;" onclick="javascript:setEditOrder(this,\''.$select_tbl_product_type_all[$i]["tp_id"].'\',\'opt\');" id="tp_id_'.$select_tbl_product_type_all[$i]["tp_id"].'">'.$select_tbl_product_type_all[$i]['tp_option_order'].'</span></td>
		<td align="center" style="padding-left:2px;" id="tp_icon_per_'.$select_tbl_product_type_all[$i]["tp_option_code"].'"  nowrap=\"nowrap\" 
		onmouseover="
				$(\'#tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'old_bgcolor\',$(\'#tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'bgcolor\'));
				$(\'#tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#tp_icon_per_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'bgcolor\',$(\'#tp_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'old_bgcolor\'))
				$(\'#tp_icon_per_'.$select_tbl_product_type_all[$i]["tp_option_code"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a href="javascript:" onclick="javascript:if (confirm(\'Bạn muốn xóa quy cách này?\')) { submitDelOpt(\''.$select_tbl_product_type_all[$i]["tp_option_code"].'\',\'opt\',null); } else { return false;}"><img src="images/metro-icon.png" height="15"></a></b>
		</td>
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>
</td>

<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="30%" valign="top">
<form id="addTp" name="addTp" method="POST">
<?php if ($_REQUEST['tp'] == '') {?>

<table width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr height="78">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%">
	</td>
</tr>

</tbody>
</table>
<?php } else { ?>


<fieldset><legend>Thêm thuộc tính</legend>

<input type="hidden" id="tp_id" name="tp_id" value="<?=$select_tbl_product_opt_bycode[0]['tp_id'];?>">
<input type="hidden" id="hid_act" name="hid_act" value="addTp">
<input type="hidden" id="hid_prd_code" name="hid_prd_code" value="<?php echo $_REQUEST['prd'];?>">
<input type="hidden" id="hid_opt_code" name="hid_opt_code" value="<?php echo $_REQUEST['tp'];?>">
<input type="hidden" id="hid_tp_code" name="hid_tp_code" value="<?=$select_tbl_product_opt_bycode[0]['tp_code'];?>">

<table width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%">
		<b>Thuộc tính</b><br>
		<input maxlength="255" name="tp_name" type="text" id="tp_name" size="25" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_fullname'])) echo $_POST['user_fullname']; else echo $select_tbl_product_opt_bycode[0]['tp_name']; ?>"/>
			  
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<b>Sắp xếp</b><br>
		<input maxlength="255" name="tp_order" type="text" id="tp_order" size="2" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_fullname'])) echo $_POST['user_fullname']; else echo $select_tbl_product_opt_bycode[0]['tp_order']; ?>"/>
		
	</td>
	<td nowrap="nowrap" align="center" style="padding-left:6px;" width="5%">
		<b>Mặc định</b><br>
		<input type="radio" name="tp_checked" group="tp_checked" value="1">
	</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%">
		<br>
		<input type="button" name="btnSave" id="btnSave" value="<?php if (isset($_REQUEST['opt'])) echo 'Update'; else echo 'Add'; ?>" onclick="return confirmSaveTp(this.value);"/>
	</td>
</tr>
<tr >
	<td nowrap="nowrap" align="left" style="padding-left:6px;" colspan=4>
		<b><a href="?mode=<?=$_REQUEST['mode']?>&prd=<?=$_REQUEST['prd']?>&tp=<?=$_REQUEST['tp']?>"><u>Thêm mới</u></a></b>
			  
	</td>
	
</tr>
</tbody>
</table>

</fieldset>

<?php } ?>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap"align="left" style="padding-left:6px;" width="15%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="50%" ><b>Thuộc tính</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%" ><b>Sắp xếp</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="20%" ><b>Mặc định</b></td>
	<td nowrap="nowrap" align="center" style="padding-left:6px;padding-right:6px;" nowrap="nowrap" colspan=2 width="1%"><b>X&#7917; l&#253;</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php
echo '<!--';
$select_tbl_product_type_byopt=$mysqlIns->select_tbl_product_type_byopt($_REQUEST['prd'],$_REQUEST['tp']);
echo '-->';
for($i=0;$i<count($select_tbl_product_type_byopt);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";

	
$stt = $i+ 1;
$checked = "";
if ($select_tbl_product_type_byopt[$i]["tp_checked"] == 1) $checked = "checked";
echo '

<input type="hidden" id="hid_tp_id_'.$select_tbl_product_type_byopt[$i]['tp_id'].'" value="'.$select_tbl_product_type_byopt[$i]["tp_order"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'" id="code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" nowrap="nowrap" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&prd='.$_REQUEST['prd'].'&tp='.$_REQUEST['tp'].'&opt='.$select_tbl_product_type_byopt[$i]['tp_code'].'" ><b>'.$select_tbl_product_type_byopt[$i]['tp_name'].'</td>
		<td align="center" nowrap="nowrap" style="padding-left:6px;"><span style="background-color: yellow;" onclick="javascript:setEditOrder(this,\''.$select_tbl_product_type_byopt[$i]["tp_id"].'\',\'tp\');" id="tp_id_'.$select_tbl_product_type_byopt[$i]["tp_id"].'">'.$select_tbl_product_type_byopt[$i]["tp_order"].'</span></td>
		<td align="center" nowrap="nowrap" style="padding-left:6px;"><input onclick="updateDefaultOpt(\''.$_REQUEST['prd'].'\',\''.$_REQUEST['tp'].'\',\''.$select_tbl_product_type_byopt[$i]["tp_id"].'\');" type="radio" value="0" name="tp_checked" group="tp_checked" '.$checked.'></td>
		<td align="center" style="padding-left:2px;" id="code_icon_per_'.$select_tbl_product_type_byopt[$i]["tp_code"].'"  nowrap=\"nowrap\" 
		onmouseover="
				$(\'#code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'old_bgcolor\',$(\'#code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'bgcolor\'));
				$(\'#code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#code_icon_per_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'bgcolor\',$(\'#code_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'old_bgcolor\'))
				$(\'#code_icon_per_'.$select_tbl_product_type_byopt[$i]["tp_code"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a href="javascript:" onclick="javascript:if (confirm(\'Bạn muốn xóa thuộc tính này?\')) { submitDelOpt(\''.$select_tbl_product_type_byopt[$i]["tp_id"].'\',\'tp\',\''.$select_tbl_product_type_byopt[$i]["tp_code"].'\'); } else { return false;}"><img src="images/metro-icon.png" height="15"></a></b>
		</td>
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>

</form>

</td>

<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="50%" valign="top"><span id="result_view"></span></td>
</tr>
</table>
<script type="text/javascript">
	$(function() {
               $("#user_birth").datepicker({ dateFormat: "dd-mm-yy" })
       });
    $(document).ready(function() {        
        $(".dialog_block_group").click(function(){	 
			var id=$(this).attr('title');	
			
			//$('#dialog_block_group').attr('title','Chi tiết quyền của ' + id);		
            $("#dialog_block_group").html('');
	        $("#dialog_block_group").dialog();	        
	        $("#dialog_block_group" ).dialog( "option", "modal", true );
	        $("#dialog_block_group" ).dialog( "option", "height", 350 );
	        $("#dialog_block_group" ).dialog( "option", "width", 750 );
	        $("#dialog_block_group" ).dialog( "option", "position", 'center' );	        
   
	        $.ajax({
	            /*    	    
		        type: "POST",      
		        url: ,		      
		        data: "userid=" + id,
		        */
		        
		        success: function(resp){		            
			        $("#dialog_block_group").html('<iframe id="ifrDilog" height="98%" width="100%" src="./admin_permission.php?grp='+id+'" frameborder="0"></iframe>');
		        },      
		        error: function(e){alert('Error: ' + e.responseText);
		        }
	        });	        
        });   
    });  
	
	function setEditOrder(obj,tpid, type) {
			//alert(tpid);
			if ($('#' + obj.id + '_text').val() != null) {
				return false;
			}
			
			thisval = $('#hid_' + obj.id).val().replace(/,/g,'');
			//alert(thisval);
			$('#' + obj.id).html('<input onkeypress="return keyOrder(event,\''+thisval+'\',\''+obj.id+'\',\''+tpid+'\',\''+type+'\');" onblur="updateOrder(\''+thisval+'\',\''+obj.id+'\',\''+tpid+'\',\''+type+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'8\' maxlength="10">');
			$('#' + obj.id+'_text').focus();
			$('#' + obj.id+'_text').select();
		}
		
		function keyOrder(e,thisval,objid,tpid,type) {
			if(e.keyCode == 13) {
				updateOrder(thisval,objid,tpid,type);
				return true;
			}
			if(e.keyCode == 27) {
				$('#' + objid).html(thisval);
				return true;
			}
			
			return onlydate(e);
		}
		
		function updateOrder(thisval, objid,tpid,type) {
			editval = $('#' + objid + '_text').val();

			if (thisval == editval) {
				if (thisval == '') thisval ='&nbsp;&nbsp;&nbsp;';
				$('#' + objid).html(thisval);
				return false;
			}
			
			//alert(objid);
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);
			
			editval_ = editval;
			$.ajax({
				url : 'json_order_change.php',
				data :  'type=' + type +
						'&keyid=' + tpid +
						'&editval=' + editval,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					
						$('#' + objid).html(editval_.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						$('#hid_' + objid).val(editval_.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				}
			});
			
			
			
		}
		
		
		
		function updateDefaultOpt(prd,tpcode,tpid) {
			var parm = 'prd=' + prd +
						'&tpcode=' + tpcode +
						'&tpid=' + tpid;
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#result_view').html(loading);
			$.ajax({
				url : 'json_save_options.php',
				data : parm,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(result);
					if (result.split("@")[1] > 0) {
						$('#result_view').html('<img src="images/check.png" height=30>');
						setTimeout(function(){
							$('#result_view').html('');
						}, 1000);
					} else {
						var ret = result.replace(/<!--/g,'').replace(/-->/g,'');
						$('#result_view').html('<img src="images/error.png" height=30><br>' + ret);
						
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$('#result_view').html('<img src="images/error.png" height=30>');
				}
			});
			
			
			
		}
	
	function confirmSavePrd(name) {

		if ($("#prd_name").val() == '') {
			alert('Bạn chưa nhập tên Sản phẩm !');
			$("#prd_name").focus();
			return false;
		}
		
		var confirmval = confirm('Bạn muốn ' +name+ ' Sản phẩm?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("addPrd").submit();
	}
	
	function confirmSaveOpt(name) {
		if ($("#tp_option").val() == '') {
			alert('Bạn chưa nhập quy cách !');
			$("#tp_option").focus();
			return false;
		}
		
		
		var confirmval = confirm('Bạn muốn ' +name+ ' Quy cách?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("addOpt").submit();
	}
	
	function confirmSaveTp(name) {
		if ($("#tp_name").val() == '') {
			alert('Bạn chưa nhập thuộc tính !');
			$("#tp_name").focus();
			return false;
		}
		
		
		var confirmval = confirm('Bạn muốn ' +name+ ' Thuộc tính?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("addTp").submit();
	}
  
</script>