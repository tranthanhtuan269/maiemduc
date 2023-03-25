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
  
  $LoginRS__query=sprintf("SELECT a.*,b.* FROM `tbl_user` a, tbl_group b WHERE a.user_grp_code = b.grp_code and a.user_name=%s AND a.user_pass=%s and a.user_stat='O'",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $db) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $loginfetchUser = mysql_fetch_array($LoginRS,MYSQL_ASSOC);
  if ($loginFoundUser > 0) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Isadmin'] = $loginfetchUser["user_isadmin"];
	$_SESSION['MM_group'] = $loginfetchUser["user_grp_code"];
	$_SESSION['MM_img'] = $loginfetchUser["grp_img"];
	$_SESSION['step'] = $_SESSION['MM_group'];
	//echo $_SESSION['MM_Isadmin'];
	//echo $_SESSION['step'];
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	
	ob_start();
    header("Location: " . $MM_redirectLoginSuccess."?vw=pending" );
	exit;
  }
  else {
	
	ob_start();
    header("Location: ". $MM_redirectLoginFailed );
	exit;
  }
}
?>


<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>
        Hệ thống quản lý bán hàng
    </title>
	
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/dialog.css" />

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
  
-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#ff123852,Positive=true)";zoom:1;
filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#ff123852,Positive=true);

  -moz-box-shadow:0px 2px 2px rgba(0,0,0,0.2);
  -webkit-box-shadow:0px 2px 2px rgba(0,0,0,0.2);
  box-shadow:0px 2px 2px rgba(0,0,0,0.2);
  -ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=2,Color=#33000000,Positive=true)";
filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=2,Color=#33000000,Positive=true);
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
-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#ccffffff,Positive=true)";
filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#ccffffff,Positive=true);
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

.tbl_shadowheader {

  box-shadow: 0px 2px 1px 0px #b9b9b9;
   
}
    </style>
	
</head>


<body background="images/bgdot.png">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl_shadow"><tr>

<tr width="100%" height="50" bgcolor="#ed1c24">
<td colspan="1" style="padding-left:25px;padding-top:5px;" width="1%"><img src="images/logo.png" height="40"></td>
<td colspan="1" style="padding-left:25px;"><font color="white" size=4><b>Phần mềm quản lý theo đơn đặt hàng</b></font>
</td>

</tr>

</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr width="100%" height="50" >
<td colspan="1" style="padding-left:25px;padding-top:5px;" width="1%"></td>


</tr>
<tr>
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
		<tr height="120" width="100%" ><td colspan="2"><img src="saleman.png" height=300>
</td>

</tr>
		<tr height="90">

			<td valign=top width="60%" align="center" height="1">
			




<table width="35%" bgcolor="#ffffff" cellspacing="0" cellpadding="6" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">

<tr height="44" bgcolor="#a8d2fa">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;padding-top:5px;">
		<img src="images/loginicon.png" height="35">
		
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
		<td style="padding-left:10px;"><input name="username" id="username" type="text" placeholder="Username" /></td>
	</tr>
	<tr height="25" bgcolor="#ffffff">
		<td style="padding-left:25px;" colspan=2 nowrap="nowrap"><b><font size="2">Mật khẩu</font></b></td>
		<td style="padding-left:10px;"><input name="password" id="password" height="20" type="password"  placeholder="Password" /></td>
	</tr>
	
	<tr width="100%" height="10"><td colspan="3">
</td>

</tr>
	<tr bgcolor="#eeeeee">
		<td></td>
		<td colspan=2 style="padding-left:50px;padding-top:10px;">
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
</td>

<!--
<td bgcolor="#eeeeee" width="40%" rowspan="5" height="680" valign="top" >

<table width="100%" bgcolor="#ffffff" cellspacing="0" cellpadding="6" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">

<tr height="44" bgcolor="#a8d2fa">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		
		
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>User test</font></b>
		
		</td>
		
	</tr>

</table><br>
<table width="100%"  cellspacing="0" cellpadding="50" style="border: 1px solid #c2c2c2; border-collapse:none;" >
<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>User</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>Password</font></b>
		</td>
	</tr>
	<tr height="1" bgcolor="#ed1c24">
		<td width="100%"  align="left" valign="middle" style="padding-left:15px;" colspan=8>
		</td>
	</tr>
	
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>sale1</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>sale1</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>sale2</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>sale2</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>design1</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>design1</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>design2</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>design2</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>build1</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>build1</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>build2</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>build2</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>deliver1</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>deliver1</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>deliver2</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>deliver2</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>care1</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>care1</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>care2</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>care2</font></b>
		</td>
	</tr>
	<tr height="25">
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="10" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>admin</font></b>
		</td>
		<td width="1%"  align="left" valign="middle" style="padding-left:15px;">
		</td>
		<td width="100%" colspan=2 align="left" valign="middle" style="padding-left:15px;"><b><font color="black" size=3>admin</font></b>
		</td>
	</tr>
</table>
</td>-->
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
		
		<?php if ($_GET['attemp'] == 1) { ?>
			$( document ).ready(function() {
				alert('Sai Tên đăng nhập hoặc mật khẩu, vui lòng đăng nhập lại!'); $('#username').focus();
			});
		<?php } ?>
</script>
<?php mysql_close($db); ?>