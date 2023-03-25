<?php ob_start();

	require_once('./global.php'); ?>

<?php

if (!isset($_SESSION)) {

  session_start();

}



$_SESSION['urlworkcode'] = "";

$splitarr = explode("/","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$_SESSION['urlpage'] = "tbl";





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



$isshow = "1";



if (isset($_POST['username'])) {

  $loginUsername=strtoupper($_POST['username']);

  $password=$_POST['password'];

  $MM_fldUserAuthorization = "";

  $MM_redirectLoginSuccess = "index.php";

  $MM_redirectLoginFailed = "login.php?attemp=1";

  $MM_redirecttoReferrer = false;

  mysql_select_db($dbhost, $db);

  

  $LoginRS__query=sprintf("SELECT a.user_isadmin,

								  a.user_stat,

								 (select GROUP_CONCAT(tmp.urg_grp_code SEPARATOR ',')  from (select t1.urg_grp_code

								  from ".$_SESSION['urlpage']."_user_group t1, ".$_SESSION['urlpage']."_group t2 

								  where t1.urg_grp_code = t2.grp_code

								  and t1.urg_user_name = %s

								  order by t2.grp_order asc) tmp) user_grp_code,

								 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from ".$_SESSION['urlpage']."_group t1

								  where t1.grp_code in (select t2.urg_grp_code 

														from ".$_SESSION['urlpage']."_user_group t2 

														where UCASE(t2.urg_user_name) = UCASE(a.user_name))

								  order by t1.grp_order asc

								  ) grp_img

						   FROM `".$_SESSION['urlpage']."_user` a

						   WHERE a.user_name=%s AND a.user_pass=%s 

						   ",

    GetSQLValueString($loginUsername, "text"),

	GetSQLValueString($loginUsername, "text"),	

	GetSQLValueString($password, "text"),

	GetSQLValueString($loginUsername, "text"),

	GetSQLValueString($password, "text"),

	GetSQLValueString(strtoupper($_SESSION['urlpage']), "text")

	); 



	//echo $LoginRS__query;

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

			$_SESSION['MM_img'] = $_SESSION['urlworkcode'].'images/noicon1.png';

		} else {

			$_SESSION['MM_img'] = $_SESSION['urlworkcode'].$loginfetchUser["grp_img"];

		}

		

		$MM_group = explode(',',$_SESSION['MM_group']);

		$_SESSION['step'] = $MM_group[0];

		//echo $_SESSION['MM_Isadmin'];

		//echo $loginfetchUser["user_grp_code"];

		if (isset($_SESSION['PrevUrl']) && false) {

		  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	

		}

		

		echo "<script language=\"javascript\"> window.location='".$MM_redirectLoginSuccess."?vw=pending"."'</script>";

		$isshow = "0";

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

	

	//ob_start();

    //header("Location: ". $MM_redirectLoginFailed );

	//exit;

  }

}



if ($isshow == "1") {

?>

<!DOCTYPE html>



<html lang='en'>

<head>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">  

    <meta charset="UTF-8" /> 

    <title>

        Hệ thống quản lý sản xuất

    </title>

	

<link rel="stylesheet" type="text/css" href="<?=$_SESSION['urlworkcode']?>css/style.css" />

<link rel="stylesheet" type="text/css" href="<?=$_SESSION['urlworkcode']?>css/dialog.css" />



<script type="text/javascript" src="<?=$_SESSION['urlworkcode']?>lib/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript" src="<?=$_SESSION['urlworkcode']?>lib/js/jquery.blockUI.js"></script>

<script type="text/javascript" src="<?=$_SESSION['urlworkcode']?>lib/js/jquery.blockUI.min.js"></script>



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

background-image:url(<?=$_SESSION['urlworkcode']?>images/icon-user-name1.png);

background-repeat:no-repeat;

background-position:6px;

border:1px solid #DADADA;



padding-left:30px;

width:200px;

height:25px;
}



input#password{

background-image:url(<?=$_SESSION['urlworkcode']?>images/passwordtiny.png);

background-repeat:no-repeat;

background-position:6px;

border:1px solid #DADADA;



padding-left:30px;

width:200px;

height:25px;

}





