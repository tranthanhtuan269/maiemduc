
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

$grp = $_REQUEST['grp'];
$tbl_group=$mysqlIns->select_tbl_group_action();

echo "-->";
$width = 97;
?>
<div id="dialog_block_group" title="Chi tiết">
</div>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="4">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>



<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<tr>
<td width="30%" valign="top">
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="15%"><b>Nh&#243;m</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="30%"><b>T&#234;n nhóm</b>&nbsp;</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="40%"><b>Ng&#224;y t&#7841;o nhóm</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php

for($i=0;$i<count($tbl_group);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($tbl_group[$i]['grp_code']  == $grp) {
	$color = '#E0F8E0';
}
	
$stt = $i+ 1;
if ($tbl_group[$i]['grp_stat'] == 'O') {
	$status = $_SESSION['urlworkcode'].'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = $_SESSION['urlworkcode'].'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}


echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'" id="grp_'.$tbl_group[$i]["grp_id"].'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" nowrap="nowrap" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&grp='.$tbl_group[$i]['grp_code'].'" ><img src="'.$tbl_group[$i]['grp_img'].'" height=14>&nbsp;<b>'.$tbl_group[$i]['grp_code'].'</b></a></td>
		<td nowrap="nowrap" align="left" valign="bottom" style="padding-left:6px;">'.$tbl_group[$i]['grp_name'].'</td>
		<td nowrap="nowrap" style="padding-left:6px;">'.$tbl_group[$i]['grp_created'].'</td>
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>

</td>
<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="20%" valign="top" align="left">

<?php if ($_REQUEST['grp'] == '') { ?>
<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="100%" colspan="2" scope="row" align="left" nowrap="nowrap">
			  <b>B&#7841;n h&#227;y ch&#7885;n nh&#243;m &#273;&#7875; setting &#273;i&#7875;m<br>
			  </b>
			  </td>
			  
			</tr>
			</table>
			
<?php } else {?>
<?php if ($_REQUEST['grp'] != 'SALE') { ?>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="15%"><b>S&#7843;n ph&#7849;m</b></td>
	<!--<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:15px;" width="30%"><b>&#272;i&#7875;m</b></td>-->
</tr>
</thead>
<tbody id="body_other">
<?php 
echo '<!--';
$view_mark_product=$mysqlIns->view_mark_product($grp);	
echo '-->';
for($i=0;$i<count($view_mark_product);$i++) {

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($view_mark_product[$i]['prd_code']  == $_REQUEST['prd']) {
	$color = '#E0F8E0';
}

$stt = $i+ 1;
if ($tbl_group[$i]['grp_stat'] == 'O') {
	$status = $_SESSION['urlworkcode'].'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = $_SESSION['urlworkcode'].'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}


echo '
<input type="hidden" id="hid_id_'.$view_mark_product[$i]['prd_code'].'_'.$grp.'" value="'.$view_mark_product[$i]["mrk_point"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" align="left" valign="bottom" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&grp='.$_REQUEST['grp'].'&prd='.$view_mark_product[$i]['prd_code'].'">'.$view_mark_product[$i]['prd_name'].'</a></td>
		<!--<td nowrap="nowrap" align="right" valign="bottom" style="padding-left:6px;padding-right:15px;"><span style="background-color: yellow;" onclick="javascript:setEditMark(this,\''.preg_replace('/[^A-Za-z0-9\. -]/', '-', $view_mark_product[$i]["prd_code"]).'\',\''.$grp.'\',\'\',\'\');" id="id_'.preg_replace('/[^A-Za-z0-9\. -]/', '-', $view_mark_product[$i]["prd_code"]).'_'.$grp.'">'.$view_mark_product[$i]["mrk_point_f"].'</span></td>-->
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>
<?php } else { ?>

<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="100%" colspan="2" scope="row" align="left" nowrap="nowrap">
			  <b>&#272;i&#7875;m c&#7911;a SALE &#273;&#432;&#7907;c t&#237;nh theo doanh s&#7889;</b>
			  </td>
			  
			</tr>
			</table>
			
			
<?php 
} 
}?>
</td>
<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>

<td width="20%" valign="top" align="left">

<?php if ($_REQUEST['grp'] == '') { ?>
<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="100%" colspan="2" scope="row" align="left" nowrap="nowrap">
			  <b>B&#7841;n h&#227;y ch&#7885;n nh&#243;m &#273;&#7875; setting &#273;i&#7875;m<br>
			  </b>
			  </td>
			  
			</tr>
			</table>
			
<?php } else {?>
<?php if ($_REQUEST['grp'] != 'SALE') { ?>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="15%"><b>Quy cách</b></td>
	<!--<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:15px;" width="30%"><b>&#272;i&#7875;m</b></td>-->
</tr>
</thead>
<tbody id="body_other">
<?php 
echo '<!--';
$view_mark_type=$mysqlIns->view_mark_type($grp, $_REQUEST['prd']);	
echo '-->';
for($i=0;$i<count($view_mark_type);$i++) {

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($view_mark_type[$i]['tp_option_code']  == $_REQUEST['opt']) {
	$color = '#E0F8E0';
}

$stt = $i+ 1;
if ($tbl_group[$i]['grp_stat'] == 'O') {
	$status = $_SESSION['urlworkcode'].'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = $_SESSION['urlworkcode'].'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}


echo '
<input type="hidden" id="hid_id_'.$view_mark_type[$i]['tp_option_code'].'_'.$_REQUEST['prd'].'" value="'.$view_mark_type[$i]["mrk_point"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" align="left" valign="bottom" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&grp='.$_REQUEST['grp'].'&prd='.$_REQUEST['prd'].'&opt='.$view_mark_type[$i]['tp_option_code'].'">'.$view_mark_type[$i]['tp_option'].'</a></td>
		<!--<td nowrap="nowrap" align="right" valign="bottom" style="padding-left:6px;padding-right:15px;"><span style="background-color: yellow;" onclick="javascript:setEditMark(this,\''.$_REQUEST['prd'].'\',\''.$grp.'\',\''.$view_mark_type[$i]["tp_option_code"].'\',\''.$view_mark_type[$i]["tp_code"].'\');" id="id_'.$view_mark_type[$i]['tp_option_code'].'_'.$_REQUEST['prd'].'">'.$view_mark_type[$i]["mrk_point_f"].'</span></td>-->
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>
<?php } 
}?>
</td>
<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>

<td width="20%" valign="top" align="left">

<?php if ($_REQUEST['grp'] == '') { ?>
<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="100%" colspan="2" scope="row" align="left" nowrap="nowrap">
			  <b>B&#7841;n h&#227;y ch&#7885;n nh&#243;m &#273;&#7875; setting &#273;i&#7875;m<br>
			  </b>
			  </td>
			  
			</tr>
			</table>
			
<?php } else {?>
<?php if ($_REQUEST['grp'] != 'SALE') { ?>
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="15%"><b>Thuộc tính</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:15px;" width="30%"><b>&#272;i&#7875;m</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php 
//echo $_REQUEST['tp'];
echo '<!--';
$view_mark_opt=$mysqlIns->view_mark_opt($grp, $_REQUEST['prd'], $_REQUEST['opt']);	
echo '-->';
for($i=0;$i<count($view_mark_opt);$i++) {

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
if ($view_mark_opt[$i]['tp_code']  == $_REQUEST['tp']) {
	$color = '#E0F8E0';
}

$stt = $i+ 1;
if ($tbl_group[$i]['grp_stat'] == 'O') {
	$status = $_SESSION['urlworkcode'].'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = $_SESSION['urlworkcode'].'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}


echo '
<input type="hidden" id="hid_id_'.bin2hex($view_mark_opt[$i]['tp_code']).'_'.$_REQUEST['opt'].'" value="'.$view_mark_opt[$i]["mrk_point"].'">
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;">'.$stt.'</td>
		<td nowrap="nowrap" align="left" valign="bottom" style="padding-left:6px;"><a href="?mode='.$_REQUEST['mode'].'&grp='.$_REQUEST['grp'].'&prd='.$_REQUEST['prd'].'&opt='.$_REQUEST['opt'].'&tp='.$view_mark_opt[$i]['tp_code'].'">'.$view_mark_opt[$i]['tp_name'].'</a></td>
		<td nowrap="nowrap" align="right" valign="bottom" style="padding-left:6px;padding-right:15px;"><span style="background-color: yellow;" onclick="javascript:setEditMark(this,\''.$_REQUEST['prd'].'\',\''.$grp.'\',\''.$view_mark_opt[$i]["tp_option_code"].'\',\''.$view_mark_opt[$i]["tp_code"].'\',\''.bin2hex($view_mark_opt[$i]["tp_code"]).'\');" id="id_'.bin2hex($view_mark_opt[$i]['tp_code']).'_'.$_REQUEST['opt'].'">'.$view_mark_opt[$i]["mrk_point_f"].'</span></td>
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>
<?php } 
}?>
</td>
<td width="1%" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="30%" valign="top" >
<?php if ($_REQUEST['grp'] != 'SALE' && $_REQUEST['grp'] != '') { ?>
<table width="100%" cellspacing="10" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="100%" colspan="2" scope="row" align="left" nowrap="nowrap">
			  <b>B&#7841;n c&#243; th&#7875; thay &#273;&#7893;i &#273;i&#7875;m theo s&#7843;n ph&#7849;m<br>&#272;i&#7875;m n&#224;y &#225;p d&#7909;ng cho 1 s&#7843;n ph&#7849;m</b>
			  </td>
			  
			</tr>
			</table>
<?php }?>
</td>
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
	        $("#dialog_block_group" ).dialog( "option", "height", 450 );
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
	
	function confirmSaveUser() {
		if ($("#user_name").val() == '') {
			alert('Bạn chưa nhập Username !');
			$("#user_name").focus();
			return false;
		}
		
		if ($("#user_pass").val() == '') {
			alert('Bạn chưa khởi tạo Password !');
			$("#user_pass").focus();
			return false;
		}
		
		var confirmval = confirm('Bạn muốn lưu dữ liệu?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("userAction").submit();
	}
	
	function confirmSaveGrp() {
		if ($("#grp_code").val() == '') {
			alert('Bạn chưa nhập Mã nhóm !');
			$("#grp_code").focus();
			return false;
		}
		
		var confirmval = confirm('Bạn muốn lưu dữ liệu?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("userAction").submit();
	}
  
  
		function setEditMark(obj,idprd, idgrp,type, optid,opt) {
			//alert(idprd);
			if ($('#' + obj.id + '_text').val() != null) {
				return false;
			}
			
			thisval = $('#hid_' + obj.id).val().replace(/,/g,'');
			$('#' + obj.id).html('<input onkeypress="return keyMark(event,\''+thisval+'\',\''+obj.id+'\',\''+idprd+'\',\''+idgrp+'\',\''+type+'\',\''+optid+'\',\''+opt+'\');" onblur="updateMark(\''+thisval+'\',\''+obj.id+'\',\''+idprd+'\',\''+idgrp+'\',\''+type+'\',\''+optid+'\',\''+opt+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'8\' maxlength="10">');
			$('#' + obj.id+'_text').focus();
			$('#' + obj.id+'_text').select();
		}
		
		function keyMark(e,thisval,objid,idprd,idgrp,type, optid,opt) {
			if(e.keyCode == 13) {
				updateMark(thisval,objid,idprd,idgrp,type, optid,opt);
				return true;
			}
			if(e.keyCode == 27) {
				$('#' + objid).html(thisval);
				return true;
			}
			
			return onlydate(e);
		}
		
		function updateMark(thisval, objid,idprd,idgrp,type, optid,opt) {
			editval = $('#' + objid + '_text').val();

			if (thisval == editval) {
				if (thisval == '') thisval ='&nbsp;&nbsp;&nbsp;';
				$('#' + objid).html(thisval);
				return false;
			}
			
			//alert(objid);
			loading = '<img src="<?=$_SESSION['urlworkcode']?>images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);
			
			editval_ = editval;
			$.ajax({
				url : 'json_setting_mark.php',
				data :  'prd_code=' + idprd + 
						'&grp_code=' + idgrp + 
						'&type=' + type + 
						'&type=' + type + 
						'&opt=' + opt + 
						'&editval=' + editval,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(result);
						$('#' + objid).html(editval_.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						$('#hid_' + objid).val(editval_.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				}
			});
			
			
			
		}
</script>