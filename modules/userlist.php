
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
// Lock or delete user by username
if (isset($_POST['post_type'])) {
	if ($_POST['post_type'] == 'lock') {
		$username = $_POST['post_id'];
		$value = $_POST['post_value'];
		$result=$mysqlIns->update_tbl_user_lock($username,$value);
	} elseif ($_POST['post_type'] == 'deluser') {
		$user_id = $_POST['post_id'];
		$check_tbl_group_for_delete_user=$mysqlIns->check_tbl_group_for_delete_user($user_id);

		if ($check_tbl_group_for_delete_user <= 1) {

			echo "--><script language=\"javascript\">alert('Mỗi nhóm cần tồn tại ít nhất 1 user, vui lòng tạo ít nhất 1 user của bạn trước khi xóa các user khacs'); </script><!--";

		} else {

			$result=$mysqlIns->delete_tbl_user($user_id);

		}
	}
}

// Delete group by group_id
if (isset($_POST['post_type'])) {
	if ($_POST['post_type'] == 'delgrp') {
		$grp_id = $_POST['post_id'];
		$grp_code = $_POST['post_value'];
		
		$tbl_group_exist_user_check=$mysqlIns->check_tbl_group_exist_user($grp_code);
		if ($tbl_group_exist_user_check > 0) {
			echo "--><script language=\"javascript\">alert('Nhóm này đang tồn tại ".$tbl_group_exist_user_check." (users), bạn hãy remove các users này ra ngoài trước khi xóa nhóm!'); </script><!--";
		} else {
			$result=$mysqlIns->delete_tbl_group_by_id($grp_id);
		}
	}
}

// Load user by username
if(isset($_REQUEST['user'])) {
	$user = $_REQUEST['user'];
	$tbl_user_edit=$mysqlIns->get_staff_by_username($user);
}


// Add new user
if($_POST['hid_user_action'] == 'add') {
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
	
	$user_name = $_POST['user_name'];
	$user_pass = $_POST['user_pass'];
	$user_grp_code = $_POST['user_grp_code'];
	$user_fullname = $_POST['user_fullname'];
	$user_sex = $_POST['user_sex'];
	$user_birth = $_POST['user_birth'];
	$user_address = $_POST['user_address'];
	$user_email = $_POST['user_email'];
	$user_phone = $_POST['user_phone'];
	$tbl_user_check=$mysqlIns->check_tbl_group_exist($user_name);
	if ($tbl_user_check > 0) {
		echo "--><script language=\"javascript\">alert('Username này đã tồn tại!'); </script><!--";
	} else {
		$tbl_user_edit=$mysqlIns->add_tbl_user($user_name,
											   $user_pass,
											   $user_grp_code,
											   $user_fullname,
											   $user_sex,
											   $user_birth,
											   $user_address,
											   $user_email,
											   $user_phone,
											   $duongdan
		);
	}
}


// edit new user
if($_POST['hid_user_action'] == 'edit') {
	$image=$_FILES["user_img"];
	$filename=$_SESSION['MM_Username']."_".date("Ymd")."_".$image['name'];
	$filename=str_replace(" ","",$filename);
	$filetype=$image['type'];
	$filesize=$image['size'];
	$upfile=$image['tmp_name'];
	$duongdan="images/avatar/".$filename;
	move_uploaded_file($upfile,$duongdan);
	//echo '-->@'.$_POST['user_img_default'].'@<!--';
	//echo '-->@'.$_FILES['user_img']['name'][0].'@<!--';
	if ($_FILES['user_img']['name'][0] == '') {
		if ($_POST['user_img_default'] == 'on') {
			$duongdan = '1';
		} else {
			$duongdan = '';
		}
	}
	
	$user_name = $_POST['user_name'];
	$user_pass = $_POST['user_pass'];
	$user_grp_code = $_POST['user_grp_code'];
	$user_fullname = $_POST['user_fullname'];
	$user_sex = $_POST['user_sex'];
	$user_birth = $_POST['user_birth'];
	$user_address = $_POST['user_address'];
	$user_email = $_POST['user_email'];
	$user_phone = $_POST['user_phone'];
	$tbl_user_edit=$mysqlIns->edit_tbl_user($user_name,
										   $user_pass,
										   $user_grp_code,
										   $user_fullname,
										   $user_sex,
										   $user_birth,
										   $user_address,
										   $user_email,
										   $user_phone,
										   $duongdan
	);
}