.tbl_shadowheader {



  box-shadow: 0px 2px 1px 0px #b9b9b9;

   

}

    </style>

	



	<script type="text/javascript" src="<?=$_SESSION['urlworkcode']?>lib/js/jquery.growl.js"></script>

	<link rel="stylesheet" href="<?=$_SESSION['urlworkcode']?>lib/css/jquery.growl.css" media="screen" />

	

</head>





<body background="<?=$_SESSION['urlworkcode']?>images/bgdot.png">

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl_shadowheader"><tr>



<tr width="100%" height="25" bgcolor="#ed1c24">



<td colspan="1" valign="middle" style="padding-left:25px;padding-top:5px;" width="10%"><img src="<?=$_SESSION['urlworkcode']?>images/logo_2711.png" height="25">

</td>

<td colspan="1" valign="middle" align="left" style="padding-left:25px;padding-top:5px;" width="60%"><!--<font color="white" size=3"><b>Phần mềm quản lý sản xuất</b></font>-->

</td>

<td colspan="1" valign="middle" align="right" style="padding-left:25px;padding-top:5px;" width="60%">

</td>

</tr>



</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>

<tr width="100%" height="50" >

<td colspan="1" style="padding-left:0px;padding-top:5px;" width="1%" align="center">

	

</td>





<td width="100%" height="100%" valign="top" align="center" colspan=2>

<form id="loginform" name="loginform" method="POST" action="login.php" onsubmit="makeBlockUI();">



<table border="0" cellpadding="0" cellspacing="0" width="280" >



<tr width="100%" height="1"><td colspan="2">

</td>



</tr>





<tr valign="top" >

	<td valign="top" colspan="2">

<table border="0" width="100%" valign="top" background="<?=$_SESSION['urlworkcode']?>images/bgdot.png">

		<tbody>

		<tr height="30" width="100%" ><td colspan="2" align="center">
		

	

</td>



</tr>

		<tr height="90">



			<td valign=top width="100%" align="center" height="1">

			









<table style="max-width:100%;"  bgcolor="#ffffff" cellspacing="0" cellpadding="6" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">



<tr height="38" bgcolor="#a8d2fa">

		<td width="1%"  align="left" valign="middle" style="padding-left:15px;padding-top:5px;">

		<img src="<?=$_SESSION['urlworkcode']?>images/loginicon.png" height="31">

		

		</td>

		<td width="99%" colspan=2 align="left" valign="middle" style="padding-left:10px;"><b><font color="black" size=3>Đăng nhập</font></b>

		

		</td>

		

	</tr>

<tr width="100%" height="1" bgcolor="#ed1c24"><td colspan="3">

</td>



</tr>

<tr width="100%" height="10"><td colspan="3">

</td>



</tr>



	<tr height="25" bgcolor="#ffffff" style="max-width:100%;"><td style="padding-left:15px;padding-right:0px;padding-top:5px;" colspan="3">

		<p><b><font size="2">Tên đăng nhập</font></b></p>

		<p><input onkeypress="checkenter(event);" name="username" id="username" type="text" placeholder="Username"/></p>

	</td></tr>

	<tr height="25" bgcolor="#ffffff" style="max-width:100%;"><td style="padding-left:15px;padding-right:0px;padding-top:5px;" colspan="3">

		<p><b><font size="2">Mật khẩu</font></b></p>

		<p><input onkeypress="checkenter(event);" name="password" id="password" height="20" type="password"  placeholder="Password"/></p>

	</td></tr>

	

	<tr width="100%" height="10"><td colspan="3">

</td>



</tr>

	<tr bgcolor="#eeeeee" style="max-width:100%;">

		<td></td>

		<td colspan=2 style="padding-left:5px;padding-right:0px;padding-top:10px;">

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

</form></td>



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

			makeBlockUI();

			document.getElementById("loginform").submit();

		}

		

		function checkenter(e) {

			if (e.keyCode == 13) {

				document.getElementById("loginform").submit();

			}

		}

		

		<?php if ($_GET['attemp'] == 1) { ?>

				$( document ).ready(function() {

					//ohSnap(\'Oh Snap! I cannot process your card...\', \'red\');

					$.growl.error({ message: "Sai username hoặc password!" });

				});

		<?php } ?>

</script>

<?php } ?>

