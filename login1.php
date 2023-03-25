<?php ob_start();
	require_once('./global.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php?attemp=1";
  $MM_redirecttoReferrer = false;
  mysql_select_db($dbhost, $db);
  
  $LoginRS__query=sprintf("SELECT a.*,
								 (select GROUP_CONCAT(tmp.urg_grp_code SEPARATOR ',')  from (select t1.urg_grp_code
								  from tbl_user_group t1, tbl_group t2 
								  where t1.urg_grp_code = t2.grp_code
								  and t1.urg_user_name = %s
								  order by t2.grp_order asc) tmp) user_grp_code,
								 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from tbl_group t1
								  where t1.grp_code in (select t2.urg_grp_code 
														from tbl_user_group t2 
														where UCASE(t2.urg_user_name) = UCASE(a.user_name))
								  order by t1.grp_order asc
								  ) grp_img
						   FROM `tbl_user` a
						   WHERE a.user_name=%s AND a.user_pass=%s ",
    GetSQLValueString($loginUsername, "text"),
	GetSQLValueString($loginUsername, "text"),	
	GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $db) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $loginfetchUser = mysql_fetch_array($LoginRS,MYSQL_ASSOC);
  if ($loginFoundUser > 0) {
     $loginStrGroup = "";
    if ($loginfetchUser["user_stat"] == 'O' && $loginfetchUser["user_grp_code"] != "") {
		if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
		//declare two session variables and assign them
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_UserGroup'] = $loginStrGroup;
		$_SESSION['MM_Isadmin'] = $loginfetchUser["user_isadmin"];
		$_SESSION['MM_group'] = $loginfetchUser["user_grp_code"].',';
		if (strpos($loginfetchUser["grp_img"],',') !== false) {
			$_SESSION['MM_img'] = 'images/noicon1.png';
		} else {
			$_SESSION['MM_img'] = $loginfetchUser["grp_img"];
		}
		
		$MM_group = explode(',',$_SESSION['MM_group']);
		$_SESSION['step'] = $MM_group[0];
		//echo $_SESSION['MM_Isadmin'];
		//echo $loginfetchUser["user_grp_code"];
		if (isset($_SESSION['PrevUrl']) && false) {
		  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
		}
		
		echo "<script language=\"javascript\"> window.location='".$MM_redirectLoginSuccess."?vw=pending"."'</script>";
	} else {
		echo '$( document ).ready(function() {
			//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');
			$.growl.error({ message: "Tài khoản của bạn đang bị tạm khóa" });
		});';
		//echo "<script language=\"javascript\">alert('!');</script>";
	}
	//ob_start();
    //header("Location: " . $MM_redirectLoginSuccess."?vw=pending" );
	//exit;
  }
  else {
	
	ob_start();
    header("Location: ". $MM_redirectLoginFailed );
	exit;
  }
}
?>
<?php if (!isset($_SESSION['MM_Username']) || $_SESSION['MM_Username'] =="" || $_SESSION['MM_Username'] == null) { ?>
<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>
        Hệ thống quản lý sản xuất
    </title>
	
<link rel="stylesheet" type="text/css" href="lib/css/styles.css" />
<link rel="stylesheet" type="text/css" href="lib/css/dialog.css" />

<script type="text/javascript" src="lib/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="lib/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="lib/js/jquery.blockUI.min.js"></script>

    <style>
	.button {
	border-top : solid 1px #d5d5d5;
	border-right : solid 1px #808080;
	border-bottom : solid 1px #808080;
	border-left : solid 1px #d5d5d5;
	color : #333;
	font-weight : bold;
	}
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
		
		.tbl_shadow {
 -moz-box-shadow: 3px 3px 5px #b9b9b9;
  -webkit-box-shadow: 3px 3px 5px #b9b9b9;       
  box-shadow: 3px 3px 5px #b9b9b9;
   
}



* {margin: 0; padding: 0;}


body {
font-family: "Lucida Grande","Lucida Sans Unicode", Tahoma, Sans-Serif;
/*font-size:	13px;*/
}

.button {
  display: inline-block;
  height: 30px;
  line-height: 30px;
  
  padding-left: 10px;
  padding-right: 10px;
  position: relative;

  text-decoration: none;
  
  letter-spacing: 1px;
  margin-bottom: 10px;
  
  
  border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  

  -moz-box-shadow:0px 2px 2px rgba(0,0,0,0.2);
  -webkit-box-shadow:0px 2px 2px rgba(0,0,0,0.2);
  box-shadow:0px 2px 2px rgba(0,0,0,0.2);

}

.button span {
  position: absolute;
  left: 0;
  width: 30px;
  padding-left: 5px;
}

.button:hover span, .button.active span {
 
}

.button:active {
  margin-top: 2px;
  margin-bottom: 13px;

  -moz-box-shadow:0px 1px 0px rgba(255,255,255,0.5);
-webkit-box-shadow:0px 1px 0px rgba(255,255,255,0.5);
box-shadow:0px 1px 0px rgba(255,255,255,0.5);

}

.button.orange {
  background: #FF7F00;
}

.button.purple {
  background: #8e44ad;
}

.button.turquoise {
  background: #1abc9c;
}

.button.red {
  background: #e74c3c;
}

.settingfocusx {
    border: 2px solid #AA88FF;
    background-color: #9f9b9b;

	
	
}

input#username{
background-image:url(images/icon-user-name1.png);
background-repeat:no-repeat;
background-position:6px;
border:1px solid #DADADA;

padding-left:30px;
width:150px;
height:25px;
}

