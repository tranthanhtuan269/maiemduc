<?php require_once('./global.php'); ?>
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

$grp=$_REQUEST['grp'];
//echo 'dddddddddddddd'.$_POST['_cb'];
$cb=$_POST['_cb'];
if ($cb != "")
{	
	$cbArr=explode(",", $cb);
	$delquery = " delete from tbl_grp_action where UCASE(gat_grp)=UCASE('".$grp."')";
	$del = mysql_query($delquery, $db) or die(mysql_error());

	foreach ($cbArr as $key => $value) {
		//$checkExist = " SELECT * from quyenhan where quyenhan.nhom_quyenhan = '".$value."' and admin_user='".$user."'";
		//$exist = mysql_query($checkExist, $db) or die(mysql_error());
		//$num_rows = mysql_num_rows($exist);
		//if ($num_rows == 0)
		//{
			$exe = " insert into tbl_grp_action(gat_act_code,gat_grp ,gat_stat) values('".$value."',UCASE('".$grp."'),'O')";
			mysql_query($exe, $db) or die(mysql_error());
		//}
	}
}


mysql_select_db($dbname, $db);
$query_danh_sach_menu = "   SELECT a.* , b.gat_stat
							FROM tbl_action a
							LEFT JOIN tbl_grp_action b ON b.gat_act_code = a.act_code 
							AND UCASE(b.gat_grp) = UCASE('".$grp."')
                         ";
$danh_sach_menu = mysql_query($query_danh_sach_menu, $db) or die(mysql_error());
$row_danh_sach_menu = mysql_fetch_assoc($danh_sach_menu);
$totalrow_danh_sach_menu = mysql_num_rows($danh_sach_menu);


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Welcome to Administrator system</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="lib/css/niceforms-default.css" />

<style>
	body {
	
	font-family: "Lucida Grande","Lucida Sans Unicode", Tahoma, Sans-Serif;
	font-size:	2px;
	}
</style>
	<script type="text/javascript" src="lib/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="lib/js/jquery-ui/jquery-ui.js"></script>
	<link type="text/css" rel="stylesheet" href="lib/js/jquery-ui/jquery-ui.css" />
	
    <style>
        input:focus { background: #FFE4C4; color: black; }
        select:focus { background: #FFE4C4; color: black; }
        input.radio:focus { background: #FFE4C4; color: black; }
        input.textarea:focus { background: #FFE4C4; color: black; }
        
        input {color: black;}
        select {color: black;}
        textarea {color: black;}
        .is-disabled {
            background-color: #F8F8FF;
            color: black !important;   
        }
    </style>
    <script type="text/javascript">
        function makeBlockUI() {
            $.blockUI({ css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
            });
        }
		
		function checksubmit()
		{
			var selected = [];
			$("input[type=checkbox]:checked").each(function(){
				
				selected.push($(this).attr('value'));
			});
			//alert(selected);
			$("#_cb").val(selected);
			//alert($("#_cb").val());
			//return false;
		}
    </script>
</head>

<body >
<form id="form1" name="form1" method="post" action="admin_permission.php?grp=<?php echo $grp ?>">
<input type="hidden" id="_cb" name="_cb"/>

		
		<table width="100%" align="center" cellpadding="0" cellspacing="0" style="20px #888888;">
<tr>
		<td colspan="1" valign="bottom" width=1>
		<img border="0" src="images/usericon1.png" height="30" align="middle"></td>
		<td width="100%" colspan="1" valign="bottom" align="left">
		<b>&nbsp;<font color="#800000" size="3">Thay đổi quyền hạn cho nh&#243;m: </font><font size="3" color="red"><?php echo $grp; ?></font></b></td></tr>
		
<tr height="10">
		<td width="3%" colspan="2" align="center">
		
		</td>
		
	</tr>
		
		<tr><td colspan=2>
  <table align="center" cellspacing="5" cellpadding="3" rules="all" border="0" id="Table4" 
  style="width:100%;border: 1px solid #000000; border-collapse:none;">
  <thead>
    <tr bgcolor="#eeedfb" height="20">
		<td width="10%" >
		<p align="center"><b><font size="2"> Chọn</font></b></td>
		<td width="20%">
		<p align="left"><b><font size="2">Mã quyền</font></b></td>
		<td width="50%"><b>
		<font size="2">Tên quyền</font></td>
		
		
		
		
		
		
	</tr>
	</thead>
	<tbody >
    <?php do { 

	?>
	
	<tr bgcolor="#ffffff">
		<td width="10%">
		<p align="center"><input type="checkbox" id="cb" name="cb" value="<?php echo $row_danh_sach_menu['act_code']; ?>" <?php if ($row_danh_sach_menu['gat_stat'] == "O") echo "checked"?>/></td>
		
		<td width="3%" >
		<p align="left"><font size="2"> <?php echo $row_danh_sach_menu['act_code']; ?></font></td>
		<td width="15%">
		<font size="2"> <?php echo $row_danh_sach_menu['act_name']; ?></font></td>
		
		
		
		
		
	</tr>
	
      <?php } while ($row_danh_sach_menu = mysql_fetch_assoc($danh_sach_menu)); ?>
	  </tbody>
  </table>
  </td>
  </tr>
  <tr height="10">
		<td width="3%" colspan="3" align="center">
		
		</td>
		
	</tr>
  <tr height="31">
		<td width="3%" colspan="3" align="center">
		<input type="submit" value=" L&#432;u thay đổi " onclick="return checksubmit();"></input>
		</td>
		
	</tr>
  </table>
  <br/>
  
  <p/>
</form>
<?php mysql_close($db); ?>
