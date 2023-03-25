


		<table  width="100%" height="10" >


<tr>


	<td valign="top">


	   	<div id="cpanel">


				<div style="float: left;" >


				<div class="icon" >


				<a onclick="makeBlockUI();" href="index.php?mode=addnew_contract">


					<b>Nhập ĐH</b><img src="images/Handshake.png" alt="Nhập đơn hàng" border="0" height="40">					


				</a>


					</div>


				</div>


<div style="float: left; ">


						<div class="icon">


						<a onclick="makeBlockUI();" href="index.php?mode=rankuser&grp=SALE">


						<b>B&#225;o c&#225;o</b><img src="images/chart01.png" alt="Doanh số" align="top" border="0" height="40">								


						</a>


						</div>
						
						<?php if ($_SESSION['MM_Isadmin'] == 1) { ?>
						<div class="icon">

						<a onclick="makeBlockUI();" href="index.php?mode=paymentreport">

						<b>Công nợ</b><img src="images/payment.png" alt="Công nợ" align="top" border="0" height="40">								

						</a>

						</div>
						<?php } ?>
						


</div>


<?php 


if (!isset($_SESSION['popup_birthday'])) $_SESSION['popup_birthday'] = "";


if ($_SESSION['popup_birthday'] != null) { ?>


<div style="float: left; ">


						<div class="icon">


						<a class="fancybox1" href="birthday.php" data-fancybox-type="iframe">


						<b>Birthday<font color="red">(<?=$_SESSION['popup_birthday'];?>)</font></b><img src="images/birthdayicon.png" alt="Sinh nhật" align="top" border="0" height="40">								


						</a>


						</div>


</div>			


<?php } ?>


				</td>








		


	</tr></table>


	


	