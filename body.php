<?php	
	$cust_name = "";
	$cust_company = "";
	$cust_email = "";
	$cust_phone = "";
	$prg_status = "";
	$trn_ref = "";
	$trn_name = "";
	$trn_prd_code = "";
	
	if (!isset($_REQUEST['act'])) $_REQUEST['act'] = "";
	if (!isset($_GET['trn_ref'])) $_GET['trn_ref'] = "";
	if (!isset($_GET['trn_name'])) $_GET['trn_name'] = "";
	if (!isset($_GET['trn_prd_code'])) $_GET['trn_prd_code'] = "";
	
	//echo 'dffffffffffffffffffffffffffffffff'.$_POST["hid_parm_sort_col"];
	echo '<!--';
	$_SESSION['steprow'] = 50;
	
	if (!isset($_POST["MM_update"])) $_POST["MM_update"] = "";
	if (!isset($_SESSION['limitrow']) || $_SESSION['limitrow'] < $_SESSION['steprow'] || $_POST["MM_update"] == "search") {
		$_SESSION['limitrow'] = $_SESSION['steprow']; 
	}
	
	if (!isset($_REQUEST['page'])) {
		$_page = 1;
		} else {
		$_page = $_REQUEST['page'];
	}
	
	$lastrow = $_SESSION['steprow'] * ($_page-1);
	
	$isearch = '0';
	$username = $_SESSION['MM_Username'];
	if (isset($_REQUEST["search"])) {		
		if ($_REQUEST["search"] == 'user'){
			$cust_phone = $_REQUEST['id'];
			$isearch = 5;
		} elseif ($_REQUEST["search"] == 'staff'){
			$isearch ='1';
			$username = $_REQUEST['id'];
		} elseif ($_REQUEST["search"] == 'saledetail'){
			// sontq updated
			$v_type = $_REQUEST['type'];
			$v_dt = $_REQUEST['dt'];
			$v_orderby = $_REQUEST['orderby'];
		}
		
	}
	
	if (!isset($_REQUEST['vw'])) $_REQUEST['vw'] = "";
	if ($_REQUEST['vw'] == "") {
		$vw = "";
		
		} else {
		$vw = $_REQUEST['vw'];
	}
	
	
	if (isset($_REQUEST["hid_parm_sort_col"])) {
		$derect = $_REQUEST["derect"];
		
		/*$_SESSION['isclick'] = $_SESSION['isclick'] + 1;
			if (($_SESSION['sort_col_val'] == null || $_SESSION['sort_col_val'] == str_replace(',',' ASC, ',$_REQUEST["hid_parm_sort_col"]).' ASC ')) {
			if ($_SESSION['sort_col_val'] == null && $vw == "complete") {
			$_SESSION['sort_col_val'] = str_replace(',',' '.$derect.', ',$_REQUEST["hid_parm_sort_col"]).' '.$derect.' ';
			$_SESSION['imgsrc'] = '<img src="images/re_icon_sort_up.gif">';
			} else {
			$_SESSION['sort_col_val'] = str_replace(',',' DESC, ',$_REQUEST["hid_parm_sort_col"]).' DESC ';
			$_SESSION['imgsrc'] = '<img src="images/icon_clf_down.gif">';
			}
			} else {
			$_SESSION['sort_col_val'] = str_replace(',',' ASC, ',$_REQUEST["hid_parm_sort_col"]).' ASC ';
			$_SESSION['imgsrc'] = '<img src="images/re_icon_sort_up.gif">';
		}*/
		
		$_SESSION['sort_col_val'] = str_replace(',',' '.$derect.', ',$_REQUEST["hid_parm_sort_col"]).' '.$derect.' ';
		if ($derect == "ASC") {
			$_SESSION['imgsrc'] = '<img src="images/re_icon_sort_up.gif">';
			} else {
			$_SESSION['imgsrc'] = '<img src="images/icon_clf_down.gif">';
		}
		
		echo "--><script language=\"javascript\">
		$( document ).ready(function() {
		//alert('sdasd');
		$('#imgsrc_".$_REQUEST["hid_parm_sort_col"]."').html('".$_SESSION['imgsrc']."');
		$('#td_".$_REQUEST["hid_parm_sort_col"]."').attr('bgcolor','yellow');
		$('#imgsrc_".$_REQUEST["hid_parm_sort_col"]."').attr('title','".$derect."');
		});
		</script><!--";
		
		} else {
		$_SESSION['sort_col_val'] = null;
		if ($vw == "complete") {
			if ($_SESSION['step'] == 'DESIGN' || $_SESSION['step'] == 'BUILD') {
				$td_orderrow = "td_prg_status";
				$imgsrc_row = "imgsrc_prg_status";
				$imgsrc = '<img src=\"images/re_icon_sort_up.gif\">';
				} else {
				$td_orderrow = "td_prg_pending_from_dt";
				$imgsrc_row = "imgsrc_prg_pending_from_dt";
				$imgsrc = '<img src=\"images/icon_clf_down.gif\">';
			}
			} else {
			$td_orderrow = "td_trn_order";
			$imgsrc_row = "imgsrc_trn_order";
			$imgsrc = '<img src=\"images/re_icon_sort_up.gif\">';
		}
		
		echo "--><script language=\"javascript\">
		$( document ).ready(function() {
		//alert('".$_SESSION['step']."');
		$('#".$imgsrc_row."').html('".$imgsrc."');
		$('#".$td_orderrow."').attr('bgcolor','yellow');
		
		});
		</script><!--";
	}	
	
	
	if (isset($_POST["hid_act"]) && ($_POST["hid_act"] == 'paymentForm')) {
		$payment_date = $_POST["payment_date"]==""?"":$_POST["payment_date"];
		
		if ($payment_date=="") {
				$payment_date_f = 'SYSDATE()';
		} else {
			$payment_date_arr = explode("-",$payment_date);
			if (strlen($payment_date_arr[2]) == 4) {
				$payment_date_f = "'".$payment_date_arr[2].'-'.$payment_date_arr[1].'-'.$payment_date_arr[0]."'";
			}
		}
		
		$payment_tm = $_POST["payment_tm"]==""?0:str_replace(",","",$_POST["payment_tm"]);
		$payment_ck = $_POST["payment_ck"]==""?0:str_replace(",","",$_POST["payment_ck"]);
		
		if ($payment_tm > 0 || $payment_ck > 0) {
			$payment_all=$mysqlIns->payment_all($_GET["id"], $payment_date_f, $payment_tm, $payment_ck);
		}
		
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: ".$actual_link);
		exit();
	}

	if (isset($_POST["hid_act"]) && ($_POST["hid_act"] == 'updateCust')) {
		$u_cust_phone_old = $_POST["cust_phone_old"];
		$u_cust_id = $_POST["cust_id"];
		$u_cust_name = $_POST["cust_name"];
		$u_cust_sex = $_POST["cust_sex"];
		$u_cust_birth = $_POST["cust_birth"];
		$u_cust_company = $_POST["cust_company"];
		$u_cust_address = $_POST["cust_address"];
		$u_cust_email = $_POST["cust_email"];
		$u_cust_phone = $_POST["cust_phone"];
		$u_cust_note = $_POST["cust_note"];
		$u_fulltext_search = 		    'cust_name@'.strtoupper(utf8convert($u_cust_name)).'@cust_name;';
		$u_fulltext_search = $u_fulltext_search.'cust_company@'.strtoupper(utf8convert($u_cust_company)).'@cust_company;';
		$u_fulltext_search = $u_fulltext_search.'cust_address@'.strtoupper(utf8convert($u_cust_address)).'@cust_address;';
		$u_fulltext_search = $u_fulltext_search.'cust_note@'.strtoupper(utf8convert($u_cust_note)).'@cust_note;';
		
		$u_hid_confirm = $_POST["hid_confirm"];
		$exist = $mysqlIns->check_customer_exist_phone($u_cust_id,$u_cust_phone);
		if ($exist > 0 && $u_hid_confirm == 0) {
			echo "--><script type=\"text/javascript\">$( document ).ready(function() {
			$.growl.warning({ message: \"Số điện thoại đã tồn tại của 1 khách hàng khác. Nhấn [Save overwrite] nếu bạn muốn ghi đè!\" });
			$('#btnUpdate').val('Save overwrite');
			$('#hid_confirm').val('1');
			});</script><!--";
		}
		if ($u_hid_confirm == 1 || $exist == 0) {
			$update_customer=$mysqlIns->update_customer(
			$u_cust_phone_old,
			$u_cust_id,
			$u_cust_name,
			$u_cust_sex,
			$u_cust_birth,
			$u_cust_company,
			$u_cust_address,
			$u_cust_email,
			$u_cust_phone,
			$u_cust_note,
			$u_fulltext_search,
			$u_hid_confirm);
			
			if ($update_customer > 0) {
				echo "--><script type=\"text/javascript\">$( document ).ready(function() {
				$.growl.notice({ message: \"Update thành công!\" });
				});</script><!--";
			}
		}
	}
	if ((isset($_GET["MM_update"])) && ($_GET["MM_update"] == "search")) {
		$cust_name =strtoupper(utf8convert($_GET['cust_name']));
		$cust_company =strtoupper(utf8convert($_GET['cust_company']));
		$cust_email =$_GET['cust_email'];
		$cust_phone =$_GET['cust_phone'];
		if ($_GET['trn_end_date']=="") {
			$trn_end_date = 'null';
			//$trn_class = "1";
		}
		else {
			$trn_end_dateArr = explode("-",$_GET['trn_end_date']);
			if (strlen($trn_end_dateArr[2]) == 4) {
				$trn_end_date = "'".$trn_end_dateArr[2].'-'.$trn_end_dateArr[1].'-'.$trn_end_dateArr[0]."'";
			}
			//$trn_class = "0";
		}

		
		$prg_status =$_GET['prg_status'];
		$trn_ref =$_GET['trn_ref'];
		$trn_name =strtoupper(utf8convert($_GET['trn_name']));
		$trn_prd_code =$_GET['trn_prd_code'];
		$isearch ='2';
		
	}
	
	
	if (!isset($_GET['search'])) $_GET['search'] = "";
	if (!isset($_GET['MM_update'])) $_GET['MM_update'] = "";
	$trn_end_date = "";
	if ($_GET["search"] == "advance" && $_GET["MM_update"] == '') {
		
	} else if ($_GET["search"] == 'saledetail') {		
		echo '--!>'.$v_type.'-'.$v_dt.'-'.$v_orderby.'<!--';
	} else {		
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
		0
		);		
	}
	$width = "98";
	
	$titlelink = "X&#7917; l&#253;";
	if ($_SESSION['step'] == "SALE") {
		$titlelink = "Giao h&#224;ng";
	}
	if ($_SESSION['step'] == "DESIGN") {
		$titlelink = "Thi&#7871;t k&#7871;";
	}
	if ($_SESSION['step'] == "BUILD") {
		$titlelink = "S&#7843;n xu&#7845;t";
	}
	if ($_SESSION['step'] == "DELIVER") {
		$titlelink = "Giao h&#224;ng";
	}
	if ($_SESSION['step'] == "CARE") {
		$titlelink = "Ch&#259;m s&#243;c";
	}
	
	if ($_SESSION['MM_Isadmin'] != 1 && !(strpos($_SESSION['MM_group'],'DESIGN,') !== false)) {
		$styleEditEndate = '';
		} else {
		$styleEditEndate = 'style="background-color: yellow;"';
	}
	echo $styleEditEndate;
	$styleEditCareNote = 'style="background-color: yellow;"';
	if ($_SESSION['MM_Isadmin'] != 1 && !strpos($_SESSION['MM_group'],'CARE,') !== false) {
		$styleEditCare = '';
		} else {
		$styleEditCare = 'style="background-color: yellow;"';
	}
	
	
	echo '-->';
