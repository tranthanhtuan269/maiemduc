
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
// edit new user
if($_POST['hid_user_action'] == 'changeinfo') {
	$image=$_FILES["user_img"];
	$filename=$_SESSION['MM_Username']."_".date("Ymd")."_".$image['name'];
	$filename=str_replace(" ","",$filename);
	$filetype=$image['type'];
	$filesize=$image['size'];
	$upfile=$image['tmp_name'];
	$duongdan="images/avatar/".$filename;
	move_uploaded_file($upfile,$duongdan);
	
	if ($_FILES['user_img']['name'][0] == '') {
		if ($_POST['user_img_default'] == 'on') {
			$duongdan = '1';
		} else {
			$duongdan = '';
		}
	}
	
	$user_name = $_SESSION['MM_Username'];
	$user_pass = $_POST['user_pass'];
	$user_fullname = $_POST['user_fullname'];
	$user_sex = $_POST['user_sex'];
	$user_birth = $_POST['user_birth'];
	$user_address = $_POST['user_address'];
	$user_email = $_POST['user_email'];
	$user_phone = $_POST['user_phone'];
	$tbl_user_edit=$mysqlIns->changeinfo_tbl_user($user_name,
										   $user_pass,
										   $user_fullname,
										   $user_sex,
										   $user_birth,
										   $user_address,
										   $user_email,
										   $user_phone,
										   $duongdan
	);
	if ($tbl_user_edit > 0) {
		echo "--><script language=\"javascript\">alert('Bạn đã sửa thông tin cá nhân thành công!'); </script><!--";
		
	} else {
		echo "--><script language=\"javascript\">alert('Cập nhật thất bại'); </script><!--";
	}
		
}

// edit new user
if($_POST['hid_user_action'] == 'changepass') {
	$user_pass_old = $_POST['user_pass_old'];
	$user_pass_new = $_POST['user_pass_new'];
	$user_pass_new_re = $_POST['user_pass_new_re'];
	if ($user_pass_new == $user_pass_new_re) {
		$tbl_user_edit=$mysqlIns->changepass_tbl_user($user_pass_old,$user_pass_new);
		//echo 'qqqqqq'.$tbl_user_edit;
		if ($tbl_user_edit > 0) {
			echo "--><script language=\"javascript\">alert('Bạn đã đổi password thành công!'); </script><!--";
			
		} else {
			echo "--><script language=\"javascript\">alert('[Mật khẩu cũ] không đúng'); </script><!--";
		}
	} else {
		echo "--><script language=\"javascript\">alert('[Gõ lại mật khẩu mới] không khớp với [Mật khẩu mới]'); </script><!--";
	}
} 

// Load user by username
$user = $_SESSION['MM_Username'];
$tbl_user_edit=$mysqlIns->get_staff_by_username($user);
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
<td width="30%" valign="top" >

<form id="formchangepass" name="formchangepass" method="POST">
<input name="hid_user_action" type="hidden" id="hid_grp_action" value="changepass"/>

<table width="100%" cellspacing="0" cellpadding="0">
<thead>

	<tr><td colspan=2 width="100%">
	<fieldset><legend>Đổi mật khẩu</legend>
	<table width="100%">
	<tr>
		<td colspan=2 width="15%" align="center" valign="top" style="padding-left:10px;padding-right:10px;"><img src="images/lock-icon.png" height=40></td>
		<td colspan=2 width="60%" align="left" valign="top" style="padding-left:10px;padding-right:10px;">
			<table width="100%" >
			<tr>
			  <td width="30%" scope="row" align="right" nowrap="nowrap"><b><u>Mật khẩu cũ</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="20%" align="left">
			  <input maxlength="255" name="user_pass_old" type="password" id="user_pass_old" size="18" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['grp_code'];?>"/>
			  </td>
			</tr>
			<tr>
			  <td width="30%" scope="row" align="right" nowrap="nowrap"><b><u>Mật khẩu mới</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="20%" align="left">
			  <input maxlength="255" name="user_pass_new" type="password" id="user_pass_new" size="18" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['grp_code'];?>"/>
			  </td>
			</tr>
			<tr>
			  <td width="30%" scope="row" align="right" nowrap="nowrap"><b><u>Gõ lại mật khẩu mới</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="20%" align="left">
			  <input maxlength="255" name="user_pass_new_re" type="password" id="user_pass_new_re" size="18" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['grp_code'];?>"/>
			  </td>
			</tr>
			
	<tr>
      <td scope="row" height=4 align="right" colspan=2></td>
	</tr>
	
			<tr>
			  <th scope="row">&nbsp;</th>
			  <td><input type="button" name="btnSave" id="btnSave" value="L&#432;u thay đổi	" onclick="return confirmSaveChangePass();"/>
			  </td>
			</tr>
			</table>
		</td>
		<td width="25%" align="left" valign="top" >
		</td>
	</tr>
	
	</table>
	</fieldset>
	</td></tr>
</thead>
</table>
</form>
</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="70%" valign="top">


<form id="formchangeinfo" name="formchangeinfo" method="POST" enctype="multipart/form-data">
<input name="hid_user_action" type="hidden" id="hid_user_action" value="changeinfo"/>

