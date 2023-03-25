<!--
<?php 
header('Content-type: text/html; charset=utf-8'); 
require_once('./global.php'); 
require("src/mysql_function.php");
$mysqlIns=new mysql(); $mysqlIns->link=$db;

$prd =$_REQUEST['prd'];

$index_chuaxuly=$mysqlIns->search_prd_options($prd);
?>-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to Administrator system</title>
	
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #c2c2c2; border-collapse:none;" >
<?php if(count($tbl_product_type) > 0) { ?>
<font color="red">(*)</font><b>&nbsp;&nbsp;&nbsp;Quy cách&nbsp;&nbsp;&nbsp;</b>
	  <select <?php echo $disabled.$selectDisabledStyle; ?> name="trn_prd_type" id="trn_prd_type" style="border:1px solid #DADADA;">
<?php
for($i=0;$i<count($index_chuaxuly);$i++)
{
	if (isset($_POST['trn_prd_type']) && ($_POST['trn_prd_type'] == $tbl_product_type[$i]['tp_code'])) {
		echo '<option selected="selected" value="'.$tbl_product_type[$i]['tp_code'].'">'.$tbl_product_type[$i]['tp_name'].'</option>';
	} elseif ($tbl_product_type[$i]['tp_checked'] == 1) {
		echo '<option selected="selected" value="'.$tbl_product_type[$i]['tp_code'].'">'.$tbl_product_type[$i]['tp_name'].'</option>';
	} else {
		echo '<option value="'.$tbl_product_type[$i]['tp_code'].'">'.$tbl_product_type[$i]['tp_name'].'</option>';
	}

}
?>
      </select>
<?php } ?>
</table>
</body>
</html>
<script type="text/javascript">
		function loadAvailbleOrder(id)
		{
			$('#cust_name').val($('#cust_name_' +id).val());
			$('#cust_company').val($('#cust_company_' +id).val());
			$('#cust_email').val($('#cust_email_' +id).val());
			$('#cust_phone').val($('#cust_phone_' +id).val());

			$('#trn_ref').val($('#trn_ref_' +id).val());
			$('#trn_name').val($('#trn_name_' +id).val());
			$('#trn_prd_code').val($('#trn_prd_code_' +id).val());
			$('#trn_prd_type').val($('#trn_prd_type_' +id).val());
			$('#trn_quantity').val($('#trn_quantity_' +id).val());
			$('#trn_unit_price').val($('#trn_unit_price_' +id).val());
			
			
			
			if ($('#trn_vat_' +id).val() == 10) {
				$('#trn_vat_v10').prop('checked',true);
				$('#trn_vat_v0').prop('checked',false);
			} else {
				$('#trn_vat_v10').prop('checked',false);
				$('#trn_vat_v0').prop('checked',true);
			}
			
			$('#trn_payment').val($('#trn_payment_' +id).val());
			$('#trn_detail').val($('#trn_detail_' +id).val());
			
			/*if ($('#trn_class_' +id).val() == 1) {
				$('#trn_class_v0').prop('checked',false);
				$('#trn_class_v1').prop('checked',true);
				changecolor('#ff7373');
			} else {
				$('#trn_class_v0').prop('checked',true);
				$('#trn_class_v1').prop('checked',false);
				changecolor('');
			}*/
			if ($('#trn_end_date_' +id).val() != '') {
				changecolor('#ff7373');
			} else {
				changecolor('');
			}

			if ($('#trn_type_code_' +id).val() == 1) {
				$('#trn_type_code_v1').prop('checked',true);
				$('#trn_type_code_v0').prop('checked',false);
			} else {
				$('#trn_type_code_v1').prop('checked',false);
				$('#trn_type_code_v0').prop('checked',true);
			}
			
			$('#trn_during').val($('#trn_during_' +id).val());
			$('#trn_end_date').val($('#trn_end_date_' +id).val());
			
			if ($('#trn_deliver_type_' +id).val() == 1) {
				$('#trn_deliver_type_v1').prop('checked',true);
				$('#trn_deliver_type_v0').prop('checked',false);
			} else {
				$('#trn_deliver_type_v1').prop('checked',false);
				$('#trn_deliver_type_v0').prop('checked',true);
			}
			
			$('#trn_deliver_address').val($('#trn_deliver_address_' +id).val());
			$('#prg_step2_by').val($('#prg_step2_by_' +id).val());
			$('#prg_step2_dt1').html($('#prg_step2_dt1_' +id).val());
			$('#prg_step2_dt2').html($('#prg_step2_dt2_' +id).val());
			$('#prg_step2_dt3').html($('#prg_step2_dt3_' +id).val());
			
			$('#prg_step3_by').val($('#prg_step3_by_' +id).val());
			$('#prg_step3_dt1').html($('#prg_step3_dt1_' +id).val());
			$('#prg_step3_dt2').html($('#prg_step3_dt2_' +id).val());
			$('#prg_issue_date').html($('#prg_issue_date_' +id).val());
			$('#prg_issue_value').val($('#prg_issue_value_' +id).val());
			
			$('#prg_step4_by').val($('#prg_step4_by_' +id).val());
			$('#prg_step4_dt1').html($('#prg_step4_dt1_' +id).val());
			$('#prg_step4_dt2').html($('#prg_step4_dt2_' +id).val());
			
			$('#prg_note').val($('#prg_note_' +id).val());
			$('#trn_img_show').html("<img src=\""+$('#trn_img_' +id).val()+"\">");
			
			$('#trn_amount').val($('#trn_amount_' +id).val());
			$('#trn_amount_withoutVAT').val($('#trn_amount_withoutVAT_' +id).val());
			$('#trn_payment_remain').val($('#trn_payment_remain_' +id).val());
			
			/*trn_amount_withoutVAT = parseInt($('#trn_quantity_' +id).val()) * parseInt($('#trn_unit_price_' +id).val());
			$('#trn_amount_withoutVAT').val(trn_amount_withoutVAT);
			
			VAT = 0;
			if ($('#trn_vat_' +id).val() == 10) VAT = 10;
			trn_amount = trn_amount_withoutVAT + trn_amount_withoutVAT / 100 * VAT;
			$('#trn_amount').val(trn_amount);
			
			trn_payment_remain = trn_amount - parseInt($('#trn_payment').val());
			$('#trn_payment_remain').val(trn_payment_remain);*/
		}
		
		function loadAvailbleCust(id)
		{
			$('#cust_name').val($('#cust_name_' +id).val());
			$('#cust_company').val($('#cust_company_' +id).val());
			$('#cust_email').val($('#cust_email_' +id).val());
			$('#cust_phone').val($('#cust_phone_' +id).val());

			$('#trn_ref').val('');
			$('#trn_name').val('');
			$('#trn_prd_code').val('');
			$('#trn_prd_type').val('');
			$('#trn_quantity').val('');
			$('#trn_unit_price').val('');
			
			
			
			$('#trn_vat_v10').prop('checked',false);
			$('#trn_vat_v0').prop('checked',true);
			
			$('#trn_payment').val('');
			$('#trn_detail').val('');
			
			//$('#trn_class_v0').prop('checked',true);
			//$('#trn_class_v1').prop('checked',false);

			$('#trn_type_code_v1').prop('checked',false);
			$('#trn_type_code_v0').prop('checked',true);
			
			$('#trn_during').val('');
			$('#trn_end_date').val('');
			
			$('#trn_deliver_type_v1').prop('checked',false);
			$('#trn_deliver_type_v0').prop('checked',true);
			
			$('#trn_deliver_address').val('');
			$('#prg_step2_dt1').html('');
			$('#prg_step2_dt2').html('');
			$('#prg_step2_dt3').html('');
			
			$('#prg_step3_dt1').html('');
			$('#prg_step3_dt2').html('');
			$('#prg_issue_date').html('');
			$('#prg_issue_value').val('');
			
			$('#prg_step4_dt1').html('');
			$('#prg_step4_dt2').html('');
			
			$('#prg_note').val('');
			$('#trn_img_show').html('');
			
			$('#trn_amount_withoutVAT').val('');
			$('#trn_amount').val('');
			$('#trn_payment_remain').val('');
		}
		
		function clearAvailbleOrder()
		{
			//$('#cust_name').val('');
			//$('#cust_company').val('');
			//$('#cust_email').val('');
			//$('#cust_phone').val('');

			$('#trn_ref').val('');
			$('#trn_name').val('');
			$('#trn_prd_code').val('');
			$('#trn_prd_type').val('');
			$('#trn_quantity').val('');
			$('#trn_unit_price').val('');
			
			$('#trn_amount_withoutVAT').val('');
			$('#trn_amount').val('');
			$('#trn_payment_remain').val('');
			
			$('#trn_vat_v0').prop('checked',true);
			
			$('#trn_payment').val('');
			$('#trn_detail').val('');
			
			//$('#trn_class_v0').prop('checked',true);
			changecolor('');

			//$('#trn_type_code_v1').prop('checked',true);

			$('#trn_during').val('3');
			$('#trn_end_date').val('');
			
			$('#trn_deliver_type_v0').prop('checked',true);
			
			$('#trn_deliver_address').val('');
			$('#prg_step2_by').val('');
			$('#prg_step2_dt1').html('');
			$('#prg_step2_dt2').html('');
			$('#prg_step2_dt3').html('');
			
			$('#prg_step3_by').val('');
			$('#prg_step3_dt1').html('');
			$('#prg_step3_dt2').html('');
			$('#prg_issue_date').html('');
			$('#prg_issue_value').val('');
			
			$('#prg_step4_by').val('');
			$('#prg_step4_dt1').html('');
			$('#prg_step4_dt2').html('');
			
			$('#prg_note').val('');
			$('#trn_img_show').html('');
			
		}
    </script>
<?php mysql_close($db); ?>