// Add new group
if($_POST['hid_grp_action'] == 'add') {
	$image=$_FILES["grp_img"];
	$filename=$_SESSION['MM_Username']."_".date("Ymd")."_".$image['name'];
	$filename=str_replace(" ","",$filename);
	$filetype=$image['type'];
	$filesize=$image['size'];
	$upfile=$image['tmp_name'];
	$duongdan="images/avatar/".$filename;
	move_uploaded_file($upfile,$duongdan);
  
	$grp_name = $_POST['grp_name'];
	$grp_code = $_POST['grp_code'];
	
	$tbl_group_check=$mysqlIns->check_tbl_group_exist($grp_code);
	if ($tbl_group_check > 0) {
		echo "--><script language=\"javascript\">alert('Group này đã tồn tại!'); </script><!--";
	} else {
		$tbl_group_add=$mysqlIns->add_tbl_group($grp_name,
													 $grp_code,
													 $duongdan
		);
	}
}

$grp = $_REQUEST['grp'];
$tbl_group=$mysqlIns->select_tbl_group_all();
$tbl_user=$mysqlIns->select_tbl_user_all($grp);
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

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

<tr>
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/usericon1.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=userlist">Qu&#7843;n l&#253; nhóm và nh&#226;n vi&#234;n</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/usericon.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=grpadd">Thêm mới nhóm</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/add1.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=useradd">Th&#234;m nh&#226;n vi&#234;n</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		<td align="center" valign="middle" nowrap="nowrap" width="90%"></td>
	</tr>
	<tr  height="4">
	
		<td width="10%" colspan="4" align="center" height="4"></td>
		
	</tr>
</thead>
</table>
<form id="userAction" name="userAction" method="POST" enctype="multipart/form-data">