input#password{
background-image:url(images/passwordtiny.png);
background-repeat:no-repeat;
background-position:6px;
border:1px solid #DADADA;

padding-left:30px;
width:150px;
height:25px;
}
    </style>
	

	<script type="text/javascript" src="lib/js/jquery.growl.js"></script>
	<link rel="stylesheet" href="lib/css/jquery.growl.css" media="screen" />
	
</head>


<body background="images/bgdot.png">
<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>
<!--
<tr width="100%" height="1" bgcolor="#eeeeee">
<td colspan="1" style="padding-left:25px;padding-top:5px;" width="1%"><img src="images/logoc.png" height="40"></td>
<td colspan="1" style="padding-left:25px;"><font color="black" size=4><b>Phần mềm quản lý theo đơn đặt hàng</b></font>
</td>

</tr>-->
<tr width="100%" height="1" bgcolor="#ed1c24"><td colspan="2">
</td>

</tr>


<td width="60%" height="100%" valign="top" colspan=2>
<form id="loginform" name="loginform" method="POST" action="login.php" onsubmit="makeBlockUI();">

<table border="0" cellpadding="0" cellspacing="0" width="100%" >

<tr width="100%" height="1"><td colspan="2">
</td>

</tr>


<tr valign="top" >
	<td valign="top" colspan="2">
<table border="0" width="100%" valign="top" background="images/bgdot.png">
		<tbody>
		<tr height="120" width="100%" ><td colspan="2">
</td>

</tr>
		<tr height="90">

			<td valign=top width="60%" align="center" height="1">
			




<table width="32%" bgcolor="#ffffff" cellspacing="0" cellpadding="6" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">

<tr height="38" bgcolor="#a8d2fa">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;padding-top:5px;">
		<img src="images/loginicon.png" height="31">
		
		</td>
		<td width="99%" colspan=2 align="left" valign="middle" style="padding-left:1px;"><b><font color="black" size=3>Đăng nhập</font></b>
		
		</td>
		
	</tr>
<tr width="100%" height="1" bgcolor="#ed1c24"><td colspan="3">
</td>

</tr>
<tr width="100%" height="10"><td colspan="3">
</td>

</tr>

	<tr height="25" bgcolor="#ffffff">
		<td style="padding-left:25px;" width="35%" colspan=2 nowrap="nowrap"><b><font size="2">Tên đăng nhập</font></b></td>
		<td style="padding-left:20px;padding-right:90px;"><input onkeypress="checkenter(event);" name="username" id="username" type="text" placeholder="Username" /></td>
	</tr>
	<tr height="25" bgcolor="#ffffff">
		<td style="padding-left:25px;" colspan=2 nowrap="nowrap"><b><font size="2">Mật khẩu</font></b></td>
		<td style="padding-left:20px;padding-right:90px;"><input onkeypress="checkenter(event);" name="password" id="password" height="20" type="password"  placeholder="Password" /></td>
	</tr>
	
	<tr width="100%" height="10"><td colspan="3">
</td>

</tr>
	<tr bgcolor="#eeeeee">
		<td></td>
		<td colspan=2 style="padding-left:16px;padding-top:10px;">
		<div class="container">
<div id="divCheckbox" style="visibility: hidden; display:inline;"><input type="submit" value="submit"></div>
<a href="javascript:" onclick="javascript: dosubmit();" class="button" ><font size="2">Đăng nhập</font></a>
</div>
		</td>
	</tr>

</table>

		</td>

</tr>
</table>
</td></tr></table>
</form>

</tr>
</table>
	<html>
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
		
		function dosubmit() {
			//alert('sdfsdf'); 
			//document.getElementById("saveTran").action = "";
			//makeBlockUI();
			document.getElementById("loginform").submit();
		}
		
		function checkenter(e) {
			if (e.keyCode == 13) {
				document.getElementById("loginform").submit();
			}
		}
		
		<?php if (isset($_GET['attemp']) && $_GET['attemp'] == 1) { ?>
				$( document ).ready(function() {
					//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');
					$.growl.error({ message: "Sai username hoặc password!" });
				});
		<?php } ?>
</script>
<?php } ?><?php mysql_close($db); ?>