?>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
	<thead>
		<tr height="2">
			
			<td width="10%" colspan="4" align="center" height="2"></td>
			
		</tr>
	</thead>
</table>


<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
	<thead>
		
		<tr>
			<td align="center" valign="middle" nowrap="nowrap"><img src="images/order11.png" height=25></td>
			<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=all">T&#7845;t c&#7843;</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
			<?php if ($_SESSION['step']=='SALE' || $_SESSION['step']== null) { ?>
				<td align="center" valign="middle"><img src="images/hotclock.png" height=25></td>
				<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=hot">&#272;&#417;n h&#224;ng g&#7845;p</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
			<?php } ?>
			
			<?php if ($_SESSION['step']=='CAREA') { ?>
				<td align="center" valign="middle"><img src="images/payment.png" height=25></td>
				<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=debit">Kh&#225;ch h&#224;ng n&#7907;</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
			<?php } ?>
			
			<?php if ($_SESSION['step']!='SALE' || $_SESSION['step']== null) { ?>
				<td align="center" valign="middle"><img src="images/order1.png" height=25></td>
				<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=wait">Ch&#432;a <?php echo $titlelink; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>
				<?php if ($_SESSION['step']!='CARE' && $_SESSION['step']!='DELIVER') { ?>
					<td align="center" valign="middle"><img src="images/order2.png" height=25></td>
					<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=on">&#272;ang <?php echo $titlelink; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>
				<?php } ?>
			<?php } ?>
			<td align="center" valign="middle"><img src="images/order3.png" height=25></td>
			<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?vw=complete">&#272;&#227; <?php echo $titlelink	; ?></a>&nbsp;&nbsp;&nbsp;</b></font> </td>
			<?php if ($_SESSION['step']=='CARED') { ?>
				<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?act=CARE&vw=unauth"><b><font size=4><font color='#ccc'>A</font>/U</font></b> Ch&#432;a x&#225;c nh&#7853;n</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
			<?php } ?>
			<td width="80%" align="center" valign="middle">
				<!--<img src="upload/free-happy-new-year-clipart-banners-6.jpg" height=21>
					<img src="upload/2015.jpeg" height=21>
				<img src="upload/88e0f16114a1e011c87b797513095a20.jpg" height=21>-->
			</td>
			<td align="center" valign="middle"><img src="images/expand.png" height=25></td>
			<td align="center" valign="middle" nowrap="nowrap" style="padding-left:10px;padding-right:10px;">
				<font size="2"><b>&nbsp;<a onclick="if (event.which != 2) makeBlockUI();" href="?search=advance">T&#236;m ki&#7871;m n&#226;ng cao</b></font>
				</td>
				
				
			</tr>
			<tr  height="1">
				
				<td width="10%" colspan="4" align="center" height="4"></td>
				
			</tr>
		</thead>
	</table>
	
	<div id="dialog_block_group" title="Chi tiết">
	</div>
	<script type="text/javascript">
		$(function() {
			$("#trn_end_date").datepicker({ dateFormat: "dd-mm-yy" });
			$("#cust_birth").datepicker({ dateFormat: "dd-mm-yy" });
			$("#payment_date").datepicker({ dateFormat: "dd-mm-yy" });
			
		});
		
	</script>
	
	<?php if ($_SESSION['search'] == "staff") { 
		echo '<!--';
		$index_user=$mysqlIns->get_staff_by_username($username);
		echo '-->';
	?>
	
	
	<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
		<thead>			
			<tr><td colspan=2 width="100%">
				<fieldset><legend>Th&#244;ng tin c&#225;n b&#7897; nh&#226;n vi&#234;n</legend>
					<table width="100%">
						<tr>
							<td colspan=2 width="15%" align="center" valign="top" style="padding-left:9px;"><img src="<?php echo $index_user[0]['user_img']; ?>" height=200></td>
							<td colspan=2 width="60%" align="left" valign="top">
								<table width="100%" >
									
									<tr>
										<td width="20%" scope="row" align="right"><b>T&#234;n  nh&#226;n vi&#234;n &nbsp;</b></td>
										<td width="80%" align="left">
											<input maxlength="255" name="user_fullname" type="text" id="username" size="40" placeholder="" style="border:1px solid #DADADA;" value="<?php echo $index_user[0]['user_fullname']; ?>" disabled="disabled"/>
											
										</td>
									</tr>
									<tr height="35">
										<td scope="row" align="right"><b>Gi&#7899;i t&#237;nh &nbsp;</b></td>
										<td width="80%" align="left"><label for="name"></label>
											<input type="radio" name="user_sex" value="male" <?php if ($index_user[0]['user_sex'] == 'M') echo 'checked'; ?>>&nbsp;Nam&nbsp;&nbsp;&nbsp;
											<input type="radio" name="user_sex" value="female" <?php if ($index_user[0]['user_sex'] == 'F') echo 'checked'; ?> >&nbsp;N&#7919;
											
											
										</td>
									</tr>
									<tr>
										<td scope="row" align="right"><b>N&#259;m sinh&nbsp;</b></td>
										<td width="80%" align="left"><label for="name"></label>
											<input maxlength="10" onkeypress="return onlydate(event);" name="user_birth" type="text" id="user_birth" size="10" style="border:1px solid #DADADA;" value="<?php echo $index_user[0]['user_birth_f']; ?>" disabled="disabled"/>
										</td>
									</tr>
									<tr>
										<td scope="row" align="right"><b>&#272;&#7883;a ch&#7881;&nbsp;</b>
										</td>
										<td width="80%" align="left"><label for="name"></label>
											<input name="user_address" type="text" id="user_address" size="70" style="border:1px solid #DADADA;" value="<?php echo $index_user[0]['user_address']; ?>" disabled="disabled"/>
										</td>
									</tr>
									<tr>
										<td scope="row" align="right"><b>Thư điện tử &nbsp;</b></td>
										<td scope="row" align="left">
											<input name="user_email" type="text" id="user_email" size="40" placeholder="" value="<?php echo $index_user[0]['user_email']; ?>" disabled="disabled"/>
										</td>
										
									</tr>
									<tr>
										<td scope="row" align="right"><b>S&#7889; &#272;T &nbsp;</b></td>
										<td scope="row" align="left">
											<input maxlength="15" onkeypress="return onlynumber(event);" name="user_phone" type="text" id="user_phone" size="20" placeholder="" value="<?php echo $index_user[0]['user_phone']; ?>" disabled="disabled"/>
										</td>
										
									</tr>
									<tr>
										<th scope="row">&nbsp;</th>
										<td><input type="button" name="btnback" id="btnsua" value="Quay l&#7841;i" onclick="history.go(-1);"/></td>
									</tr>
								</table>
							</td>
							<td width="25%" align="left" valign="top" style="padding-right:7px;">
								<!--<table width="100%" cellspacing="5" cellpadding="0" bgcolor="#faf9a8" style="border: 1px solid #c2c2c2; border-collapse:none;">
									<tr>
									<td width="15%" colspan="2" scope="row" align="left" nowrap="nowrap"><b>T&#7893;ng &#273;i&#7875;m <font color="red"><?php echo $index_user[0]['total_amount'];?></font></b></b></td>
									
									</tr>
									<tr bgcolor="#c2c2c2">
									<td height="1" colspan="2" scope="row" align="left" nowrap="nowrap"></td>
									
									</tr>
									<tr>
									<td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m hi&#7879;n t&#7841;i (Th&#225;ng <?php echo date('m/Y');?>):&nbsp;</b></td>
									<td><b><font color="red"><?php echo $index_user[0]['current_amount'];?></font></b></td>
									</tr>
									<tr>
									<td width="15%" scope="row" align="right" nowrap="nowrap"><b>S&#7889; &#273;i&#7875;m &#273;&#227; t&#237;ch l&#361;y:&nbsp;</b></td>
									<td><b><font color="red"><?php echo $index_user[0]['total_amount'];?></font></b></td>
									</tr>
									
									
								</table>-->
							</td>
						</tr>
						
					</table>
				</fieldset>
			</td></tr>
		</thead>
	</table>
	
	<?php } else if ($_SESSION['search'] == "user") { 
		echo '<!--';
		$index_user=$mysqlIns->get_cust_by_phone($cust_phone);
		echo '-->';
		
	?>
	<form id="updateCust" name="updateCust" method="POST" enctype="multipart/form-data">
		<input type="hidden" id="hid_act" name="hid_act" value="updateCust">
		<input type="hidden" id="hid_confirm" name="hid_confirm" value="0">
		<input type="hidden" id="cust_id" name="cust_id" value="<?php echo $index_user[0]["cust_id"]; ?>">
		<input type="hidden" id="cust_phone_old" name="cust_phone_old" value="<?php echo $index_user[0]["cust_phone"]; ?>">
		<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
			<thead>
				
				<tr><td colspan=3 width="100%">
					<fieldset><legend>Th&#244;ng tin kh&#225;ch h&#224;ng</legend>
						<table width="100%">
							<!--
								<tr>
								<td scope="row" align="right" width="99%"></td>
								<td scope="row" align="left"  width="1%" nowrap="nowrap">
								<img src="images/editblue.png" height=20>&nbsp;&nbsp;<img src="images/expand1.png" height=20>
								</td>
							</tr>-->
							<tr>
								<td width="20%" scope="row" align="right"><b>T&#234;n  kh&#225;ch h&#224;ng &nbsp;</b></td>
								<td width="80%" align="left"><label for="name"></label>
									<input name="cust_name" type="text" id="cust_name" size="40" placeholder="" style="border:1px solid #DADADA;" value="<?php echo isset($_POST['cust_name']) ? $_POST['cust_name'] : $index_user[0]["cust_name"]; ?>" />
									
								</td>
							</tr>
							<tr height="35">
								<td scope="row" align="right"><b>Gi&#7899;i t&#237;nh &nbsp;</b></td>
								<td width="80%" align="left"><label for="name"></label>
									<input type="radio" name="cust_sex" value="M" <?php if (isset($_POST['cust_sex'])) { if($_POST['cust_sex'] == 'M') echo 'checked'; } else if($index_user[0]['cust_sex'] == 'M') echo 'checked'; ?>>&nbsp;Nam&nbsp;&nbsp;&nbsp;
									<input type="radio" name="cust_sex" value="F" <?php if (isset($_POST['cust_sex'])) { if($_POST['cust_sex'] == 'F') echo 'checked'; } else if($index_user[0]['cust_sex'] == 'F') echo 'checked'; ?>>&nbsp;N&#7919;
									
									
								</td>
							</tr>
							<tr>
								<td scope="row" align="right"><b>N&#259;m sinh&nbsp;</b></td>
								<td width="80%" align="left"><label for="name"></label>
									<input name="cust_birth" type="text" id="cust_birth" size="10" style="border:1px solid #DADADA;" value="<?php echo isset($_POST['cust_birth']) ? $_POST['cust_birth'] : $index_user[0]["cust_birth_f"]; ?>" />
								</td>
							</tr>
							<tr>
								<td scope="row" align="right"><b>T&#234;n c&#244;ng ty&nbsp;</b></td>
								<td width="80%" align="left"><label for="name"></label>
									<input name="cust_company" type="text" id="cust_company" size="70" style="border:1px solid #DADADA;" value="<?php echo isset($_POST['cust_company']) ? $_POST['cust_company'] : $index_user[0]["cust_company"]; ?>" />
								</td>
							</tr>
							
							<tr>
								<td scope="row" align="right"><b>Địa chỉ c&#244;ng ty&nbsp;</b></td>
								<td width="80%" align="left"><label for="name"></label>
									<input name="cust_address" type="text" id="cust_address" size="100" style="border:1px solid #DADADA;" value="<?php echo isset($_POST['cust_address']) ? $_POST['cust_address'] : $index_user[0]["cust_address"]; ?>" />
								</td>
							</tr>
							<tr>
								<td scope="row" align="right"><b>Thư điện tử &nbsp;</b></td>
								<td scope="row" align="left">
									<input name="cust_email" type="text" id="cust_email" size="40" placeholder="" value="<?php echo isset($_POST['cust_email']) ? $_POST['cust_email'] : $index_user[0]["cust_email"]; ?>"/>
								</td>
								
							</tr>
							<tr>
								<td scope="row" align="right"><b>S&#7889; &#272;T kh&#225;ch h&#224;ng &nbsp;</b></td>
								<td scope="row" align="left">
									<input maxlength="15" name="cust_phone" onkeypress="return onlynumber(event);" type="text" id="cust_phone" size="20" placeholder="" value="<?php echo isset($_POST['cust_phone']) ? $_POST['cust_phone'] : $index_user[0]["cust_phone"]; ?>"/>
								</td>
								
							</tr>
							<tr>
								<td scope="row" align="right"><b>Th&#244;ng tin kh&#225;c &nbsp;</b></td>
								<td scope="row" align="left">
									<textarea name="cust_note" type="text" id="cust_note" cols="100" rows="6" style="border:1px solid #DADADA;" 
									><?php echo isset($_POST['cust_note']) ? $_POST['cust_note'] : $index_user[0]["cust_note"]; ?></textarea>
								</td>
								
							</tr>
							<tr>
								<th scope="row">&nbsp;</th>
								<td>
									<input type="button" name="btnUpdate" id="btnUpdate" value="Lưu thông tin" onclick="return validateUpdateCust();"/>
									<input type="button" name="btnback" id="btnback" value="Quay l&#7841;i" onclick="history.go(-1);"/>
								</td>
							</tr>
						</table>
					</fieldset>
				</td></tr>
				
				<tr height="22">
					<td align="left" valign="top" style="padding-top:5px;padding-bottom:5px">
						
						<table width="99%" cellspacing="0" cellpadding="0" class="tbl_shadow">
							<tr>
								<td width="90%"  >
									<table width="100%" cellspacing="0" cellpadding="0" >
										<tr height="25"> 
											<td colspan="3" bgcolor="#eeedfb" style="padding-left:9px;padding-right:6px;border-left: 1px solid #c2c2c2; border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;"><b>Công nợ</b></td>
										</tr>
										<tr height="25">
											
											<td align="left" bgcolor="#eeedfb" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Ng&#224;y tr&#7843; h&#224;ng</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>S&#7889; ti&#7873;n kh&#225;ch c&#242;n n&#7907;</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>N&#7907; l&#361;y k&#7871;</b>&nbsp;</td>
										</tr>
										
										<?php //echo 'AAAAA'.$cust_phone;
											$get_cust_debit=$mysqlIns->get_cust_debit($cust_phone);
											$totaldebit = 0;
											for($i=0;$i<count($get_cust_debit);$i++)
											{
												$totaldebit = $totaldebit + $get_cust_debit[$i]['trn_payment_remain'];
											?>
											<tr  height="25">
												
												<td align="left"  bgcolor="#fff" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b><?=$get_cust_debit[$i]['prg_step4_dt2']?></b>&nbsp;</td>
												<td align="right"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b><?=number_format($get_cust_debit[$i]['trn_payment_remain'], 0, '.', ',')?></b>&nbsp;</td>
												<td align="right"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b><?=number_format($totaldebit, 0, '.', ',')?></b>&nbsp;</td>
											</tr>
											<?php
											}
										?>
										
										<tr color="yellow"  height="30">
											<td align="left"  bgcolor="#fafae0" color="yellow" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b><font size="3">T&#7893;ng c&#242;n n&#7907;</b>&nbsp;</font></td>
											<td align="right"  bgcolor="#fafae0" colspan="2" color="yellow" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b><font size="3"><?=number_format($totaldebit, 0, '.', ',')?></b>&nbsp;</font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td  align="center" valign="top" style="padding-top:5px;padding-bottom:5px">
						<table width="99%" cellspacing="0" cellpadding="0" class="tbl_shadow">
							<tr>
								<td width="90%"  >
									<table width="100%" cellspacing="0" cellpadding="0" >
										<tr height="25">
											<td bgcolor="#eeedfb" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-top: 1px solid #c2c2c2;border-collapse:none;border-collapse:none;"><b>Ngày thanh toán Gộp</b></td>
											<td bgcolor="#eeedfb" style="padding-top:5px;padding-left:8px;padding-right:6px;padding-bottom:2px;border-left: 1px solid #c2c2c2; border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">
											<input type="text" maxlength="15" size="20" style="padding:1px;" id="payment_date" name="payment_date">
											</td>
										</tr>
										<tr height="25">
											<td align="left" bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Số tiền mặt</b>&nbsp;</td>
											<td align="left" bgcolor="#fff" nowrap  style="padding-left:2px;padding-right:6px;padding-top:1px;padding-bottom:1px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;
												<input type="text" maxlength="15" size="20" style="padding:1px" id="payment_tm" name="payment_tm" onfocus="replace_comas(this);" onblur=" return_comas(this);" onkeypress="return onlynumber1(event);">
											&nbsp;</td>
										</tr>
										<tr height="25">
											<td align="left" bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Số tiền chuyển khoản</b>&nbsp;</td>
											<td align="left" bgcolor="#fff" nowrap  style="padding-left:2px;padding-right:6px;padding-top:1px;padding-bottom:1px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">&nbsp;
												<input type="text" maxlength="15" size="20" style="padding:1px" id="payment_ck" name="payment_ck" onfocus="replace_comas(this);" onblur=" return_comas(this);" onkeypress="return onlynumber1(event);">
											&nbsp;</td>
										</tr>
										
										
										
										<tr color="yellow"  height="30">
											<td align="center"  bgcolor="#fafae0" colspan=2 color="yellow" nowrap style="padding-left:6px;padding-right:6px;padding-top:0px;padding-bottom:0px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;">
												<input type="button" name="btnPayment" id="btnPayment" value="Thanh toán" onclick="return validatePayment();"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td  align="right" valign="top" style="padding-top:5px;padding-bottom:5px">
						<div style="overflow-x:auto;">
						<table width="99%" cellspacing="0" cellpadding="0" class="tbl_shadow" >
							
							<tr>
								<td width="90%">
									<div style="height: 105px; overflow: auto; ">
									<table width="100%" cellspacing="0" cellpadding="0" >
									
										<tr height="25">
											<td colspan="5" bgcolor="#eeedfb" style="padding-left:9px;padding-right:6px;border-left: 1px solid #c2c2c2; border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;"><b>Lịch sử thanh toán</b></td>
										</tr>
										<tr height="25">
											
											<td align="left" bgcolor="#eeedfb" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Ngày thanh toán</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Tiền mặt</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Chuyển khoản</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Đơn hàng</b>&nbsp;</td>
											<td align="right" bgcolor="#eeedfb" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-top: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;">&nbsp;<b>Thừa</b>&nbsp;</td>
										</tr>
									
										<?php //echo 'AAAAA'.$cust_phone;
											$get_payment_pack=$mysqlIns->select_tbl_payment_pack($_GET["id"]);
											for($i=0;$i<count($get_payment_pack);$i++)
											{
												
											?>
											<tr  height="25">
												
												<td align="left"  valign="top" bgcolor="#fff" nowrap  style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><b><?=$get_payment_pack[$i]['pack_date']?></b></td>
												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><b><?=number_format($get_payment_pack[$i]['pack_payment_tm'], 0, '.', ',')?></b></td>
												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><b><?=number_format($get_payment_pack[$i]['pack_payment_ck'], 0, '.', ',')?></b></td>
												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;"><b><?=str_replace(' ','',str_replace('@','<br>',$get_payment_pack[$i]['pack_trn_ids']))?></b></td>
												<td align="right" valign="top"  bgcolor="#fff" nowrap style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-bottom: 1px solid #c2c2c2;border-collapse:none;border-right: 1px solid #c2c2c2;border-collapse:none;"><b><?=number_format($get_payment_pack[$i]['pack_over'], 0, '.', ',')?></b></td>
											</tr>
											<?php
											}
										?>
										
									
									</table>
									</div>
								</td>
							</tr>
						</table>
						</DIV>
					</td>
					<!--
						<td valign="bottom" align="left">
						<table width="100%">
						<tr>
						<td width="100%" bgcolor="#eeedfb" align="left">
						<table width="100%" align="left">
						<tr>
						<td align="left" >
						&nbsp;<b>Thanh to&#225;n th&#234;m:</b>&nbsp;
						&nbsp;<b><input type="text" id="trn_payment" name="trn_payment" size="15" onfocus="replace_comas(this);" onblur="return_comas(this);"></b>&nbsp;
						&nbsp;<b><input type="button" value="Thanh to&#225;n"></b>&nbsp;
						</td>
						</tr>
						</table>
						</td>
						</tr>
						</table>
					</td>-->
				</tr>
				
			</thead>
		</table>
	</form>
	<?php } else if ($_SESSION['search'] == "advance") {?>
	
	<form id="search" name="search"  method="GET">
		<input type="hidden" name="MM_update" value="search" />
		<input type="hidden" name="search" value="advance" />
		<table width="<?php echo $width; ?>%" cellspacing="5" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" bgcolor="#eeedfb">
			<td valign="top"><table width="100%">
				<tr>
					<td width="100%" scope="row" align="right"><img src="images/search3.png" height=35></td>
					
				</tr>
			</table></td>
			<td>
				
				<table width="<?php echo $width; ?>%">
					<thead>						
						<tr>
							<td scope="row" align="left" nowrap="nowrap"><b>Mã đơn hàng&nbsp;</b></td>
							<td align="left"><label for="name"></label>
								<input maxlength="20" name="trn_ref" type="text" id="trn_ref" value="<?php echo isset($_REQUEST['trn_ref'])? $_REQUEST['trn_ref']:""; ?>" size="10" style="border:1px solid #DADADA;"/>
							</td>	
							
							<td scope="row" align="left" nowrap="nowrap"><b>T&#234;n c&#244;ng ty&nbsp;</b></td>
							<td align="left"><label for="name"></label>
								<input name="cust_company" type="text" id="cust_company" value="<?php echo isset($_REQUEST['cust_company'])? $_REQUEST['cust_company']:""; ?>" size="30" style="border:1px solid #DADADA;"/>
							</td>
							
							<td scope="row" align="left" nowrap="nowrap"><b>T&#234;n  kh&#225;ch h&#224;ng &nbsp;</b></td>
							<td align="left"><label for="name"></label>
								<input maxlength="255" name="cust_name" type="text" id="cust_name" value="<?php echo isset($_REQUEST['cust_name'])? $_REQUEST['cust_name']:""; ?>" size="25" placeholder="" style="border:1px solid #DADADA;"/>
								
							</td>
						</tr>					
						
						<tr>
							<td scope="row" align="left"><b>Thư điện tử &nbsp;</b></td>
							<td scope="row" align="left">
								<input name="cust_email" type="text" value="<?php echo isset($_REQUEST['cust_email'])? $_REQUEST['cust_email']:""; ?>" id="cust_email" size="28" placeholder=""/>
							</td>
							<td scope="row" align="left"><b>Ng&#224;y giao h&#224;ng &nbsp;</b></td>
							<td scope="row" align="left">
								<input maxlength="10" onkeypress="return onlydate(event);" value="<?php echo isset($_REQUEST['trn_end_date'])? $_REQUEST['trn_end_date']:""; ?>" name="trn_end_date" type="text" id="trn_end_date" size="10" style="border:1px solid #DADADA;"/>
							</td>
							
							<td scope="row" align="left" nowrap="nowrap"><b>Tiêu đề&nbsp;</b></td>
							<td align="left"><label for="name"></label>
								<input name="trn_name" type="text" id="trn_name" value="<?php echo isset($_REQUEST['trn_name'])?$_REQUEST['trn_name']:""; ?>" size="30" style="border:1px solid #DADADA;"/>
							</td>
							
						</tr>
						
						<tr>
							<td scope="row" align="left" nowrap="nowrap"><b>S&#7889; &#272;T kh&#225;ch h&#224;ng &nbsp;</b></td>
							<td scope="row" align="left">
								<input maxlength="15" onkeypress="return onlynumber(event);" value="<?php echo isset($_REQUEST['cust_phone'])?$_REQUEST['cust_phone']:""; ?>" name="cust_phone" type="text" id="cust_phone" size="10" placeholder=""/>
							</td>
							
							<td scope="row" align="left"><b>Tr&#7841;ng th&#225;i &nbsp;</b></td>
							<td><label for="prg_status"></label>
								<?php if (!isset($_REQUEST['prg_status'])) $_REQUEST['prg_status'] = "";?>
								<select name="prg_status" id="prg_status" style="border:1px solid #DADADA;">
									<option value="" <?php if ($_REQUEST['prg_status'] == '') echo 'selected'; ?>>---Tất cả---</option>';
									<option value="12" <?php if ($_REQUEST['prg_status'] == '12') echo 'selected'; ?>>Chưa thiết kế</option>
									<option value="21" <?php if ($_REQUEST['prg_status'] == '21') echo 'selected'; ?>>Đang thiết kế</option>
									<option value="22" <?php if ($_REQUEST['prg_status'] == '22') echo 'selected'; ?>>Chưa duyệt thiết kế</option>
									<option value="23" <?php if ($_REQUEST['prg_status'] == '23') echo 'selected'; ?>>Chưa sản xuất</option>
									<option value="31" <?php if ($_REQUEST['prg_status'] == '31') echo 'selected'; ?>>Đang sản xuất</option>
									<option value="32" <?php if ($_REQUEST['prg_status'] == '32') echo 'selected'; ?>>Chưa giao hàng</option>
									<option value="42" <?php if ($_REQUEST['prg_status'] == '42') echo 'selected'; ?>>Đã giao hàng</option>
									<option value="52" <?php if ($_REQUEST['prg_status'] == '52') echo 'selected'; ?>>Đã chăm sóc</option>
								</select></td>
								<td scope="row" align="left" nowrap="nowrap"><b>Sản phẩm&nbsp;</b></td>
								<td align="left"><label for="name"></label>
									
									<select name="trn_prd_code" id="trn_prd_code" style="border:1px solid #DADADA;">
										<option value="">--Tất cả--</option>
										<?php
											echo "<!--";
											$tbl_product=$mysqlIns->select_tbl_product();
											echo "-->";
											for($i=0;$i<count($tbl_product);$i++)
											{
												if (isset($_REQUEST['trn_prd_code']) && ($_REQUEST['trn_prd_code'] == $tbl_product[$i]['prd_code'])) {
													echo '<option selected="selected" value="'.$tbl_product[$i]['prd_code'].'">'.$tbl_product[$i]['prd_name'].'</option>';
													} else {
													echo '<option value="'.$tbl_product[$i]['prd_code'].'">'.$tbl_product[$i]['prd_name'].'</option>';
												}
											}
										?>
									</select>
								</td>
						</tr>
						
						<tr>
							<th scope="row">&nbsp;</th>
							<td colspan=2><input onclick="if (event.which != 2) makeBlockUI();" type="submit" name="btnsearch" id="btnsearch" value="T&#236;m ki&#7871;m" />
							<input type="button" name="btnback" id="btnsua" value="Quay l&#7841;i" onclick="history.go(-1);"/></td>
						</tr>
						
					</thead>
				</table></td></tr>
	</table>
	</form>
	<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
		<thead>
			<tr height="6">
				
				<td width="10%" colspan="4" align="center" height="2"></td>
				
			</tr>
		</thead>
	</table>
<?php } else  {?><?php } ?>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
	<thead>		
		<?php if ($_SESSION['step'] == "CARE") { ?>
			<tr bgcolor="#eeedfb" height="22">
				<td align="left" >&nbsp;<b>M&#227;</b>&nbsp;</td>
						
				<td align="left" style="padding-left:2px;" colspan="2" nowrap="nowrap"><b>&#272;&#417;n h&#224;ng&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				<td align="left" style="padding-left:2px;" colspan="2" nowrap="nowrap"><b>T&#234;n KH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7843;n ph&#7849;m</b></td>
				<td id="td_trn_start_date" align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ngày Nh&#7853;p</b></td>
				
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ng&#224;y tr&#7843; h&#224;ng</b></td>
				
				<td align="right" style="padding-left:6px;" nowrap="nowrap"><b>Th&#224;nh ti&#7873;n</b></td>
				<td align="right" style="padding-left:6px;" nowrap="nowrap"><b>T&#7893;ng &#273;&#417;n h&#224;ng</b></td>
				<!--<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>TM</b></td>
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>CK</b></td>
				
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ng&#224;y thanh to&#225;n</b></td>-->
				<td align="right" style="padding-left:6px;" nowrap="nowrap"><b>&#272;&#227; thanh to&#225;n</b></td>
				<!--<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7889; ng&#224;y</b></td>-->
				<!--<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>C&#242;n n&#7907;</b></td>-->
				
				
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Kinh doanh</b></td>
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Tr&#7843; h&#224;ng</b></td>
				<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>&#272;&#225;nh gi&#225; c&#7911;a KH&nbsp;&nbsp;&nbsp;</b></td>
				
				<td align="left" style="padding-left:6px;padding-right:6px;" nowrap="nowrap" colspan=1><b>X&#7917; l&#253;</b></td>
			</tr>
		</thead>
		<tbody id="body_other">
			<?php
				$previousref= "";
				$displayref= "";
				$trn_total_pay = "";
				$trn_total_pay_tm = "";
				$trn_total_pay_ck = "";
				$trn_total_amount = "";
				$trn_payment_remain = "";
				$currentref = ' ';
				if (isset($index_chuaxuly)) {
					for($i=0;$i<count($index_chuaxuly);$i++)
					{
						$previousref=$currentref;
						$currentref = $index_chuaxuly[$i]["trn_ref"];
						$displayref=$index_chuaxuly[$i]["trn_ref"];
						$trn_total_pay=$index_chuaxuly[$i]["trn_total_pay"];
						$trn_total_pay_tm=$index_chuaxuly[$i]["trn_total_pay_tm"];
						$trn_total_pay_ck=$index_chuaxuly[$i]["trn_total_pay_ck"];
						
						$trn_total_amount=$index_chuaxuly[$i]["trn_total_amount"];
						
						echo '<!--';
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
							//$color = "#faf9a8"; // vang
						} elseif ($index_chuaxuly[$i]["prg_note"] != null)
						{
							//$color = "#a8d2fa"; // xanh
						} else {
							$color = "#ffffff"; // tráng
							if ($_SESSION['step'] == "DESIGN") {
								//if ($index_chuaxuly[$i]["prg_status"] == "21") $color = "#faf9a8"; // vang
								//if ($index_chuaxuly[$i]["prg_status"] == "22") $color = "#faf9a8"; // vang
								//if ($index_chuaxuly[$i]["prg_status"] == "23") $color = "#a8d2fa"; // xanh
							}
							if ($_SESSION['step'] == "BUILD") {
								//if ($index_chuaxuly[$i]["prg_status"] == "31") $color = "#faf9a8"; // vang
								//if ($index_chuaxuly[$i]["prg_status"] == "32") $color = "#a8d2fa"; // xanh
							}
							if ($_SESSION['step'] == "DELIVER") {
								//if ($index_chuaxuly[$i]["prg_status"] == "41") $color = "#faf9a8"; // vang
								//if ($index_chuaxuly[$i]["prg_status"] == "42") $color = "#a8d2fa"; // xanh
							}
						}
						
						$stt = $lastrow + $i+ 1;
						
						if (strlen($index_chuaxuly[$i]["prg_note"]) < 1)
							$note = 'Click to input';
						else
							$note = trim($index_chuaxuly[$i]["prg_note"]);
						echo '-->';
						if ($previousref != $currentref) {
							echo '<tr height="1" bgcolor="gray">
							<td width="100%" colspan="24" align="left" valign="middle">
							
							</td>';
							} else {
							$displayref = '';
							$trn_total_pay= '';
							$trn_total_pay_tm= '';
							$trn_total_pay_ck= '';
							$trn_total_amount='';
						}
						
						
						$trn_payment_remain = (int)$trn_total_amount - (int)$trn_total_pay;
						if ($trn_payment_remain == 0) $trn_payment_remain = "";
						
						
						if ($index_chuaxuly[$i]["prg_step4_dt2_f"] != null) {
							$prg_step4_dt2 = $index_chuaxuly[$i]["prg_step4_dt2_f"];
							} else {
							$prg_step4_dt2 = $index_chuaxuly[$i]["prg_status_f"];
						}
						$trn_quantity_f =  number_format($index_chuaxuly[$i]["trn_quantity"], 0, '.', ',');
						$trn_unit_price_f =  number_format($index_chuaxuly[$i]["trn_unit_price"], 0, '.', ',');
						$trn_amount_withoutVAT_f =  number_format($index_chuaxuly[$i]["trn_amount_withoutVAT"], 0, '.', ',');
						
						if ($index_chuaxuly[$i]["trn_vat"] == "0") {
							$trn_vat_f = "Kh&#244;ng";
							} else {
							$trn_vat_f = "C&#243;";
						}
						$trn_total_amount_f =  number_format((int)$trn_total_amount, 0, '.', ',');
						$trn_payment_remain_f =  number_format((int)$trn_payment_remain, 0, '.', ',');
						
						$trn_total_pay_f =  number_format((int)$trn_total_pay_tm, 0, '.', ',');
						$trn_total_pay_tm_f =  number_format((int)$trn_total_pay_tm, 0, '.', ',');
						$trn_total_pay_ck_f =  number_format((int)$trn_total_pay_ck, 0, '.', ',');
						//if ($index_chuaxuly[$i]["payment_lastest_0"] == "0") {
						//	$payment_lastest = number_format($index_chuaxuly[$i]["payment_lastest_1"], 0, '.', ',');
						//	} else {
						//	$payment_lastest = number_format(abs($index_chuaxuly[$i]["payment_lastest_0"] - $index_chuaxuly[$i]["payment_lastest_1"]), 0, '.', ',');
						//}
						
						$payment_all = number_format($index_chuaxuly[$i]["trn_payment_sum_tm"] + $index_chuaxuly[$i]["trn_payment_sum_ck"], 0, '.', ',');
						
						$payment_datef = substr($index_chuaxuly[$i]["trn_auth_date"],0,10);
						
						$total_all = $index_chuaxuly[$i]['trn_payment_all'];
						$trn_total_pay_f = number_format($total_all, 0, '.', ',');
						
						if ($previousref != $currentref) {
							} else {
							$trn_total_amount_f = "";
							$trn_payment_remain_f = "";
							$trn_total_pay_f = "";
							$trn_total_pay_tm_f = "";
							$trn_total_pay_ck_f = "";
							$payment_lastest = "";
							$payment_all = "";
							$payment_datef = "";
						}
						
						if (strpos($index_chuaxuly[$i]["grp_img_step1"],',') !== false) {
							//$grp_img_step1 = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_step1"].'"><img src="images/noicon1.png" width="16"></abbr>';
							$grp_img_step1 = '<img src="images/hatman.png" width="16">';
							} else {
							$grp_img_step1 = '<img src="'.$index_chuaxuly[$i]["grp_img_step1"].'" width="16">';
						}
						
						$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["grp_img_pending"].'" width="16">';
						if (strpos($index_chuaxuly[$i]["grp_img_pending"],',') !== false) {
							//$grp_img_pending = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_pending"].'"><img src="images/noicon1.png" width="16"></abbr>';
							if ($index_chuaxuly[$i]["prg_status"] >= 12 && $index_chuaxuly[$i]["prg_status"] < 23) {
								$grp_img_pending = '<img src="images/designer.png" width="16">';
								} elseif ($index_chuaxuly[$i]["prg_status"] >= 23 && $index_chuaxuly[$i]["prg_status"] < 32) {
								$grp_img_pending = '<img src="images/builder.png" width="16">';
								} elseif ($index_chuaxuly[$i]["prg_status"] >= 32 && $index_chuaxuly[$i]["prg_status"] < 41) {
								$grp_img_pending = '<img src="images/deliver.png" width="16">';
								} elseif ($index_chuaxuly[$i]["prg_status"] >= 42 && $index_chuaxuly[$i]["prg_status"] < 42) {
								$grp_img_pending = '';
							} 
							} else {
							$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["grp_img_pending"].'" width="16">';
						}		
						$payment_auth = "U";
						if ($index_chuaxuly[$i]["trn_payment_auth"] != '' && $index_chuaxuly[$i]["payment_status"] != '0') {
							$payment_auth = "<font color='#ccc'>A</font>";
						}
						
						if ($trn_total_amount == "") {
							$payment_auth = "&nbsp;&nbsp;";
						}
						
						
						echo '
						
						<input type="hidden" id="hid_trn_payment_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_total_pay.'">
						<input type="hidden" id="hid_trn_payment_type_tm_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_total_pay_tm.'">
						<input type="hidden" id="hid_trn_payment_type_ck_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_total_pay_ck.'">
						<input type="hidden" id="hid_trn_payment_remain_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$trn_payment_remain.'">
						<input type="hidden" id="hid_trn_ref_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_ref"].'">
						
						<tr height="22" bgcolor="'.$color.'">
						
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$displayref.'&nbsp;</td>
						<!--<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;<span id="trn_action_'.$index_chuaxuly[$i]["trn_id"].'">
						';
						//echo $index_chuaxuly[$i]["prg_step5_dt2"];
						if (($_SESSION['MM_Isadmin'] == 1 || strpos($_SESSION['MM_group'],'CARE,') !== false)
						&& ($trn_payment_remain != "")
						&& ($index_chuaxuly[$i]["prg_step4_dt2"] != null)
						)
						{
							if ($previousref != $currentref) {
								$prg_status = '42';
								echo '<b><a onclick="javascript:if (confirm(\'Bạn muốn update trạng thái đơn hàng ['.$index_chuaxuly[$i]["trn_ref"].'] thành đã thanh toán ?\')) { makeBlockUI(); } else { return false;}" href="?act='.$_REQUEST['act'].'&vw='.$vw.'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&ref='.$index_chuaxuly[$i]["trn_ref"].'&status='.$prg_status.'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/payment_complete.png" height="19"></a></b>';
							}
						}
						echo '</span>&nbsp;&nbsp;</td>-->';
						echo '<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><img src="images/viewinfo.png" height="14"></b></td>
						<td style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?mode=viewdetail&id='.$index_chuaxuly[$i]["trn_id"].'"class="login-window" id="trn_name_'.$index_chuaxuly[$i][0].'" >'.$index_chuaxuly[$i]["trn_name"].'</a></b></td>
						
						<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'"><img src="images/user.png"></a></b></td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'" class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["cust_id"].'" >'.$index_chuaxuly[$i]["cust_name"].'</b></td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prd_name"].'</td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_start_date_f"].'</td>
						';
						
						
						echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$prg_step4_dt2.'</td>
						';
						
						echo '<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span id="trn_sub_amount_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["trn_amount_f"].'</span></td>
						<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span id="trn_amount_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_total_amount_f.'</span></td>
						<!--<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span '.$styleEditCare.' onclick="setEdit(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_payment_type_tm_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_total_pay_tm_f.'</span></td>
						<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span '.$styleEditCare.' onclick="setEdit(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_payment_type_ck_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_total_pay_ck_f.'</span>';
						
						if ($_SESSION['MM_Isadmin'] == "1" || strpos($_SESSION['MM_group'],'CARE,') !== false){
							echo '&nbsp;<B><font size=4><span ';
							if ($payment_auth == 'U' && $trn_total_amount != "") {
								echo ' style="cursor: pointer;" onclick="updatePaymentAuth('.$index_chuaxuly[$i]["trn_id"].',\''.$trn_total_pay_f.'\')" ';
							}
							echo ' id="payment_auth_'.$index_chuaxuly[$i]["trn_id"];
							echo '">'.$payment_auth.'</span></font></b>';
						}
						
						echo '</td>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$payment_datef.'</td>-->';
						echo '<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$trn_total_pay_f.'</td>';
						
						echo '<!--<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span id="trn_payment_remain_'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_payment_remain_f.'</span></td>-->
						
						
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step1_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step1_by"].'" >'.$grp_img_step1.' '.$index_chuaxuly[$i]["prg_step1_by"].'</a></b></td>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step4_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step4_by"].'" >'.$grp_img_pending.' '.$index_chuaxuly[$i]["prg_step4_by"].'</a></b>&nbsp;</td>
						<td style=\"padding-left:6px;\"><span '.$styleEditCareNote.' onclick="setEditNote(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_note_'.$index_chuaxuly[$i]["trn_id"].'">'.$note.'</span></td>
						';
						
						//echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;<span id="trn_action_'.$index_chuaxuly[$i]["trn_id"].'">';
						//echo $index_chuaxuly[$i]["prg_step5_dt2"];
						/*if (($_SESSION['MM_Isadmin'] == "1")
							&& ($trn_payment_remain != "")
							&& ($index_chuaxuly[$i]["prg_step4_dt2"] != null)
							)
							{
							if ($previousref != $currentref) {
							$prg_status = '42';
							echo '<b><a onclick="javascript:if (confirm(\'Bạn muốn update trạng thái đơn hàng ['.$index_chuaxuly[$i]["trn_ref"].'] thành đã thanh toán ?\')) { makeBlockUI(); } else { return false;}" href="?act='.$_REQUEST['act'].'&vw='.$vw.'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&ref='.$index_chuaxuly[$i]["trn_ref"].'&status='.$prg_status.'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/payment_complete.png" height="19"></a></b>';
							}
							}
						echo '</span></td>';*/
						echo '<td nowrap=\"nowrap\"><span id="trn_action_'.$index_chuaxuly[$i]["trn_id"].'">';
						//echo $index_chuaxuly[$i]["prg_step5_dt2"];
						if (($_SESSION['MM_Isadmin'] == "1" || strpos($_SESSION['MM_group'],'CARE,') !== false)
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
				}
			?>
			<!--START PHAN TRANG-->
			<?php 
				if (!isset($_REQUEST['search'])) $_REQUEST['search'] = "";
				if ($_REQUEST['vw'] == 'complete' ||
				$_REQUEST['search'] == 'advance' ||
				$_REQUEST['search'] == 'user' ||
				$_REQUEST['search'] == 'staff' ||
				($_SESSION['step'] == 'CARE' && ($_REQUEST['vw']=='pending' || $_REQUEST['vw']=='wait'))
				) {
					if (isset($index_chuaxuly)) {
						if (count($index_chuaxuly) == 0) {
							$pages = 0;
						} else {
							$pages = $index_chuaxuly[0]['records'];
						}
						} else {
						$pages = 0;
					}
					
					$numpage = ceil($pages/$_SESSION['steprow']);
				?>
				
				<tr><td colspan=24 width="100%"><div id="content" height="1"></div></td></tr>
				<tr><td colspan=9 width="80%" bgcolor="#eeedfb"></td>
					<td colspan=15 align="right" valign="middle" nowrap="nowrap" bgcolor="#eeedfb" style="padding-left:0px;padding-right:10px;padding-top:5px;padding-top:4px;padding-bottom:4px">
						<font size=2><b><a href="<?=$_SESSION['req']?>&page=1">First</a>...
							
							<?php 
								for ($j = 1; $j <= $numpage; $j++) {
									if (($j >= $_page - 4) && $j <= $_page + 4) {
										if ($_page != $j) {
											echo "<a href='".$_SESSION['req']."&page=".$j."'>" .$j."</a>";
											} else {
											echo "<font color='#C8C8C8'>" .$j."</font>";
										}
										
										if ($j < $numpage) {
											echo " | ";
										}
									} 		
								}
								
							?> 
							... <a href="<?=$_SESSION['req']?>&page=<?=$numpage?>">Last</a>
							&nbsp;&nbsp;&nbsp;
							<select name="current_page" id="current_page" onchange="changepage();">
								<?php 
									for ($j = 1; $j <= $numpage; $j++) {
										if ($_page == $j) {
											echo '<option value="'.$j.'" selected>'.$j.'</option>';
											} else {
											echo '<option value="'.$j.'">'.$j.'</option>';	
										}
									}
								?>
							</select>
							
						/ <?=$numpage?> </b></font>		</td></tr>
			<?php } ?>
			<!-- END PHAN TRANG -->
			
			<?php } else { ?>
			<tr bgcolor="#eeedfb" height="22">
				<td align="left" >&nbsp;<b>M&#227;</b>&nbsp;</td>
				
				<td id="td_trn_name" align="left" style="padding-left:2px;min-width:160" colspan="2" nowrap="nowrap" onclick="submit_sort_col('trn_name',$('#imgsrc_trn_name').attr('title'));"><b>&#272;&#417;n h&#224;ng <span id='imgsrc_trn_name'></span>&nbsp;</b></td>
				<td id="td_cust_name" align="left" style="padding-left:2px;" colspan="2" nowrap="nowrap" onclick="submit_sort_col('cust_name',$('#imgsrc_cust_name').attr('title'));"><b>T&#234;n KH <span id='imgsrc_cust_name'></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				<td id="td_prd_name" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prd_name',$('#imgsrc_prd_name').attr('title'));"><b>S&#7843;n ph&#7849;m</b> <span id='imgsrc_prd_name'></span></td>
				<td id="td_trn_amount" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('trn_amount',$('#imgsrc_trn_amount').attr('title'));"><b>Tổng tiền</b> <span id='imgsrc_trn_amount'></span></td>
				<td id="td_trn_payment" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('trn_payment',$('#imgsrc_trn_payment').attr('title'));"><b>Đã thanh toán</b> <span id='imgsrc_trn_payment'></span></td>
				<td id="td_trn_start_date" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('trn_start_date',$('#imgsrc_trn_start_date').attr('title'));"><b>Ngày Nh&#7853;p</b> <span id='imgsrc_trn_start_date'></span></td>
				<td id="td_trn_end_date" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('trn_end_date, trn_during',$('#imgsrc_trn_end_date').attr('title'));"><b>Ngày h&#7865;n</b> <span id='imgsrc_trn_end_date'></span></td>
				<td id="td_prg_step2_dt3" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_step2_dt3',$('#imgsrc_prg_step2_dt3').attr('title'));"><b>Ngày duyệt TK</b> <span id='imgsrc_prg_step2_dt3'></span></td>
				<!--<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7889; ng&#224;y</b></td>-->
				<td id="td_trn_order" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('trn_order',$('#imgsrc_trn_order').attr('title'));"><b>Ng&#224;y c&#242;n</b> <span id='imgsrc_trn_order'></td>
					<td id="td_prg_step1_by" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_step1_by',$('#imgsrc_prg_step1_by').attr('title'));"><b>Kinh doanh</b> <span id='imgsrc_prg_step1_by'></span></td>
					<?php if ($_SESSION['step'] != "DELIVER") { ?>
						<td id="td_prg_pending_by" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_pending_by',$('#imgsrc_prg_pending_by').attr('title'));"><b>Tr&#225;ch nhi&#7879;m</b> <span id='imgsrc_prg_pending_by'></span></td>
					<?php }?>
					<?php if ($_SESSION['step'] != "DELIVER") { ?>
						<td id="td_prg_status1" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_status',$('#imgsrc_prg_status1').attr('title'));"><b>Ti&#7871;n &#273;&#7897;</b> <span id='imgsrc_prg_status1'></span></td>
					<?php }?>
					
					<td id="td_prg_status" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_status',$('#imgsrc_prg_status').attr('title'));"><b>Tr&#7841;ng th&#225;i</b> <span id='imgsrc_prg_status'></span></td>
					<?php if ($_SESSION['step'] == "DELIVER") { ?>
						<td id="td_prg_note" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_note',$('#imgsrc_prg_note').attr('title'));"><b>Chú ý</b> <span id='imgsrc_prg_note'></span></td>
					<?php }?>
					<?php if ($_SESSION['step'] != "DELIVER") { ?>
						<td id="td_prg_pending_from_dt" align="left" style="padding-left:6px;" nowrap="nowrap" onclick="submit_sort_col('prg_pending_from_dt',$('#imgsrc_prg_pending_from_dt').attr('title'));"><b>Update cu&#7889;i</b> <span id='imgsrc_prg_pending_from_dt'></span></td>
					<?php }?>
					
					<td align="left" style="padding-left:6px;" nowrap="nowrap" colspan=3><b>X&#7917; l&#253;&nbsp;</b></td>
				</tr>
			</thead>
			<tbody  id="body_other">
				<?php
					$previousref= "";
					$currentref = ' ';
					$displayref= "";
					for($i=0;$i<count($index_chuaxuly);$i++)
					{
						$previousref=$currentref;
						$currentref = $index_chuaxuly[$i]["trn_ref"];
						$displayref=$index_chuaxuly[$i]["trn_ref"];
						echo '<!--';
						$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
						$persentage = "10";
						$status = "Ch&#7901; thi&#7871;t k&#7871;";
						$saler = "ManhPD";
						$pending = "GiangTT";
						$iconsaler = "images/hatman.png";
						$iconpending = "images/designer.png";
						$xuly = "";
						
						if ($_SESSION['step'] == "DESIGN") {
							//if ($index_chuaxuly[$i]["prg_status"] == "21") $color = "#faf9a8"; // vang
							//if ($index_chuaxuly[$i]["prg_status"] == "22") $color = "#faf9a8"; // vang
							//if ($index_chuaxuly[$i]["prg_status"] == "23") $color = "#a8d2fa"; // xanh
						}
						if ($_SESSION['step'] == "BUILD") {
							//if ($index_chuaxuly[$i]["prg_status"] == "31") $color = "#faf9a8"; // vang
							//if ($index_chuaxuly[$i]["prg_status"] == "32") $color = "#a8d2fa"; // xanh
						}
						if ($_SESSION['step'] == "DELIVER") {
							//if ($index_chuaxuly[$i]["prg_status"] == "41") $color = "#faf9a8"; // vang
							//if ($index_chuaxuly[$i]["prg_status"] == "42") $color = "#a8d2fa"; // xanh
						}
						
						if ($index_chuaxuly[$i]["prg_status"] > 41)
						{
							$color = "#a8d2fa"; // xanh
							//} else if ($index_chuaxuly[$i]["trn_end_date"] != "") {
							} else  {
							if ($index_chuaxuly[$i]["today_duration"] <= $trans_fixdate_red_max && 
							$index_chuaxuly[$i]["today_duration"] >= $trans_fixdate_red_min &&
							$index_chuaxuly[$i]["today_duration"] != '(?)') {
								$color = "#fab4af"; // do
							} else if ($index_chuaxuly[$i]["today_duration"] <= $trans_fixdate_yellow_max &&
							$index_chuaxuly[$i]["today_duration"] >= $trans_fixdate_yellow_min  &&
							$index_chuaxuly[$i]["today_duration"] != '(?)'
							) 
							{
								$color = "#faf9a8"; // vang
							}
							//} else {
							
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
						
						//Anh dai dien
						/*if (strpos($index_chuaxuly[$i]["grp_img_step1"],',') !== false) {
							//$grp_img_step1 = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_step1"].'"><img src="images/noicon1.png" width="16"></abbr>';
							$grp_img_step1 = '<img src="images/hatman.png" width="16">';
							} else {
							$grp_img_step1 = '<img src="'.$index_chuaxuly[$i]["grp_img_step1"].'" width="16">';
							}
							if (strpos($index_chuaxuly[$i]["grp_img_pending"],',') !== false) {
							//$grp_img_pending = '<abbr rel="tooltip" title="'.$index_chuaxuly[$i]["grp_code_pending"].'"><img src="images/noicon1.png" width="16"></abbr>';
							if ($index_chuaxuly[$i]["prg_status"] >= 12 && $index_chuaxuly[$i]["prg_status"] < 23) {
							$grp_img_pending = '<img src="images/designer.png" width="16">';
							} elseif ($index_chuaxuly[$i]["prg_status"] >= 23 && $index_chuaxuly[$i]["prg_status"] < 32) {
							$grp_img_pending = '<img src="images/builder.png" width="16">';
							} elseif ($index_chuaxuly[$i]["prg_status"] >= 32 && $index_chuaxuly[$i]["prg_status"] < 41) {
							$grp_img_pending = '<img src="images/deliver.png" width="16">';
							} elseif ($index_chuaxuly[$i]["prg_status"] >= 42 && $index_chuaxuly[$i]["prg_status"] < 42) {
							$grp_img_pending = '';
							} 
							} else {
							$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["grp_img_pending"].'" width="16">';
						}	*/
						$grp_img_step1 = '<img src="'.$index_chuaxuly[$i]["user_img_step1"].'" width="16">';
						$grp_img_pending = '<img src="'.$index_chuaxuly[$i]["user_img"].'" width="16">';
						
						echo '-->';
						if ($previousref != $currentref) {
							echo '<tr height="1" bgcolor="gray">
							<td width="100%" colspan="18" align="left" valign="middle">
							
							</td>';
						} else {
							$displayref = '';
						}
						
						echo '
						<tr id="tr_'.$index_chuaxuly[$i]["trn_id"].'" height="22" bgcolor="'.$color.'" 
						
						>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$displayref.'&nbsp;</td>
						<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><img src="images/viewinfo.png" height="14"></b></td>
						<td 
						onmouseover="
						$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
						$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
						"
						onmouseout="
						$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
						
						"
						
						style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?mode=viewdetail&id='.$index_chuaxuly[$i]["trn_id"].'"class="login-window" id="trn_name_'.$index_chuaxuly[$i][0].'" >'.$index_chuaxuly[$i]["trn_name"].'</a></b></td>
						
						<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'"><img src="images/user.png"></a></b></td>
						<td align="left" style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$index_chuaxuly[$i]["trn_cust_phone"].'"class="login-window" id="cust_name_'.$index_chuaxuly[$i]["trn_id"].'" >'.$index_chuaxuly[$i]["cust_name"].'<!--nguoigoiden--></a></b></td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prd_name"].'<!--tencongtrinh--></a></td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_amount"].'<!--tencongtrinh--></a></td>
						<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_payment"].'<!--tencongtrinh--></a></td>
						
						
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["trn_start_date_f"].'</td>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span '.$styleEditEndate.' onclick="setEditEndDate(this,\''.$index_chuaxuly[$i]["trn_id"].'\')" id="trn_end_date_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["trn_end_date_f"].'</span>
						&nbsp;<span class="dialog_block_group" title="'.$index_chuaxuly[$i]["trn_name"].'" id="trn_end_date_his_'.$index_chuaxuly[$i]["trn_id"].'" value="'.$index_chuaxuly[$i]["trn_id"].'">'.$trn_end_date_his.'</span>
						</td>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_step2_dt3_f"].'</td>
						
						
						<td nowrap=\"nowrap\" align="right" style=\"padding-left:6px;padding-right:15px;\">&nbsp;&nbsp;<span id="date_duration_'.$index_chuaxuly[$i]["trn_id"].'">'.$index_chuaxuly[$i]["today_duration"].'</span></td>
						<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_step1_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_step1_by"].'" >'.$grp_img_step1.' '.$index_chuaxuly[$i]["prg_step1_by"].'</a></b></td>
						';
						if ($_SESSION['step'] != "DELIVER") { 
							echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$index_chuaxuly[$i]["prg_pending_by"].'"class="login-window" id="congtrinh-'.$index_chuaxuly[$i]["prg_pending_by"].'" >'.$grp_img_pending.' '.$index_chuaxuly[$i]["prg_pending_by"].'</a></b></td>
							';
						}
						
						if ($_SESSION['step'] != "DELIVER") { 
							echo '<td nowrap=\"nowrap\" width="70"><div class="progress">
							<span style="width: '.$index_chuaxuly[$i]["prg_percent_f"].'%;"><span>'.$index_chuaxuly[$i]["prg_percent_f"].'%</span></span>
							</div></td>';
						}
						
						echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$index_chuaxuly[$i]["prg_status_f"].'</td>';
						if ($_SESSION['step'] == "DELIVER") { 
							echo '<td style=\"padding-left:6px;\">&nbsp;&nbsp;'.$errImg.$index_chuaxuly[$i]["prg_note"].'</td>';
						}
						if ($_SESSION['step'] != "DELIVER") { 
							echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$errImg.$index_chuaxuly[$i]["prg_pending_from_dt_f"].'</td>';
						}
						
						if ($ishowAction==1)
						{
							echo '<td id="icon_complete_'.$index_chuaxuly[$i][0].'" nowrap=\"nowrap\" style=\"padding-left:6px;\" >';
							echo '<b><a onmouseover="
							$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\'));
							$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#E0F8E0\');
							$(\'#icon_complete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'#DF0101\')
							
							" 
							onmouseout="
							$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',$(\'#tr_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'old_bgcolor\'))
							$(\'#icon_complete_'.$index_chuaxuly[$i]["trn_id"].'\').attr(\'bgcolor\',\'\')" onclick="return makeconfirm(\''.$index_chuaxuly[$i]["prg_status"].'\',\''.$index_chuaxuly[$i]["trn_id"].'\',\''.$index_chuaxuly[$i]["trn_ref"].'\',\''.$index_chuaxuly[$i]["trn_name"].'\',\''.$index_chuaxuly[$i]["prg_action_f"].'\')" href="'.$_SERVER['REQUEST_URI'].'&do=xuly&id='.$index_chuaxuly[$i]["trn_id"].'&status=41" class="login-window" id="congtrinh-'.$index_chuaxuly[$i][0].'" ><img src="images/complete.png" height="14"></a></b>';
							echo '</td>';
							if ($index_chuaxuly[$i]["prg_status"] >= 31  && ($_SESSION['MM_Isadmin'] == 1 ||
							strpos($_SESSION['MM_group'],'BUILD,') !== false ||
							strpos($_SESSION['MM_group'],'DELIVER,') !== false ||
							strpos($_SESSION['MM_group'],'CARE,') !== false)) {
								echo '<td id="icon_error_'.$index_chuaxuly[$i][0].'" nowrap=\"nowrap\" style=\"padding-left:6px;\">';
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
								echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;</td>';
							}
							
							} else {
							echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\"></td>';
							if ($index_chuaxuly[$i]["prg_status"] > 31 && ($_SESSION['MM_Isadmin'] == 1 ||
							strpos($_SESSION['MM_group'],'BUILD,') !== false ||
							strpos($_SESSION['MM_group'],'DELIVER,') !== false ||
							strpos($_SESSION['MM_group'],'CARE,') !== false)) {
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
								echo '<td nowrap="nowrap" style="padding-left:6px;" colspan="1">&nbsp;</td>';
							}
						}		
						
						
						if (isset($_SESSION['MM_Isadmin']) && $_SESSION['MM_Isadmin'] == 1) {
							echo '<td id="icon_delete_'.$index_chuaxuly[$i][0].'" nowrap=\"nowrap\">
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
							echo '<td nowrap=\"nowrap\" style=\"padding-left:6px;\" >&nbsp;</td>';
						}
						
						echo '</tr>';
						
					}
					
					echo '<tr><td colspan=18 width="100%"><div id="content" height="1"></div></td></tr>';  
				?>
				
				<!--START PHAN TRANG-->
				<?php if ($_REQUEST['vw'] == 'complete' ||
					$_REQUEST['search'] == 'advance' ||
					$_REQUEST['search'] == 'user' ||
					$_REQUEST['search'] == 'staff' ||
					$_REQUEST['search'] == 'saledetail'
					) {
						if (count($index_chuaxuly) == 0) {
							$pages = 0;
						} else {
							$pages = $index_chuaxuly[0]['records'];
						}
						$numpage = ceil($pages/$_SESSION['steprow']);
					?>
					
					<tr><td colspan=18 width="100%"><div id="content" height="1"></div></td></tr>
					<tr><td colspan=10 width="80%" bgcolor="#eeedfb"></td>
						<td colspan=8 align="right" valign="middle" nowrap="nowrap" bgcolor="#eeedfb" style="padding-left:0px;padding-right:10px;padding-top:5px;padding-top:4px;padding-bottom:4px">
							<font size=2><b><a href="<?=$_SESSION['req']?>&page=1">First</a>...
								
								<?php 
									for ($j = 1; $j <= $numpage; $j++) {
										if (($j >= $_page - 4) && $j <= $_page + 4) {
											if ($_page != $j) {
												echo "<a href='".$_SESSION['req']."&page=".$j."'>" .$j."</a>";
												} else {
												echo "<font color='#C8C8C8'>" .$j."</font>";
											}
											
											if ($j < $numpage) {
												echo " | ";
											}
										} 		
									}
									
								?> 
								... <a href="<?=$_SESSION['req']?>&page=<?=$numpage?>">Last</a>
								&nbsp;&nbsp;&nbsp;
								<select name="current_page" id="current_page" onchange="changepage();">
									<?php 
										for ($j = 1; $j <= $numpage; $j++) {
											if ($_page == $j) {
												echo '<option value="'.$j.'" selected>'.$j.'</option>';
												} else {
												echo '<option value="'.$j.'">'.$j.'</option>';	
											}
										}
									?>
								</select>
								
							/ <?=$numpage?> </b></font>		</td></tr>
				<?php }?>
				<!-- END PHAN TRANG -->
			<?php }?>
			
		</tbody>
		<input type="hidden" id="hid_lastnumrow" value="<?php echo count($index_chuaxuly); ?>">
	</table>
	<!--
		<div id="rowload" height="1">
		<?php if ($vw == 'complete' || $_REQUEST['search'] == 'advance') { ?>
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
		<?php } ?> 
		</div>
	-->	 
	<script type="text/javascript">
		
		
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
		
		
		<?php if ($vw == 'complete') { ?>
			checkmore(1);
		<?php } ?> 
		<?php if ($_REQUEST['search'] == 'advance' && ($_POST["MM_update"] == "")) { ?>
			checkmore(2);
		<?php } ?> 
		<?php if ($_REQUEST['search'] == 'advance' && ($_POST["MM_update"] != "")) { ?>
			checkmore(1);
		<?php } ?> 
		function backsearch() {
			makeBlockUI();
			document.location = "?search=nomal";
		}
		
		function setEdit(obj,id) {
			if ($('#' + obj.id + '_text').val() != null) {
				return false;
			}
			//alert('222');
			<?php if ($_SESSION['MM_Isadmin'] != 1 && !strpos($_SESSION['MM_group'],'CARE,') !== false) echo 'return false;' ?>
			//alert('sdas');
			thisval = $('#hid_' + obj.id).val().replace(/,/g,'');
			$('#' + obj.id).html('<input onkeypress="return keyDebit(event,\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" onblur="updateDebitNone(\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'8\' maxlength="10">');
			$('#' + obj.id+'_text').focus();
			$('#' + obj.id+'_text').select();
		}
		
		function keyDebit(e,thisval,objid,id) {
			if(e.keyCode == 13) {
				updateDebit(thisval,objid,id);
				return true;
			}
			if(e.keyCode == 27) {
				$('#' + obj.id).html(thisval);
				return true;
			}
			
			return onlydate(e);
		}
		
		function updateDebitNone(thisval, objid,id) {
			//editval = $('#' + objid + '_text').val();
			editval_tm = "0";
			editval_ck = "0";
			
			
			
			if ($('#' + "trn_payment_type_tm_" + id + '_text').length > 0) {
				editval_tm = $('#' + "trn_payment_type_tm_" + id + '_text').val();
				editval_ck = $('#' + "trn_payment_type_ck_" + id).html().replace(/,/g,'');
				
			}
			
			if ($('#' + "trn_payment_type_ck_" + id + '_text').length > 0) {
				editval_ck = $('#' + "trn_payment_type_ck_" + id + '_text').val();
				editval_tm = $('#' + "trn_payment_type_tm_" + id).html().replace(/,/g,'');
				
			}
			
			
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);

			$('#trn_payment_type_tm_' + id).html($('#hid_trn_payment_type_tm_' + id).val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
			$('#trn_payment_type_ck_' + id).html($('#hid_trn_payment_type_ck_' + id).val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
		}
		
		function updateDebit(thisval, objid,id) {
			//editval = $('#' + objid + '_text').val();
			editval_tm = "0";
			editval_ck = "0";
			
			if ($('#' + "trn_payment_type_tm_" + id + '_text').length > 0) {
				editval_tm = $('#' + "trn_payment_type_tm_" + id + '_text').val();
				editval_ck = $('#' + "trn_payment_type_ck_" + id).html().replace(/,/g,'');
				if (thisval == editval_tm) {
					$('#' + objid).html(thisval);
					return false;
				}
			}
			
			if ($('#' + "trn_payment_type_ck_" + id + '_text').length > 0) {
				editval_ck = $('#' + "trn_payment_type_ck_" + id + '_text').val();
				editval_tm = $('#' + "trn_payment_type_tm_" + id).html().replace(/,/g,'');
				if (thisval == editval_ck) {
					$('#' + objid).html(thisval);
					return false;
				}
			}
			
			//alert(objid);
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);
			
			editval = eval(editval_tm) + eval(editval_ck);
			editval_ = eval(editval_tm) + eval(editval_ck);
			
			if (objid == "trn_payment_type_tm_" + id) {
				editval_remain = parseInt($('#trn_amount_' + id).html().replace(/,/g,'')) - parseInt(editval_);
				} else if (objid == "trn_payment_type_ck_" + id) {
				editval_remain = parseInt($('#trn_amount_' + id).html().replace(/,/g,'')) - parseInt(editval_);
				} else {
				editval_remain = editval_;
				editval_ = parseInt($('#trn_amount_' + id).html().replace(/,/g,'')) - parseInt(editval_);
			}
			//alert(editval_ + '-' + editval_remain);
			
			var p_request = 'trn_id=' + id + 
			'&trn_ref=' + $('#hid_trn_ref_' + id).val() + 
			'&editval=' + editval + 
			'&editval_tm=' + editval_tm + 
			'&editval_ck=' + editval_ck + 
			'&objid=' + objid;
			
			//alert(p_request);
			//window.location = "http://localhost/invietdung/json_upd_payment.php?" + p_request;
			$.ajax({
				url : 'json_upd_payment.php',
				data :  p_request,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(thisval +"@" +editval_tm);
					if (thisval != editval_tm) {
						$('#trn_payment_type_tm_' + id).html(editval_tm.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						$('#hid_trn_payment_type_tm_' + id).val(editval_tm.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					}
					if (thisval != editval_ck) {
						$('#trn_payment_type_ck_' + id).html(editval_ck.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						$('#hid_trn_payment_type_ck_' + id).val(editval_ck.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					}
					
					$('#trn_payment_remain_' + id).html(editval_remain.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					$('#hid_trn_payment_remain_' + id).val(editval_remain.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					//$('#' +objid+ '_hid_' + id).val(editval);
					$('#trn_action_' + id).html('<img src="images/refresh.png" height="18">');
					$('#payment_auth_' + id).html('A');
					//alert($('#trn_action_' + id).html());
				}
			});
			
			
			
		}
		
		
		
		
		
		function setEditNote(obj,id) {
			if ($('#' + obj.id + '_text').val() != null) {
				return false;
			}
			thisval = $('#' + obj.id).html();
			if (thisval == 'Click to input') thisval = '';
			$('#' + obj.id).html('<input onkeypress="return keyNote(event,\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" onblur="updateNote(\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'15\' maxlength=\'10\'>');
			$('#' + obj.id+'_text').focus();
			$('#' + obj.id+'_text').select();
		}
		
		function keyNote(e,thisval,objid,id) {
			if(e.keyCode == 13) {
				updateNote(thisval,objid,id);
			}
			if(e.keyCode == 27) {
				$('#' + obj.id).html(thisval);
			}
		}
		
		function updateNote(thisval,objid,id) {
			editval = $('#' + objid + '_text').val();
			if (thisval == editval) {
				$('#' + objid).html(thisval);
				return false;
			}
			
			//alert(objid);
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);
			//alert(objid);
			$.ajax({
				url : 'json_upd_payment.php',
				data :  'trn_id=' + id + 
				'&editval=' + editval + 
				'&objid=' + objid,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					if (editval == '') {
						$('#trn_note_' + id).html('Click to input');
						} else {
						$('#trn_note_' + id).html(editval);
					}
					$('#trn_action_' + id).html('<img src="images/refresh.png" height="18">');
				}
			});
			
			
			
		}
		
		
		function setEditEndDate(obj,id) {
			if ($('#' + obj.id + '_text').val() != null) {
				return false;
			}
			<?php if ($_SESSION['MM_Isadmin'] != 1 && !(strpos($_SESSION['MM_group'],'DESIGN,') !== false)) echo 'return false;' ?>
			thisval = $('#' + obj.id).html().replace(/\+/g, "");
			//$('#' + obj.id).html('<input onkeypress="return keyDate(event,\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" onblur="updateEndDate(\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'8\' class="datepicker" maxlength="10">');
			$('#' + obj.id).html('<input onkeypress="return keyDate(event,\''+thisval+'\',\''+obj.id+'\',\''+id+'\');" type=\'text\' id=\''+obj.id+'_text\' value=\''+thisval+'\' size=\'8\' class="datepicker" maxlength="10">');
			$('#' + obj.id+'_text').focus();
			$('#' + obj.id+'_text').select();
		}
		
		function keyDate(e,thisval,objid,id) {
			if(e.keyCode == 13) {
				updateEndDate(thisval,objid,id);
				return true;
			}
			//alert(e.keyCode);
			if(e.keyCode == 27) {
				$('#' + obj.id).html(thisval);
				return true;
			}
			
			return onlydate(e);
		}
		
		
		
		function updateEndDate(thisval,objid,id) {
			editval = $('#' + objid + '_text').val();
			if (thisval == editval) {
				$('#' + objid).html(thisval);
				return false;
			}
			
			
			//alert(objid);
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#' + objid).html(loading);
			//alert(editval.replace('+',''));
			//alert('trn_id=' + id + '&editval=' + editval.replace(/\+/g, "") + '&objid=' + objid);
			$.ajax({
				url : 'json_upd_payment.php',
				data :  'trn_id=' + id + 
				'&editval=' + editval.replace(/\+/g, "") + 
				'&objid=' + objid,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(editval);
					if (editval == '') {
						$('#trn_end_date_' + id).html('Click to input');
						} else {
						$('#trn_end_date_' + id).html(editval);
					}
					$('#trn_action_' + id).html('<img src="images/refresh.png" height="18">');
					if (editval.length == 10) {
						$('#date_duration_' + id).html(result.split("@")[1]);
						} else {
						$('#trn_end_date_' + id).html('+' + editval.replace(/\+/g, ""));
						
					}
				}
			});
		}
		
		function makeconfirm(trn_status,trn_id, trn_ref, trn_name, trn_next) {
			
			if (confirm('Bạn muốn update trạng thái đơn hàng '+trn_name+' thành '+trn_next+' ?')) { 
				makeBlockUI(); 
				} else { 
				return false;
			}
			
			if (trn_status == '31')
			$.ajax({
				url : 'sendmail.php?trn_id=' + trn_id,
				data :  '',
				type : 'get',
				dataType : '',
				success : function (result)
				{
					
				}
			});
		}
		
		function loadmore()
		{
			return false;
			var cust_name='';
			var cust_company='';
			var cust_email='';
			var cust_phone='';
			var trn_end_date='';
			var prg_status='';
			var trn_ref='';
			var trn_name='';
			var trn_prd_code='';
			
			if ($('#cust_name').val() != null) cust_name = $('#cust_name').val();
			if ($('#cust_company').val() != null) cust_company = $('#cust_company').val();
			if ($('#cust_email').val() != null) cust_email = $('#cust_email').val();
			if ($('#cust_phone').val() != null) cust_phone = $('#cust_phone').val();
			if ($('#trn_end_date').val() != null) trn_end_date = $('#trn_end_date').val();
			if ($('#prg_status').val() != null) prg_status = $('#prg_status').val();
			if ($('#trn_ref').val() != null) trn_ref = $('#trn_ref').val();
			if ($('#trn_name').val() != null) trn_name = $('#trn_name').val();
			if ($('#trn_prd_code').val() != null) trn_prd_code = $('#trn_prd_code').val();
			
			var ischeck = '0';
			<?php if($_REQUEST["search"]=='advance') { ?>
				ischeck = '3';
			<?php }	?>
			
			$('#readmore_img').html('<img src=images/loadingjson.gif height=20>');
			var parm = 'vw=<?php echo $vw; ?>' +
			'&lastrow=' + $('#hid_lastnumrow').val() +
			'&cust_name=' + cust_name +
			'&cust_company=' + cust_company +
			'&cust_email=' + cust_email +
			'&cust_phone=' + cust_phone +
			'&trn_end_date=' + trn_end_date +
			'&prg_status=' + prg_status +
			'&trn_ref=' + trn_ref +
			'&trn_name=' + trn_name +
			'&trn_prd_code=' + trn_prd_code +
			'&ischeck=' + ischeck;
			//alert(parm);
			$.ajax({
				url : 'json_load_tran.php',
				data :  parm,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(result);
					$('#body_other').html($('#body_other').html() + result);
					$('#readmore_img').html('<img src=images/readmore.png height=20>');
					
					lastrow = parseInt($('#hid_lastnumrow').val()) + parseInt('<?php echo $_SESSION['steprow']; ?>');
					$('#hid_lastnumrow').val(lastrow);
					//alert($('#hid_lastnumrow').val());
					checkmore(1);
				}
			});
		}
		
		function checkmore(ischeck)
		{
			return false;
			var cust_name='';
			var cust_company='';
			var cust_email='';
			var cust_phone='';
			var trn_end_date='';
			var prg_status='';
			var trn_ref='';
			var trn_name='';
			var trn_prd_code='';
			
			if ($('#cust_name').val() != null) cust_name = $('#cust_name').val();
			if ($('#cust_company').val() != null) cust_company = $('#cust_company').val();
			if ($('#cust_email').val() != null) cust_email = $('#cust_email').val();
			if ($('#cust_phone').val() != null) cust_phone = $('#cust_phone').val();
			if ($('#trn_end_date').val() != null) trn_end_date = $('#trn_end_date').val();
			if ($('#prg_status').val() != null) prg_status = $('#prg_status').val();
			if ($('#trn_ref').val() != null) trn_ref = $('#trn_ref').val();
			if ($('#trn_name').val() != null) trn_name = $('#trn_name').val();
			if ($('#trn_prd_code').val() != null) trn_prd_code = $('#trn_prd_code').val();
			
			$('#readmore_img').html('<img src=images/loadingjson.gif height=20>');
			//alert(ischeck);
			
			<?php if($_REQUEST["search"]=='advance') { ?>
				if (ischeck != 2) ischeck = '4';
			<?php }	?>
			
			$.ajax({
				url : 'json_load_tran.php',
				data :  'vw=<?php echo $vw; ?>&lastrow=' + $('#hid_lastnumrow').val() +
				'&cust_name=' + cust_name +
				'&cust_company=' + cust_company +
				'&cust_email=' + cust_email +
				'&cust_phone=' + cust_phone +
				'&trn_end_date=' + trn_end_date +
				'&prg_status=' + prg_status +
				'&trn_ref=' + trn_ref +
				'&trn_name=' + trn_name +
				'&trn_prd_code=' + trn_prd_code +
				'&ischeck=' + ischeck,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					//alert(result);
					$('#rowload').html(result);
					//$('#rowload').html('');
					$('#readmore_img').html('<img src=images/readmore.png height=20>');
					//lastrow = parseInt($('#hid_lastnumrow').val()) + parseInt('<?php echo $_SESSION['steprow']; ?>');
					//$('#hid_lastnumrow').val(lastrow);
					//alert($('#hid_lastnumrow').val());
					
				}
			});
		}
		
		function validateUpdateCust() {
			makeBlockUI();
			document.getElementById("hid_act").value = "updateCust";
			document.getElementById("updateCust").submit();
			
		}
		
		function validatePayment() {
			makeBlockUI();
			document.getElementById("hid_act").value = "paymentForm";
			document.getElementById("updateCust").submit();
			
		}
		
		function changepage() {
			makeBlockUI();
			window.location='<?=$_SESSION['req']?>&page=' + $('#current_page').val();
		}
		
		function updatePaymentAuth(trn_id,editval) {
			//alert($('#payment_auth_'+ trn_id).html());
			if ($('#payment_auth_'+ trn_id).html() == "<font color=\"#ccc\">A</font>") return;
			
			loading = '<img src="images/loadingjson.gif" height="20">';
			$('#payment_auth_'+ trn_id).html(loading);
			var param = 'trn_id=' + trn_id + 
			'&editval=' + editval
			$.ajax({
				url : 'json_upd_payment_auth.php',
				data : param,
				type : 'get',
				dataType : '',
				success : function (result)
				{
					$('#payment_auth_'+ trn_id).html("<font color='#ccc'>A</font>");
					$('#payment_auth_'+ trn_id).attr("style","");
				}
			});
		}
	</script>																														