<?php if ($_REQUEST['mode'] == 'grpadd') { ?>
<input name="hid_grp_action" type="hidden" id="hid_grp_action" value="add"/>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

	<tr><td colspan=2 width="100%">
	<fieldset><legend>Thông tin nhóm</legend>
	<table width="100%">
	<tr>
		<td colspan=2 width="15%" align="center" valign="top"><img src="images/groupicon.png" height=90></td>
		<td colspan=2 width="60%" align="left" valign="top">
			<table width="100%" >
			<tr>
			  <td width="30%" scope="row" align="right" nowrap="nowrap"><b><u>Mã nhóm</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="20%" align="left">
			  <input maxlength="255" name="grp_code" type="text" id="grp_code" size="25" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['grp_code'];?>"/>
			  </td>
			  <td width="20%" scope="row" align="right" nowrap="nowrap"><b>T&#234;n nhóm &nbsp;</b></td>
			  <td width="30%" align="left">
			  <input maxlength="255" name="grp_name" type="text" id="grp_name" size="40" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['grp_name'];?>"/>
			  
			  </td>
			</tr>
			<tr>
			  <td width="30%" scope="row" align="right"></td>
			  <td width="20%" align="left" nowrap="nowrap">
			  (Ti&#7871;ng vi&#7879;t kh&#244;ng d&#7845;u, vi&#7871;t li&#7873;n)</td>
			  <td width="10%" scope="row" align="right"></td>
			  <td width="40%" align="left">

			  </td>
			</tr>
			<tr>
		<td scope="row" align="right" nowrap="nowrap"><b>Bi&#7875;u t&#432;&#7907;ng nh&#243;m&nbsp;</b></td>
      <td scope="row" align="left">
		<input id="grp_img" type="file" name="grp_img" size="21">
	  </td>
	  
    </tr>
	<tr>
      <td scope="row" height=4 align="right" colspan=2></td>
	</tr>
	
			<tr>
			  <th scope="row">&nbsp;</th>
			  <td><input type="button" name="btnSave" id="btnSave" value="L&#432;u nh&#243;m m&#7899;i" onclick="return confirmSaveGrp();"/>
			  <input type="button" name="btnback" id="btnsua" value="Quay l&#7841;i" onclick="history.go(-1);"/></td>
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

<?php } ?>

<?php if ($_REQUEST['mode'] == 'useradd') { ?>
<input name="hid_user_action" type="hidden" id="hid_user_action" value="add"/>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

	<tr><td colspan=2 width="100%">
	<fieldset><legend>Th&#244;ng tin c&#225;n b&#7897; nh&#226;n vi&#234;n</legend>
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
	  
		<td colspan=2 width="60%" align="left" valign="top">
			<table width="100%" >
			<tr>
			  <td width="20%" scope="row" align="right"><b><u>Username</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="80%" align="left">
			  <input maxlength="255" name="user_name" type="text" id="user_name" size="20" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['user_name'];?>"/>
			  &nbsp;&nbsp;&nbsp;<b><u>Password</u> <font color="red">(*)</font>&nbsp;</b>
			  <input maxlength="255" name="user_pass" type="password" id="user_pass" size="20" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['user_pass'];?>"/>
			  </td>
			</tr>
			<tr>
			  <td width="20%" scope="row" align="right"><b>Nhóm &nbsp;</b></td>
			  <td width="80%" align="left">
<select multiple="multiple" size=8 style='height: 100%;' name="user_grp_code[]" id="user_grp_code" style="border:1px solid #DADADA;">
<option value="" >--- Không chọn---</option>
<?php

echo "<!--";
$tbl_tbl_group=$mysqlIns->select_tbl_group_all();
echo "-->";
for($i=0;$i<count($tbl_tbl_group);$i++)
{
	if ((isset($_POST['user_grp_code']) && ($_POST['user_grp_code'] == $tbl_tbl_group[$i]['grp_code'])) || 
		(strpos(strtoupper($tbl_user_edit[0]['user_grp_code']),$tbl_tbl_group[$i]['grp_code']) !== false))
	{
		echo '<option selected="selected" value="'.$tbl_tbl_group[$i]['grp_code'].'" >'.$tbl_tbl_group[$i]['grp_code'].' - '.$tbl_tbl_group[$i]['grp_name'].'</option>';
	} else {
		echo '<option value="'.$tbl_tbl_group[$i]['grp_code'].'" >'.$tbl_tbl_group[$i]['grp_code'].' - '.$tbl_tbl_group[$i]['grp_name'].'</option>';
	}

}
?>
      </select>
			  </td>
			</tr>
			<tr>
			  <td width="20%" scope="row" align="right"><b>T&#234;n  nh&#226;n vi&#234;n &nbsp;</b></td>
			  <td width="80%" align="left">
			  <input maxlength="255" name="user_fullname" type="text" id="user_fullname" size="40" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $_POST['user_fullname'];?>"/>
			  
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
      <td width="80%" align="left"><label for="name"></label>
			<input type="radio" name="user_sex" value="M" <?php if ($_POST['user_sex'] == 'M') echo ' checked';?>>&nbsp;Nam&nbsp;&nbsp;&nbsp;
			<input type="radio" name="user_sex" value="F" <?php if ($_POST['user_sex'] == 'F') echo ' checked';?>>&nbsp;N&#7919;
			
	 &nbsp;&nbsp;&nbsp;<b>N&#259;m sinh&nbsp;</b>&nbsp;<input maxlength="10" onkeypress="return onlydate(event);" name="user_birth" type="text" id="user_birth" size="10" style="border:1px solid #DADADA;" value="<?php echo $_POST['user_birth'];?>"/>
     
     </td>
    </tr>

			<tr>
			  <td scope="row" align="right"><b>&#272;&#7883;a ch&#7881;&nbsp;</b>
			  </td>
			  <td width="80%" align="left"><label for="name"></label>
			  <input name="user_address" type="text" id="user_address" size="70" style="border:1px solid #DADADA;" value="<?php echo $_POST['user_address'];?>"/>
			 </td>
			</tr>
			<tr>
		<td scope="row" align="right"><b>Thư điện tử &nbsp;</b></td>
      <td scope="row" align="left">
		<input name="user_email" type="text" id="user_email" size="30" placeholder="" value="<?php echo $_POST['user_email'];?>"/>
	  </td>
      
    </tr>
			<tr>
				<td scope="row" align="right"><b>S&#7889; &#272;T &nbsp;</b></td>
			  <td scope="row" align="left">
				<input maxlength="15" onkeypress="return onlynumber(event);" name="user_phone" type="text" id="user_phone" size="20" placeholder="" value="<?php echo $_POST['user_phone'];?>"/>
			  </td>
			  
			</tr>
			
	<tr>
      <td scope="row" height=4 align="right" colspan=2></td>
	</tr>
	
			<tr>
			  <th scope="row">&nbsp;</th>
			  <td><input type="button" name="btnSave" id="btnSave" value="L&#432;u user m&#7899;i" onclick="return confirmSaveUser();"/>
			  <input type="button" name="btnback" id="btnsua" value="Quay l&#7841;i" onclick="history.go(-1);"/></td>
			</tr>
			</table>
		</td>
		<td width="25%" align="left" valign="top" ><!--
			<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="15%" colspan="2" scope="row" align="left" nowrap="nowrap"><b>T&#7893;ng &#273;i&#7875;m <font color="red"><?php echo $index_user[0]['total_amount'];?></font></b></b></td>
			  
			</tr>
			<tr bgcolor="#c2c2c2">
			  <td height="1" colspan="2" scope="row" align="left" nowrap="nowrap"></td>
			  
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m hi&#7879;n t&#7841;i (Th&#225;ng 12):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $index_user[0]['current_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m &#273;&#227; t&#237;ch l&#361;y:&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $index_user[0]['total_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>Th&#225;ng cao &#273;i&#7875;m nh&#7845;t (Th&#225;ng 1):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $index_user[0]['highest_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>Th&#225;ng th&#7845;p &#273;i&#7875;m nh&#7845;t (Th&#225;ng 4):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $index_user[0]['lowest_amount'];?></font></b></td>
			</tr>
		
			</table>-->
		</td>
	</tr>
	
	</table>
	</fieldset>
	</td></tr>
</thead>
</table>

<?php } elseif ($_REQUEST['mode'] == 'useredit') { ?>
<input name="hid_user_action" type="hidden" id="hid_user_action" value="edit"/>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

	<tr><td colspan=2 width="100%">
	<fieldset><legend>Th&#244;ng tin c&#225;n b&#7897; nh&#226;n vi&#234;n</legend>
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
		<td colspan=2 width="60%" align="left" valign="top">
			<table width="100%" >
			<tr>
			  <td width="20%" scope="row" align="right"><b><u>Username</u> <font color="red">(*)</font>&nbsp;</b></td>
			  <td width="80%" align="left">
			  <input maxlength="255" name="user_name" type="text" id="user_name" size="20" placeholder="" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_name'])) echo $_POST['user_name']; else echo $tbl_user_edit[0]['user_name']; ?>"/>
			  &nbsp;&nbsp;&nbsp;<b><u>Password</u> <font color="red">(*)</font>&nbsp;</b>
			  <input maxlength="255" name="user_pass" type="password" id="user_pass" size="20" placeholder="" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_pass'])) echo $_POST['user_pass']; else echo $tbl_user_edit[0]['user_pass']; ?>"/>
			  </td>
			</tr>
			<tr>
			  <td width="20%" scope="row" align="right"><b>Nhóm &nbsp;</b></td>
			  <td width="80%" align="left">
<select multiple="multiple" size=8 style='height: 100%;' name="user_grp_code[]" id="user_grp_code" style="border:1px solid #DADADA;">
<option value="" >--- Không chọn---</option>
<?php
echo "<!--";
$tbl_tbl_group=$mysqlIns->select_tbl_group_all();
echo "-->";
for($i=0;$i<count($tbl_tbl_group);$i++)
{
	if ((isset($_POST['user_grp_code']) && ($_POST['user_grp_code'] == $tbl_tbl_group[$i]['grp_code'])) || 
		(strpos(strtoupper($tbl_user_edit[0]['user_grp_code']),$tbl_tbl_group[$i]['grp_code']) !== false))
	{
		echo '<option selected="selected" value="'.$tbl_tbl_group[$i]['grp_code'].'" >'.$tbl_tbl_group[$i]['grp_code'].' - '.$tbl_tbl_group[$i]['grp_name'].'</option>';
	} else {
		echo '<option value="'.$tbl_tbl_group[$i]['grp_code'].'" >'.$tbl_tbl_group[$i]['grp_code'].' - '.$tbl_tbl_group[$i]['grp_name'].'</option>';
	}

}
?>
      </select>
			  </td>
			</tr>
			<tr>
			  <td width="20%" scope="row" align="right"><b>T&#234;n  nh&#226;n vi&#234;n &nbsp;</b></td>
			  <td width="80%" align="left">
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
      <td width="80%" align="left"><label for="name"></label>
			<input type="radio" name="user_sex" value="M" <?php if ($_POST['user_sex'] == 'M') echo ' checked'; else if ($tbl_user_edit[0]['user_sex'] == 'M') echo 'checked'; ?>>&nbsp;Nam&nbsp;&nbsp;&nbsp;
			<input type="radio" name="user_sex" value="F" <?php if ($_POST['user_sex'] == 'F') echo ' checked'; else if ($tbl_user_edit[0]['user_sex'] == 'F') echo 'checked'; ?> >&nbsp;N&#7919;
			&nbsp;&nbsp;&nbsp;<b>N&#259;m sinh&nbsp;</b>&nbsp;
	 <input maxlength="10" onkeypress="return onlydate(event);" name="user_birth" type="text" id="user_birth" size="10" style="border:1px solid #DADADA;" value="<?php if(isset($_POST['user_birth'])) echo $_POST['user_birth']; else echo $tbl_user_edit[0]['user_birth_f']; ?>"/>
     
     </td>
    </tr>

			<tr>
			  <td scope="row" align="right"><b>&#272;&#7883;a ch&#7881;&nbsp;</b>
			  </td>
			  <td width="80%" align="left"><label for="name"></label>
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
			  <td><input type="button" name="btnSave" id="btnSave" value="L&#432;u thay đổi" onclick="return confirmSaveUser();"/>
			  <input type="button" name="btnback" id="btnsua" value="Quay l&#7841;i" onclick="history.go(-1);"/></td>
			</tr>
			</table>
		</td>
		<td width="25%" align="left" valign="top" >
			<!--<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
			<tr>
			  <td width="15%" colspan="2" scope="row" align="left" nowrap="nowrap"><b>T&#7893;ng &#273;i&#7875;m <font color="red"><?php echo $tbl_user_edit[0]['total_amount'];?></font></b></b></td>
			  
			</tr>
			<tr bgcolor="#c2c2c2">
			  <td height="1" colspan="2" scope="row" align="left" nowrap="nowrap"></td>
			  
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m hi&#7879;n t&#7841;i (Th&#225;ng 12):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $tbl_user_edit[0]['current_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m &#273;&#227; t&#237;ch l&#361;y:&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $tbl_user_edit[0]['total_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>Th&#225;ng cao &#273;i&#7875;m nh&#7845;t (Th&#225;ng 1):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $tbl_user_edit[0]['highest_amount'];?></font></b></td>
			</tr>
			<tr>
			  <td width="15%" scope="row" align="right" nowrap="nowrap"><b>Th&#225;ng th&#7845;p &#273;i&#7875;m nh&#7845;t (Th&#225;ng 4):&nbsp;</b></td>
			  <td><b><font color="red"><?php echo $tbl_user_edit[0]['lowest_amount'];?></font></b></td>
			</tr>
		
			</table>-->
		</td>
	</tr>
	
	</table>
	</fieldset>
	</td></tr>
</thead>
</table>

<?php } ?>
</form>

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
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" colspan=2 width="10%"><b>X&#7917; l&#253;</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php

for($i=0;$i<count($tbl_group);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";

	
$stt = $i+ 1;
if ($tbl_group[$i]['grp_stat'] == 'O') {
	$status = 'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = 'images/complete.png';
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
		<td nowrap="nowrap" style="padding-left:2px;padding-right:2px;" id="grp_icon_stat_'.$tbl_group[$i]["grp_id"].'" 
		onmouseover="
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'old_bgcolor\',$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\'));
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#grp_icon_stat_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'old_bgcolor\'))
				$(\'#grp_icon_stat_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a onclick="javascript:if (confirm(\'Bạn muốn xóa Group này khỏi hệ thống?\')) { submitDelGrp(\''.$tbl_group[$i]["grp_id"].'\',\''.$tbl_group[$i]["grp_code"].'\'); } else { return false;}" href="javascript:" ><img src="images/metro-icon.png" height="15"></a></b>
		
		</td>
		<td style="padding-left:2px;padding-right:6px;" id="grp_icon_per_'.$tbl_group[$i]["grp_id"].'" width="1" 
		onmouseover="
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'old_bgcolor\',$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\'));
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#grp_icon_per_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',$(\'#grp_'.$tbl_group[$i]["grp_id"].'\').attr(\'old_bgcolor\'))
				$(\'#grp_icon_per_'.$tbl_group[$i]["grp_id"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a title="'.$tbl_group[$i]["grp_code"].'" class="dialog_block_group" href="javascript:" ><img src="images/security.png" height="15"></a></b>
		</td>
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>

</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>
<td width="70%" valign="top">
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>
<tr bgcolor="#eeedfb">
	<td nowrap="nowrap"align="left" style="padding-left:6px;" width="4%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="10%" ><b>Nh&#243;m</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" width="10%"><b>T&#234;n &#273;&#259;ng nh&#7853;p</b>&nbsp;</td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" nowrap="nowrap" width="20%"><b>T&#234;n &#273;&#7847;y &#273;&#7911;</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" nowrap="nowrap" width="15%"><b>S&#7889; &#273;i&#7879;n tho&#7841;i</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" nowrap="nowrap" width="15%" ><b>EMail</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;" nowrap="nowrap" width="20%"><b>Ng&#224;y t&#7841;o Account</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" nowrap="nowrap" colspan=2 width="6%"><b>X&#7917; l&#253;</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php

for($i=0;$i<count($tbl_user);$i++)
{

$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";

	
$stt = $i+ 1;
if ($tbl_user[$i]['user_stat'] == 'O') {
	$status = 'images/lock-icon.png';
	$status_stat = 'C';
	$status_msg = 'Bạn muốn khóa tài khoản này lại?';
	
} else {
	$status = 'images/complete.png';
	$status_stat = 'O';
	$status_msg = 'Bạn muốn mở lại tài khoản này?';
}



echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'" id="user_'.$tbl_user[$i]["user_id"].'">
		<td nowrap="nowrap" style="padding-left:6px;" valign="top">'.$stt.'</td>
		<td nowrap="nowrap" nowrap="nowrap" style="padding-left:6px;" valign="top"><img src="'.$tbl_user[$i]["user_img"].'" height=30></td>
		<td nowrap="nowrap" align="left" valign="top" style="padding-left:6px;"><b><a href="?mode=useredit&user='.$tbl_user[$i]["user_name"].'" ><img src="images/icon-user-name2.png" height=13>'.$tbl_user[$i]['user_name'].'</a></b></td>
		<td style="padding-left:6px;" valign="top">'.$tbl_user[$i]['user_fullname'].'</td>
		<td nowrap="nowrap" style="padding-left:6px;" valign="top">'.$tbl_user[$i]['user_phone'].'</td>
		<td nowrap="nowrap" style="padding-left:6px;" valign="top">'.$tbl_user[$i]['user_email'].'</td>
		<td nowrap="nowrap" align="left" nowrap="nowrap" style="padding-left:6px;" valign="top">'.$tbl_user[$i]['user_created'].'</td>
		
		<td nowrap="nowrap" style="padding-left:2px;padding-right:2px;" id="user_icon_stat_'.$tbl_user[$i]["user_id"].'" nowrap=\"nowrap\" 
		onmouseover="
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'old_bgcolor\',$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\'));
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#user_icon_stat_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'old_bgcolor\'))
				$(\'#user_icon_stat_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a onclick="javascript:if (confirm(\''.$status_msg.'\')) { submitLock(\''.$tbl_user[$i]["user_name"].'\',\''.$status_stat.'\'); } else { return false;}" href="javascript:" ><img src="'.$status.'" height="15"></a></b>
		</td>
		<td style="padding-left:2px;padding-right:2px;" id="user_icon_per_'.$tbl_user[$i]["user_id"].'"  nowrap=\"nowrap\" width="1" 
		onmouseover="
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'old_bgcolor\',$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\'));
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
				$(\'#user_icon_per_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'#DF0101\')
				" 
				
				onmouseout="
				$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',$(\'#user_'.$tbl_user[$i]["user_id"].'\').attr(\'old_bgcolor\'))
				$(\'#user_icon_per_'.$tbl_user[$i]["user_id"].'\').attr(\'bgcolor\',\'\');"
		>
		<b><a href="javascript:" onclick="javascript:if (confirm(\'Bạn muốn xóa tài khoản này khỏi hệ thống?\')) { submitDelAcc(\''.$tbl_user[$i]["user_id"].'\'); } else { return false;}"><img src="images/metro-icon.png" height="15"></a></b>
		</td>
		
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>
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
					//alert(id);
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
  
</script>