<table width="100%" cellspacing="0" cellpadding="0">
<thead>

	<tr><td colspan=2 width="100%">
	<fieldset><legend>Sửa th&#244;ng tin cá nhân</legend>
	<table width="100%">
	<tr>
	
		<?php if ($_FILES['user_img']['name'][0] != '') { ?>
		
	  <td colspan=2 width="15%" align="center" valign="top">
		<img src="<?php echo $duongdan;?>" height=200>
	  </td>
      <?php } else { 
	  
	  echo '<!--';
	  if(isset($_REQUEST['user'])) {
			$user = $_REQUEST['user'];
			$tbl_user_edit=$mysqlIns->get_staff_by_username($user);
		}
		echo '-->';
	  ?>
	  <td colspan=2 width="15%" align="center" valign="top">
		<img src="<?php echo $tbl_user_edit[0]['user_img'];?>" height=200>
	  </td>
	  <?php } ?>
	  
		
		<td colspan=2 width="85%" align="left" valign="top">
			<table width="100%" >
			<tr>
			  <td width="20%" scope="row" align="right"><b><u>Username</u> &nbsp;<font color="red"></font></b></td>
			  <td width="80%" align="left">
			  <b><?php echo $_SESSION['MM_Username']; ?></b>
			  </td>
			</tr>

			<tr>
			  <td scope="row" align="right"><b>Tên đầy đủ &nbsp;</b></td>
			  <td align="left">
			  <input maxlength="255" name="user_fullname" type="text" id="username" size="40" placeholder="" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_fullname'])) echo $_POST['user_fullname']; else echo $tbl_user_edit[0]['user_fullname']; ?>"/>
			  
			  </td>
			</tr>
			<tr>
		<td scope="row" align="right" nowrap="nowrap"><b>&#7842;nh &#273;&#7841;i di&#7879;n&nbsp;</b></td>
      <td scope="row" align="left">
		<input id="user_img" type="file" name="user_img" size="21">
	  <b>&#7842;nh m&#7863;c &#273;&#7883;nh&nbsp;</b> <input id="user_img_default" type="checkbox" name="user_img_default" onclick="$('#user_img').val('')">
	  </td>
      
    </tr>
			 <tr height="35">
      <td scope="row" align="right"><b>Gi&#7899;i t&#237;nh &nbsp;</b></td>
      <td align="left"><label for="name"></label>
			<input type="radio" name="user_sex" value="M" <?php if ($_POST['user_sex'] == 'M') echo ' checked'; else if ($tbl_user_edit[0]['user_sex'] == 'M') echo 'checked'; ?>>&nbsp;Nam&nbsp;&nbsp;&nbsp;
			<input type="radio" name="user_sex" value="F" <?php if ($_POST['user_sex'] == 'F') echo ' checked'; else if ($tbl_user_edit[0]['user_sex'] == 'F') echo 'checked'; ?> >&nbsp;N&#7919;
			&nbsp;&nbsp;&nbsp;<b>N&#259;m sinh&nbsp;</b>&nbsp;
	 <input maxlength="10" onkeypress="return onlydate(event);" name="user_birth" type="text" id="user_birth" size="10" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_birth'])) echo $_POST['user_birth']; else echo $tbl_user_edit[0]['user_birth_f']; ?>"/>
     
     </td>
    </tr>

			<tr>
			  <td scope="row" align="right"><b>&#272;&#7883;a ch&#7881;&nbsp;</b>
			  </td>
			  <td align="left"><label for="name"></label>
			  <input name="user_address" type="text" id="user_address" size="70" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_address'])) echo $_POST['user_address']; else echo $tbl_user_edit[0]['user_address']; ?>"/>
			 </td>
			</tr>
			<tr>
		<td scope="row" align="right"><b>Thư điện tử &nbsp;</b></td>
      <td scope="row" align="left">
		<input name="user_email" type="text" id="user_email" size="30" placeholder="" value="<?php if(isset($_POST['user_email'])) echo $_POST['user_email']; else echo $tbl_user_edit[0]['user_email']; ?>"/>
	  </td>
      
    </tr>
			<tr>
				<td scope="row" align="right"><b>S&#7889; &#272;T &nbsp;</b></td>
			  <td scope="row" align="left">
				<input maxlength="15" onkeypress="return onlynumber(event);" name="user_phone" type="text" id="user_phone" size="20" placeholder="" value="<?php if(isset($_POST['user_phone'])) echo $_POST['user_phone']; else echo $tbl_user_edit[0]['user_phone']; ?>"/>
			  </td>
			  
			</tr>
	<tr>
      <td scope="row" height=4 align="right" colspan=2></td>
	</tr>
	
			<tr>
			  <th scope="row">&nbsp;</th>
			  <td><input type="button" name="btnSave" id="btnSave" value="L&#432;u thay đổi" onclick="return confirmSaveChangeInfo();"/>
			</tr>
			</table>
		</td>
	</tr>
	
	</table>
	</fieldset>
	</td></tr>
</thead>
</table>
</form>

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
	
	function confirmSaveChangePass() {
		if ($("#user_pass_old").val() == '') {
			alert('Bạn chưa nhập [Mật khẩu cũ] !');
			$("#user_pass_old").focus();
			return false;
		}
		if ($("#user_pass_new").val() == '') {
			alert('Bạn chưa nhập [Mật khẩu mới] !');
			$("#user_pass_new").focus();
			return false;
		}
		if ($("#user_pass_new_re").val() == '') {
			alert('Bạn chưa nhập [Gõ lại mật khẩu mới] !');
			$("#user_pass_new_re").focus();
			return false;
		}
		if ($("#user_pass_new").val() != $("#user_pass_new_re").val()) {
			alert('[Gõ lại mật khẩu mới] không khớp với [Mật khẩu mới]');
			$("#user_pass_new_re").focus();
			return false;
		}
		
		var confirmval = confirm('Bạn muốn thay đổi password?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("formchangepass").submit();
	}
	
	function confirmSaveChangeInfo() {
		
		
		var confirmval = confirm('Bạn muốn thay đổi thông tin cá nhân?');
		if (!confirmval) {
			return false;
		}
		
		document.getElementById("formchangeinfo").submit();
	}
  
</script>