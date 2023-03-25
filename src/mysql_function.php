<?php
	class mysql{
	var $result;
	var $link;
	var $database;
	var $rowsdata = null;
        var $output = null;
	function getConnection()
	{
		/*$filename="./global.php";
		if (file_exists($filename)) {
		require($filename);
		}
		else
		{
			$filename="../global.php";
			require($filename);
		}
		
		$this->database=$dbname;
		$this->link=mysql_connect($dbhost,$dbuser,$dbpass)
		or die("Connect to server Failed");
		//mysql_query('set names utf8',$this->link);
		mysql_select_db($this->database,$this->link);
		*/
	}
	
	
	function close()
	{
		//mysql_close($this->link);
	}
	function querycount($sql)
	{
		$this->getConnection();
		$this->result=mysql_query($sql,$this->link);
		$numrows=mysql_num_rows($this->result);
		return $numrows;
		
	}
	function query_sql($sql)
	{
		$this->getConnection();
		$this->result=mysql_query($sql,$this->link);
		return $this->result;
	}
	function fetch_array()
	{
		$row=mysql_fetch_array($this->result);
		return $row;
	}
	function fetch_rows()
	{
	
		$row=mysql_fetch_row($this->result) ;
		return $row;
	
	}
	
	function select_tbl_trans()
	{
		$rowsdata = null;
		$sql="SELECT t.* FROM tbl_trans t";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		return $rowsdata;
	}
	
	function select_tbl_payment_pack($cust_phone)
	{
		if ($cust_phone=="") {
			$cust_phone_f = '';
		} else {
			$cust_phone_f = " and t.pack_cust_phone = '".$cust_phone."' ";
		}
		
		if ($from_date=="") {
				$from_date_f = '1900-01-01';
		} else {
			$from_date_f_arr = explode("-",$from_date);
			if (strlen($from_date_f_arr[2]) == 4) {
				$from_date_f = $from_date_f_arr[2].'-'.$from_date_f_arr[1].'-'.$from_date_f_arr[0];
			}
		}
		
		if ($to_date=="") {
				$to_date_f = '3000-01-01';
		} else {
			$to_date_f_arr = explode("-",$to_date);
			if (strlen($to_date_f_arr[2]) == 4) {
				$to_date_f = $to_date_f_arr[2].'-'.$to_date_f_arr[1].'-'.$to_date_f_arr[0];
			}
		}
		
		$rowsdata = null;
		$sql="SELECT t.*, t2.cust_name FROM tbl_payment_pack t inner join tbl_customer t2 on CONVERT(t2.cust_phone , CHAR) = CONVERT(t.pack_cust_phone  , CHAR)
		      where 1=1 ".$cust_phone_f." 
													and pack_date >= '".$from_date_f."' 
													and pack_date <= ADDDATE('".$to_date_f."',1) 
													order by pack_date desc limit 0,500";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		return $rowsdata;
	}
	
	function payment_all($cust_phone, $payment_date_f, $total_payment_tm, $total_payment_ck)
	{
		$rowsdata = null;
		$sql="select *
				  from (select tmp.*,
							   case
								 when (tmp.trn_payment_old - tmp.trn_amount_all < 0) then
								  1
								 when (tmp.trn_payment_old - tmp.trn_amount_all = 0) then
								  0
								 else
								  -1
							   end as payorder
						  from (SELECT (select x.trn_id
										  from tbl_trans x
										 where x.trn_payment = max(t.trn_payment)
										   and x.trn_ref = t.trn_ref limit 0, 1) trn_id_max,
									   t.trn_ref,
									   t.trn_start_date,
									   t.trn_id,
									   sum(IFNULL(t.trn_payment, 0)) trn_payment_old,
									   (select sum(x.trn_amount)
										  from tbl_trans x
										 where x.trn_ref = t.trn_ref) trn_amount_all
								  FROM tbl_trans t
								 inner join tbl_product t1
									on t1.prd_code = t.trn_prd_code
								 WHERE t.trn_cust_phone = '".$cust_phone."'
								 group by t.trn_ref) tmp) tmp1
				 order by tmp1.payorder, tmp1.trn_start_date asc, tmp1.trn_id asc ";
		
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
		}
		
		$this_payment = 0;
		$total_payment_tm_countdown = $total_payment_tm;
		$total_payment_ck_countdown = $total_payment_ck;
		$payment_this_tm = 0;
		$payment_this_ck = 0;
		
		$trn_id_all = "";
		$have_payment = 0;
		$trn_id_last = "";
		$trn_ref_last = "";
		// Duyệt các bản ghi đối tượng trả nợ
		for ($i = 0; $i < count($rowsdata); $i ++) {
			$this_payment = $rowsdata[$i]["trn_amount_all"] - $rowsdata[$i]["trn_payment_old"];
			
			// Nếu số tiền còn dư để trả lớn hơn 0
			if ($total_payment_tm_countdown + $total_payment_ck_countdown > 0) {
				if ($this_payment == 0) { // Nếu đơn đang duyệt đã trả đủ
					// Bỏ qua
				} else if ($this_payment != 0) { // Nếu đơn đang duyệt thừa tiền hoặc đang còn nợ
					// Trả nợ tiền mặt trước
					// Nếu tiền mặt lớn hơn số nợ ở bản ghi đang duyệt
					if ($total_payment_tm_countdown >= $this_payment) {
						// Trả toàn bộ bằng tiền mặt
						$payment_this_tm = $this_payment;
						$payment_this_ck = 0;
					} else {
						// Nếu tiền mặt ít hơn số nợ ở bản ghi đang duyệt
						// Trả toàn bộ bằng tiền mặt + 1 phần tiền ck
						$payment_this_tm = $total_payment_tm_countdown;
						
						// Kiểm tra xem số ck có đủ trả ko
						$remain_payment_ck = $this_payment - $payment_this_tm;
						
						// Nếu đủ trả
						if ($total_payment_ck_countdown >= $remain_payment_ck) {
							// Trả hết nợ còn lại = tiền ck
							$payment_this_ck = $remain_payment_ck;
						} else {
							// Nếu không đủ tra hết thì trả hết tiền ck vào đơn này
							$payment_this_ck = $total_payment_ck_countdown;
						}
					}
					
					// Trừ đi số tiền đã trả cho đơn sau
					$total_payment_tm_countdown = $total_payment_tm_countdown - $payment_this_tm;
					$total_payment_ck_countdown = $total_payment_ck_countdown - $payment_this_ck;
					
					$update_payment = $this->update_payment($rowsdata[$i]["trn_id_max"], $rowsdata[$i]["trn_ref"], $payment_this_tm, $payment_this_ck);
					$trn_id_all = $trn_id_all.$rowsdata[$i]["trn_id_max"]."@";
					$have_payment = 1;
					$trn_id_last = $rowsdata[$i]["trn_id_max"];
					$trn_ref_last =  $rowsdata[$i]["trn_ref"];
				}
			}
		}

		$payment_total_remain = $total_payment_tm_countdown + $total_payment_ck_countdown;
		if ($have_payment == 1) {
			$sql = " INSERT INTO tbl_payment_pack (pack_user, pack_created, pack_cust_phone, pack_date, pack_payment_tm, pack_payment_ck, pack_over, pack_is, pack_trn_ids)
					 VALUES (UCASE('".$_SESSION['MM_Username']."'),SYSDATE(),'".$cust_phone."', ".$payment_date_f.", '".$total_payment_tm."', '".$total_payment_ck."',
					 ".$payment_total_remain.", '1', '".$trn_id_all."')	";
					 //echo "-->TTTT".$sql."<!--";
			$numrow=$this->query_sql($sql);
			//echo "-->TTTT".$numrow."<!--"; 
			if ($numrow < 1) {
				return;
			}
		}
		
		if ($payment_total_remain > 0) {
			$update_payment = $this->update_payment($trn_id_last, $trn_ref_last, $total_payment_tm_countdown, $total_payment_ck_countdown);
		}
	}
	
	function update_payment($trn_id, $trn_ref, $payment_this_tm, $payment_this_ck)
	{
		$sql = "INSERT INTO tbl_payment_hisv2
				  (payment_id,
				   payment_status,
				   payment_cust_phone,
				   trn_id,
				   trn_ref,
				   trn_payment_tm,
				   trn_payment_ck,
				   trn_createdby,
				   trn_created,
				   trn_auth_by,
				   trn_auth_date)
				VALUES
				  (IFNULL((select max(t.payment_id) + 1 from tbl_payment_hisv2 t), 1),
				   '1',
				   (select x.trn_cust_phone from tbl_trans x where x.trn_id = '".$trn_id."'),
				   '".$trn_id."',
				   '".$trn_ref."',
				   '".$payment_this_tm."',
				   '".$payment_this_ck."',
				   UCASE('".$_SESSION['MM_Username']."'),
				   SYSDATE(),
				   UCASE('".$_SESSION['MM_Username']."'),
				   SYSDATE()
				   ) ";
		echo "KK".$sql;
		$numrow=$this->querycount($sql);

		$sql = " UPDATE tbl_trans SET
						prg_payment_dt = SYSDATE(),
						trn_payment = (SELECT sum(trn_payment_tm + trn_payment_ck)
										  from tbl_payment_hisv2 a
										  WHERE a.trn_ref = '".$trn_ref."'),
						trn_payment_type_tm = '".$payment_this_tm."',
						trn_payment_type_ck = '".$payment_this_ck."'
				 WHERE trn_id = '".$trn_id."'";
		$numrow=$this->querycount($sql);
		
		
		$sql = " UPDATE tbl_trans SET
						prg_payment_dt = null,
						trn_payment = null,
						prg_payment_tm_add = 0,
						prg_payment_ck_add = 0,
						trn_payment_type_tm = 0,
						trn_payment_type_ck = 0
				 WHERE trn_id <> '".$trn_id."' and trn_ref = '".$trn_ref."'";
		$numrow=$this->querycount($sql);

		return $numrow;
	}
	
	function select_tbl_trans_issue($view)
	{
		$rowsdata = null;
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		$sql="SELECT a.*,b.*,c.*,e.*,
					 FORMAT(a.trn_amount, 0) as trn_amount_f,
					 FORMAT(a.trn_payment, 0) as trn_payment_f,
					 FORMAT(a.trn_payment_remain, 0) as trn_payment_remain_f,
					 DATE_FORMAT(a.trn_start_date,'%d-%m-%Y') as trn_start_date_f,
					 DATE_FORMAT(a.prg_step2_dt3,'%d-%m-%Y') as prg_step2_dt3_f,
					 DATE_FORMAT(a.prg_step4_dt2,'%d-%m-%Y') as prg_step4_dt2_f,
					 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_step1_by))
					  ) grp_img_step1,
					  (select GROUP_CONCAT(t1.grp_code SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_step1_by))
					  ) grp_code_step1,
					  
					 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_pending_by))
					  ) grp_img_pending,
					  (select GROUP_CONCAT(t1.grp_code SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_pending_by))
					  ) grp_code_pending,
					  
					 /*case 
						when a.prg_step2_dt3 is not null then TIMESTAMPDIFF(DAY,DATE(a.trn_start_date) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when a.trn_end_date is not null then TIMESTAMPDIFF(DAY,DATE(a.trn_start_date) , DATE(a.trn_end_date))
						else '(?)'
					 end as date_duration,*/
					 case 
						when (a.trn_end_date is not null and a.trn_end_date != '' and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , DATE(a.trn_end_date))
						when (a.trn_end_date is not null and a.trn_end_date != '' and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , DATE(a.trn_end_date))
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when (a.trn_has_file = 1) then TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_start_date),a.trn_during))
						else '(?)'
					 end as today_duration,
					 case 
						when (a.prg_status >= 42) then 999
						when (a.trn_end_date != '' and a.trn_end_date is not null and a.prg_step4_dt2 is null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , DATE(a.trn_end_date)),SIGNED INTEGER)
						when (a.trn_end_date != '' and a.trn_end_date is not null and a.prg_step4_dt2 is not null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , DATE(a.trn_end_date)),SIGNED INTEGER)
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during)),SIGNED INTEGER)
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is not null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during)),SIGNED INTEGER)
						when (a.trn_has_file = 1) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_start_date),a.trn_during)),SIGNED INTEGER)
						else 998
					 end as trn_order,
					 case 
						when a.trn_during is not null then CONCAT('+',a.trn_during)
						else DATE_FORMAT(a.trn_end_date,'%d-%m-%Y')
					 end as trn_end_date_f,
					 (select max(t.trn_payment) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_pay,
					 (select sum(t.trn_amount) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_amount,
					 case 
						when a.prg_status = '12' then 'Chưa thiết kế'
						when a.prg_status = '21' then 'Đang thiết kế'
						when a.prg_status = '22' then 'Chưa duyệt tkế'
						when a.prg_status = '23' then 'Chưa sản xuất'
						when a.prg_status = '31' then 'Đang sản xuất'
						when a.prg_status = '32' then 'Chưa giao hàng'
						when a.prg_status = '41' then 'Đang giao hàng'
						when a.prg_status = '42' then 'Đã giao hàng'
						else '(?)'
					 end as prg_status_f,
					 case 
						when a.prg_status = '12' then 'Đang thiết kế'
						when a.prg_status = '21' then 'Đã thiết kế'
						when a.prg_status = '22' then 'Đã duyệt tkế'
						when a.prg_status = '23' then 'Đang sản xuất'
						when a.prg_status = '31' then 'Đã sản xuất'
						when a.prg_status = '32' then 'Đã giao hàng'
						when a.prg_status = '41' then 'Đã giao hàng'
						when a.prg_status = '42' and a.trn_payment_remain > 0 then 'Đã thanh toán'
						else 'Đã chăm sóc'
					 end as prg_action_f,
					 case 
						when a.prg_status = '12' then '10'
						when a.prg_status = '21' then '20'
						when a.prg_status = '22' then '40'
						when a.prg_status = '23' then '50'
						when a.prg_status = '31' then '60'
						when a.prg_status = '32' then '80'
						when a.prg_status = '41' then '90'
						when a.prg_status = '42' then '100'
						else '0'
					 end as prg_percent_f,
					 DATE_FORMAT(a.prg_pending_from_dt,'%d-%m-%Y') as prg_pending_from_dt_f
		FROM tbl_trans a 
		left join tbl_customer b on b.cust_phone = a.trn_cust_phone
		left join tbl_product c on c.prd_code = a.trn_prd_code
		left join tbl_user e on UCASE(e.user_name) = UCASE(a.prg_pending_by)
		where a.prg_issue_dt is not null and (DATE_FORMAT(a.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']." or 
											  DATE_FORMAT(a.trn_start_date,'%Y%m') = ".$_SESSION['report_month'].")";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		return $rowsdata;
	}
	
	function select_tbl_customer()
	{
		$rowsdata = null;
		$sql="SELECT t.* FROM tbl_customer t";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		return $rowsdata;
	}
	
	function select_tbl_group_byid($grp_code)
	{
		$this->getConnection();
		$sql="SELECT t.* FROM tbl_group t where t.grp_code = '".$grp_code."'";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function get_ref_max()
	{
		$this->getConnection();
		$sql="SELECT MAX( CAST( t.trn_ref AS SIGNED INTEGER) ) maxref FROM tbl_trans t";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_group_all()
	{
		$this->getConnection();
		$sql="SELECT t.* FROM tbl_group t ORDER BY t.grp_order asc";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_group_action()
	{
		$this->getConnection();
		$sql="SELECT t.* FROM tbl_group t where UCASE(t.grp_code) in ('SALE','DESIGN','BUILD','DELIVER','CARE') ORDER BY t.grp_order asc";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_product_type_all($prd)
	{

		$sql="SELECT  *
			  FROM tbl_product_type t 
			  where t.tp_class = '".$prd."'
			  and t.tp_code is null
			  ORDER BY tp_option_order asc";

		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;

		return $rowsdata;
	}
	
	function select_tbl_product_bycode($prd)
	{

		$sql="SELECT  *
			  FROM tbl_product t 
			  where t.prd_code = '".$prd."'";

		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;

		return $rowsdata;
	}
	
	function select_tbl_product_tp_bycode($tp)
	{

		$sql="SELECT  *
			  FROM tbl_product_type t 
			  where t.tp_option_code = '".$tp."'
			  and t.tp_option is not null";

		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;

		return $rowsdata;
	}
	
	
	function select_tbl_product_opt_bycode($tp,$opt)
	{

		$sql="SELECT  *
			  FROM tbl_product_type t 
			  where t.tp_option_code = '".$tp."'
			  and t.tp_code = '".$opt."'";

		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo '-->'.$sql.'<!--';

		return $rowsdata;
	}
	
	function select_tbl_product_type_byopt($prd,$opt)
	{
		$sql="SELECT * FROM tbl_product_type t 
		  where t.tp_class = '".$prd."'
		  and t.tp_option_code = '".$opt."'
		  and t.tp_code is not null
		  ORDER BY t.tp_order asc";
		
		
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		return $rowsdata;
	}
	
		
	function select_tbl_user_report($grp)
	{
		if (strtoupper($grp) == 'SALE') {
			$step_by = " UCASE(t1.prg_step1_by) ";
		} else if (strtoupper($grp) == 'DESIGN') {
			$step_by = " UCASE(t1.prg_step2_by) ";
		} else if (strtoupper($grp) == 'BUILD') {
			$step_by = " UCASE(t1.prg_step3_by) ";
		} else if (strtoupper($grp) == 'DELIVER') {
			$step_by = " UCASE(t1.prg_step4_by) ";
		} else if (strtoupper($grp) == 'CARE') {
			$step_by = " UCASE(t1.prg_step5_by) ";
		} else {
			$step_by = " UCASE(t1.prg_step1_by) ";
		}
		
		$this->getConnection();
		$sql = "SELECT a.trn_user user_name
				FROM 
					(SELECT DISTINCT ".$step_by." trn_user
					 FROM tbl_trans t1 where t1.trn_created > DATE_SUB(now(), INTERVAL 16 MONTH)
					 ) a ";
		
		
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
		
	function select_tbl_user_all($grp)
	{
		$this->getConnection();
		if ($grp != null) {
			$sql="SELECT t.*
			      FROM tbl_user t 
				  where t.user_name in (select v1.urg_user_name from tbl_user_group v1 where v1.urg_grp_code = '".$grp."')
				  ORDER BY t.user_name asc";
		} else {
			$sql="SELECT t.*
				  FROM tbl_user t 
			      ORDER BY t.user_name asc";
		}
		
		
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_user_get_group($user)
	{
		$this->getConnection();
		$sql="SELECT t1.*,t2.*
			  from tbl_user_group t1, 
				   tbl_group t2 
			  where t1.urg_grp_code = t2.grp_code
			  and UCASE(t1.urg_user_name) = UCASE('".$user."')
			  ORDER BY t2.grp_order asc";

		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function update_unerr_trn_trans($trn_id)
	{
		$sql="UPDATE tbl_trans set prg_issue_dt = null, prg_issue_value = null where trn_id = '".$trn_id."'";
		$numrow=$this->query_sql($sql);
		//echo $sql
		return $numrow;
	}
	
	function update_fulltext_search_trn_trans($trn_id, $trn_fulltext_search)
	{
		$sql="UPDATE tbl_trans set trn_fulltext_search = '".$trn_fulltext_search."' where trn_id = '".$trn_id."'";
		$numrow=$this->query_sql($sql);
		//echo $sql
		return $numrow;
	}
	
	function update_fulltext_search_trn_customer($cust_id, $cust_fulltext_search)
	{
		$sql="UPDATE tbl_customer set cust_fulltext_search = '".$cust_fulltext_search."' where cust_id = '".$cust_id."'";
		$numrow=$this->query_sql($sql);
		//echo $sql
		return $numrow;
	}
	
	function update_tbl_user_lock($user_name,$lock_val)
	{
		$sql="UPDATE tbl_user set user_stat = '".$lock_val."' where UCASE(user_name) = UCASE('".$user_name."')";
		$numrow=$this->query_sql($sql);
		//echo $sql
		return $numrow;
	}
	
	function get_birthday()
	{
		$rowsdata = null;
		$sql="SELECT u.*,DATE_FORMAT(u.user_birth ,'%d/%m') as user_birth_f
			  FROM tbl_user u where DATE_FORMAT(u.user_birth ,'%d-%m') = DATE_FORMAT(NOW() ,'%d-%m')";
		$rowID=0;
		$numrow=$this->querycount($sql);
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		return $rowsdata;
	}
	
	function changepass_tbl_user($user_pass_old,$user_pass_new)
	{
		$sql="SELECT * FROM tbl_user WHERE user_pass = '".$user_pass_old."' and UCASE(user_name) = UCASE('".$_SESSION['MM_Username']."')";
		$numrow=$this->querycount($sql);
		//echo $numrow;
		
		if ($numrow > 0) {
			$sql="UPDATE tbl_user set user_pass = '".$user_pass_new."' where UCASE(user_name) = UCASE('".$_SESSION['MM_Username']."')";
			$numrow=$this->query_sql($sql);
			//echo $numrow;
		} else {
			$numrow = -1;
		}
		return $numrow;
	}
	
	function delete_tbl_user($user_id)
	{
		$this->getConnection();
		$sql="DELETE from tbl_user where user_id = '".$user_id."'";
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function delete_tbl_product_type($tp_id)
	{
		$sql="DELETE from tbl_product_type where tp_id = '".$tp_id."'";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function delete_tbl_product_opt($tp_option_code)
	{
		$sql="DELETE from tbl_product_type where tp_option_code = '".$tp_option_code."'";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function delete_tbl_product($prd_code)
	{
		$sql="DELETE from tbl_product where prd_code = '".$prd_code."'";
		$numrow=$this->query_sql($sql);
		
		$sql="DELETE from tbl_product_type where tp_class = '".$prd_code."'";
		$numrow=$this->query_sql($sql);
		
		return $numrow;
	}
	
	function delete_tbl_group_by_id($grp_id)
	{
		$this->getConnection();
		$sql="DELETE from tbl_group where grp_id = '".$grp_id."'";
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function check_tbl_user_exist($user_name)
	{
		$this->getConnection();
		$sql="select * from tbl_user where UCASE(user_name) = UCASE('".$user_name."')";
		$numrow=$this->querycount($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function check_tbl_group_exist_user($grp_code)
	{
		$this->getConnection();
		$sql="select * from tbl_user_group a where UCASE(a.urg_grp_code) = UCASE('".$grp_code."')";
		$numrow=$this->querycount($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function check_tbl_group_for_delete_user($user_id)
	{
		$this->getConnection();
		$sql="SELECT * FROM `tbl_user_group` WHERE urg_grp_code = (SELECT urg_grp_code FROM `tbl_user_group` WHERE urg_user_name = '$user_id')";
		$numrow=$this->querycount($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function check_customer_exist_phone($cust_id,$cust_phone)
	{
		$sql="select * from tbl_customer a where a.cust_phone = '".str_replace(' ','',str_replace('.','',$cust_phone))."' and cust_id !=".$cust_id;
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_product_exist($prd_code)
	{
		$sql="select * from tbl_product a where UCASE(a.prd_code) = UCASE('".$prd_code."')";
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_product_type_exist($hid_prd_code, $tp_option_code)
	{
		$sql="select * from tbl_product_type a 
			  where UCASE(a.tp_option_code) = UCASE('".$tp_option_code."')
			  and UCASE(a.tp_class) = UCASE('".$hid_prd_code."')
			  ";
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_product_type_exist_tp($hid_prd_code, $hid_option_code, $tp_code)
	{
		$sql="select * from tbl_product_type a 
			  where UCASE(a.tp_code) = UCASE('".$tp_code."')
			  and UCASE(a.tp_option_code) = UCASE('".$hid_option_code."')
			  and UCASE(a.tp_class) = UCASE('".$hid_prd_code."')
			  ";
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_trans_exist_tp($hid_prd_code, $hid_option_code, $tp_code)
	{
		$sql="select * from tbl_trans a 
			  where UCASE(a.trn_option) like UCASE('%".$hid_option_code."=".$tp_code."@%')
			  and a.trn_prd_code = '".$hid_prd_code."'
			  ";
		$numrow=$this->querycount($sql);
		//echo '-->'.$sql.'<!--';
		return $numrow;
	}
	
	function check_tbl_trans_exist_opt($hid_prd_code, $hid_option_code, $tp_code)
	{
		$sql="select * from tbl_trans a 
			  where UCASE(a.trn_option) like UCASE('%".$hid_option_code."=%')
			  and a.trn_prd_code = '".$hid_prd_code."'
			  ";
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_trans_exist_prd($hid_prd_code, $hid_option_code, $tp_code)
	{
		$sql="select * from tbl_trans a 
			  where a.trn_prd_code = '".$hid_prd_code."'
			  ";
		$numrow=$this->querycount($sql);
		//echo $sql;
		return $numrow;
	}
	
	function check_tbl_group_exist($grp_code)
	{
		$this->getConnection();
		$sql="select * from tbl_group where UCASE(grp_code) = UCASE('".$grp_code."')";
		$numrow=$this->querycount($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function add_tbl_group(   $grp_name,
								   $grp_code,
								   $grp_img)
	{
		  
		$this->getConnection();
		$sql="INSERT INTO tbl_group (   grp_name,
											 grp_code,
											 grp_img,
										     grp_stat,
										     grp_created,
										     grp_created_by
									)
							VALUES (   '".$grp_name."',
									   UCASE('".$grp_code."'),
									   '".$grp_img."',
									   'O',
									   SYSDATE(),
									   UCASE('".$_SESSION['MM_Username']."')
									)";
		$numrow=$this->query_sql($sql);
		echo $sql;
		$this->close();
		return $numrow;
	}
	
	function add_tbl_product(   $prd_code,
								   $prd_name,
								   $prd_order)
	{
		  
		$sql="INSERT INTO tbl_product (   prd_code,
											 prd_name,
											 prd_order,
										     prd_stat,
										     prd_created,
										     prd_created_by
									)
							VALUES (   UCASE('".$prd_code."'),
									   '".$prd_name."',
									   '".$prd_order."',
									   'O',
									   SYSDATE(),
									   UCASE('".$_SESSION['MM_Username']."')
									)";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function update_tbl_product(   $prd_code,
								   $prd_name,
								   $prd_order)
	{
		  
		$sql="UPDATE tbl_product SET prd_name = '".$prd_name."',
									 prd_order = '".$prd_order."',
									 prd_created = SYSDATE(),
									 prd_created_by = UCASE('".$_SESSION['MM_Username']."')
			  WHERE UCASE(prd_code) = UCASE('".$prd_code."')";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function add_tbl_product_type( $tp_class,
								   $tp_option,
								   $tp_option_code,
								   $tp_option_type,
								   $tp_option_order)
	{
		  
		$sql="INSERT INTO tbl_product_type ( tp_class,
											 tp_option,
											 tp_option_code,
											 tp_option_type,
											 tp_option_order,
										     tp_stat,
										     tp_created,
										     tp_created_by
									)
							VALUES (   '".$tp_class."',
									   '".$tp_option."',
									   UCASE('".$tp_option_code."'),
									   '".$tp_option_type."',
									   '".$tp_option_order."',
									   'O',
									   SYSDATE(),
									   UCASE('".$_SESSION['MM_Username']."')
									)";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function update_tbl_product_type($tp_id, $hid_prd_code,
									 $tp_option,
									 $tp_option_code,
									 $tp_option_type,
									 $tp_option_order)
	{
		  
		$sql="UPDATE tbl_product_type SET tp_option = '".$tp_option."',
		tp_option_type = '".$tp_option_type."',
									 tp_option_order = '".$tp_option_order."',
									 tp_created = SYSDATE(),
									 tp_created_by = UCASE('".$_SESSION['MM_Username']."')
			  WHERE tp_id = '".$tp_id."' and UCASE(tp_option_code) = UCASE('".$tp_option_code."')";
		$numrow=$this->query_sql($sql);
		
		echo $sql;
		return $numrow;
	}
	
	function add_tbl_product_type_tp($hid_prd_code,
									 $hid_tp_option_code,
									 $tp_code,
									 $tp_name,
									 $tp_checked,
									 $tp_order
									 )
	{
		//echo $tp_checked ;
		if ($tp_checked == 1) {
			$sql="UPDATE tbl_product_type set tp_checked = 0 
			where UCASE(tp_class) = UCASE('".$hid_prd_code."')
			and UCASE(tp_option_code) = UCASE('".$hid_tp_option_code."')
			";
			$numrow=$this->query_sql($sql);
			//echo $sql;
		}
		
		$sql="INSERT INTO tbl_product_type ( tp_class,
											 tp_option_code,
											 tp_code,
											 tp_name,
											 tp_checked,
											 tp_order,
										     tp_stat,
										     tp_created,
										     tp_created_by
									)
							VALUES (   UCASE('".$hid_prd_code."'),
									   UCASE('".$hid_tp_option_code."'),
									   UCASE('".$tp_code."'),
									   '".$tp_name."',
									   '".$tp_checked."',
									   '".$tp_order."',
									   'O',
									   SYSDATE(),
									   UCASE('".$_SESSION['MM_Username']."')
									)";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	
	function update_tbl_product_type_tp( $tp_id,$hid_prd_code,
									 $hid_tp_option_code,
									 $tp_code,
									 $tp_name,
									 $tp_checked,
									 $tp_order
									 )
	{
		//echo $tp_checked ;
		if ($tp_checked == 1) {
			$sql="UPDATE tbl_product_type set tp_checked = 0 
			where UCASE(tp_class) = UCASE('".$hid_prd_code."')
			and UCASE(tp_option_code) = UCASE('".$hid_tp_option_code."')
			";
			$numrow=$this->query_sql($sql);
			//echo $sql;
		}
		
		$sql="UPDATE tbl_product_type 
				SET tp_class = UCASE('".$hid_prd_code."'),
					tp_option_code = UCASE('".$hid_tp_option_code."'),
					tp_name='".$tp_name."',
					tp_checked ='".$tp_checked."',
					tp_order = '".$tp_order."',
					tp_stat = 'O',
					tp_created = SYSDATE(),
					tp_created_by = UCASE('".$_SESSION['MM_Username']."')
				WHERE tp_code = UCASE('".$tp_code."') and tp_id='$tp_id'";
		$numrow=$this->query_sql($sql);
		echo $sql;
		return $numrow;
	}
	
	function add_tbl_user( $user_name,
						   $user_pass,
						   $user_grp_code,
						   $user_fullname,
						   $user_sex,
						   $user_birth,
						   $user_address,
						   $user_email,
						   $user_phone,
						   $duongdan)
	{
		if ($user_birth=="") {
				$user_birth_f = 'null';
		} else {
			$user_birth_f_arr = explode("-",$user_birth);
			if (strlen($user_birth_f_arr[2]) == 4) {
				$user_birth_f = "'".$user_birth_f_arr[2].'-'.$user_birth_f_arr[1].'-'.$user_birth_f_arr[0]."'";
			}
		}
		  
		$this->getConnection();

		$sql="DELETE FROM tbl_user_group where UCASE(urg_user_name) = UCASE('".$user_name."')";
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$this->close();
			
		for ($i = 0; $i < count($user_grp_code); $i ++) {
			if ($user_grp_code[$i] != "") {
				$sql="INSERT INTO tbl_user_group (  urg_grp_code,
													urg_user_name,
													urg_created ,
													urg_created_by
													)
										VALUES (   '".$user_grp_code[$i]."',
												   '".$user_name."',
												   SYSDATE(),
												   UCASE('".$_SESSION['MM_Username']."')
												)";
				$numrow=$this->query_sql($sql);
				//echo $sql;
				//$this->close();
			}
		}
		
		if ($duongdan == '1') {
			$sql="SELECT g.*
				  FROM tbl_group g where g.grp_code like '%".$user_grp_code."%'";
				  $grp_img = "";
				  
			$rowID=0;
			$numrow=$this->querycount($sql);
			if($numrow>0)
			{
				$row = $this->fetch_array();
				$duongdan = $row["grp_img"];
			} else {
				$duongdan = "images/noicon1.png";
			}
		}
		
		$sql="INSERT INTO tbl_user (   user_name,
									   user_pass,
									   user_grp_code,
									   user_fullname,
									   user_sex,
									   user_birth,
									   user_address,
									   user_email,
									   user_phone,
									   user_img,
									   user_stat,
									   user_created,
									   user_created_by
									)
							VALUES (   '".$user_name."',
									   '".$user_pass."',
									   '".$user_grp_code."',
									   '".$user_fullname."',
									   '".$user_sex."',
									   ".$user_birth_f.",
									   '".$user_address."',
									   '".$user_email."',
									   '".$user_phone."',
									   '".$duongdan."',
									   'O',
									   SYSDATE(),
									   UCASE('".$_SESSION['MM_Username']."')
									)";
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	function edit_tbl_user( $user_name,
						    $user_pass,
						    $user_grp_code,
						    $user_fullname,
						    $user_sex,
						    $user_birth,
						    $user_address,
						    $user_email,
						    $user_phone,
						    $duongdan)
	{
		if ($user_birth=="") {
				$user_birth_f = 'null';
		} else {
			$user_birth_f_arr = explode("-",$user_birth);
			if (strlen($user_birth_f_arr[2]) == 4) {
				$user_birth_f = "'".$user_birth_f_arr[2].'-'.$user_birth_f_arr[1].'-'.$user_birth_f_arr[0]."'";
			}
		}
		$this->getConnection();
		
		$sql="DELETE FROM tbl_user_group where UCASE(urg_user_name) = UCASE('".$user_name."')";
		$numrow=$this->query_sql($sql);
		echo $sql;
		//$this->close();
			
		for ($i = 0; $i < count($user_grp_code); $i ++) {
			if ($user_grp_code[$i] != "") {
				$sql="INSERT INTO tbl_user_group (  urg_grp_code,
													urg_user_name,
													urg_created ,
													urg_created_by
													)
										VALUES (   '".$user_grp_code[$i]."',
												   '".$user_name."',
												   SYSDATE(),
												   UCASE('".$_SESSION['MM_Username']."')
												)";
				$numrow=$this->query_sql($sql);
				//echo $sql;
				//$this->close();
			}
		}
		
		
		if ($duongdan == '1') {
			$sql="SELECT g.*
				  FROM tbl_group g where g.grp_code like '%".$user_grp_code[0]."%'";
				  $grp_img = "";
			$rowID=0;
			
			//echo '-->'.$sql.'<!--';
			$numrow=$this->querycount($sql);
			if($numrow>0)
			{
				$row = $this->fetch_array();
				$duongdan = $row["grp_img"];
			} else {
				$duongdan = "images/noicon1.png";
			}
		}
		
		$sql="UPDATE tbl_user set      user_grp_code = '".$user_grp_code."',
									   user_fullname = '".$user_fullname."',
									   user_sex = '".$user_sex."',
									   user_birth = ".$user_birth_f.",
									   user_address = '".$user_address."',
									   user_email = '".$user_email."',
									   user_phone = '".$user_phone."',
									   user_img = (select case '".$duongdan."' when '' then user_img
																					else '".$duongdan."'
														  end from dual),
									   user_stat = 'O',
									   user_created = SYSDATE(),
									   user_created_by = UCASE('".$_SESSION['MM_Username']."')
			   WHERE UCASE(user_name) = UCASE('".$user_name."')";
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$this->close();
		return $numrow;
	}
	
	
	function changeinfo_tbl_user( $user_name,
						    $user_pass,
						    $user_fullname,
						    $user_sex,
						    $user_birth,
						    $user_address,
						    $user_email,
						    $user_phone,
							$duongdan)
	{
		if ($user_birth=="") {
				$user_birth_f = 'null';
		} else {
			$user_birth_f_arr = explode("-",$user_birth);
			if (strlen($user_birth_f_arr[2]) == 4) {
				$user_birth_f = "'".$user_birth_f_arr[2].'-'.$user_birth_f_arr[1].'-'.$user_birth_f_arr[0]."'";
			}
		}
		
		if ($duongdan == '1') {
			$sql="SELECT *
				  FROM tbl_group g, tbl_user_group ug where g.grp_code = ug.urg_grp_code and UCASE(ug.urg_user_name) = UCASE('".$user_name."')";
				  $grp_img = "";
			$rowID=0;
			//echo '-->'.$sql.'<!--';
			$numrow=$this->querycount($sql);
			if($numrow>0)
			{
				$row = $this->fetch_array();
				$duongdan = $row["grp_img"];
			} else {
				$duongdan = "images/noicon1.png";
			}
		}
		
		$sql="UPDATE tbl_user set      user_fullname = '".$user_fullname."',
									   user_sex = '".$user_sex."',
									   user_birth = ".$user_birth_f.",
									   user_address = '".$user_address."',
									   user_email = '".$user_email."',
									   user_phone = '".$user_phone."',
									   user_img = (select case '".$duongdan."' when '' then user_img
																					else '".$duongdan."'
														  end from dual),
									   user_stat = 'O',
									   user_created = SYSDATE(),
									   user_created_by = UCASE('".$_SESSION['MM_Username']."')
			   WHERE UCASE(user_name) = UCASE('".$_SESSION['MM_Username']."')";
		$numrow=$this->query_sql($sql);
		//echo $sql;

		return $numrow;
	}
	
	function update_customer( 
	$cust_phone_old,
	$cust_id,
	$cust_name,
	$cust_sex,
	$cust_birth,
	$cust_company,
	$cust_address,
	$cust_email,
	$cust_phone,
	$cust_note,
	$fulltext_search,
	$isoverwrite)
	{
		if ($cust_birth=="") {
				$cust_birth_f = 'null';
		} else {
			$cust_birth_f_arr = explode("-",$cust_birth);
			if (strlen($cust_birth_f_arr[2]) == 4) {
				$cust_birth_f = "'".$cust_birth_f_arr[2].'-'.$cust_birth_f_arr[1].'-'.$cust_birth_f_arr[0]."'";
			}
		}
		
		$sql="UPDATE tbl_customer set      cust_name = '".$cust_name."',
										   cust_sex = '".$cust_sex."',
										   cust_birth = ".$cust_birth_f.",
										   cust_company = '".$cust_company."',
										   cust_address = '".$cust_address."',
										   cust_email = '".$cust_email."',
										   cust_phone = '".$cust_phone."',
										   cust_note = '".$cust_note."',
										   cust_fulltext_search = '".$fulltext_search."',
										   cust_stat = 'O',
										   cust_created = SYSDATE(),
										   cust_created_by = UCASE('".$_SESSION['MM_Username']."') ";
		if ($isoverwrite == 1) {
			$sql = $sql." WHERE cust_phone = '".$cust_phone."'";
		} else {
			$sql = $sql." WHERE cust_phone = '".$cust_phone_old."'";
		}
		
		$numrow=$this->query_sql($sql);
		//echo $sql;
		$sql="UPDATE tbl_trans set      trn_cust_phone = '".$cust_phone."'
			   WHERE trn_cust_phone = '".$cust_phone_old."'";
		$numrow=$this->query_sql($sql);
		//echo $sql;

		return $numrow;
	}
	
	function select_tbl_product()
	{
		$this->getConnection();
		$sql="select * from tbl_product t where t.prd_stat = 'O' order by prd_order asc ";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_product_option($prd)
	{
		$this->getConnection();
		$sql="select t.*
			  from tbl_product_type t 
			  where t.tp_stat = 'O' 
			  and t.tp_class='".$prd."'
			  and t.tp_code is null
			  order by t.tp_option_order asc";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_product_option_list($prd, $tp_option_code)
	{
		$this->getConnection();
		$sql="select t.*
			  from tbl_product_type t 
			  where t.tp_stat = 'O' 
			  and t.tp_option_code='".$tp_option_code."'
			  and t.tp_class='".$prd."'
			  and tp_code is not null
			  order by t.tp_order asc, t.tp_code";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function select_tbl_user($grp)
	{
		$this->getConnection();
		$sql="select t.*, t1.*,UCASE(t.urg_user_name) user_name_u 
			  from tbl_user_group t, tbl_user t1 
			  where t.urg_user_name = t1.user_name 
			  and t.urg_grp_code = '".$grp."' 
			  order by t1.user_stat desc ";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function get_end_date_his($id)
	{
		$this->getConnection();
		$sql="select t.trn_end_date_his, t.trn_name from tbl_trans t where t.trn_id = '".$id."'";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function get_payment_his($id,$ref)
	{
		$this->getConnection();
		$sql="select t.* from tbl_payment_hisv2 t where t.trn_ref = '".$ref."' order by payment_id desc";
		$numrow=$this->querycount($sql);
		$rowID=0;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$rowsdata[$rowID]=$row;
					$rowID++;
			}
				
			
		}
		//echo $sql;
		$this->close();
		return $rowsdata;
	}
	
	function FormatDateTime_display($datetime) 
	{
		 if ($datetime=="" or $datetime==null) return null;
		else
		return date('m-d-Y H:i:s', strtotime($datetime));
		
	} 
	function FormatDateTime_index($datetime) 
	{
		
		return date('d-m-Y', strtotime($datetime));
	} 
	
	
	function get_all_cuocgoi_chua_xl_index($isearch,
										   $vw,
										   $step,
										   $user,
										   $userrole,
										   $cust_name,
										   $cust_company,
										   $cust_email,
										   $cust_phone,
										   $cust_end_date,
										   $prg_status,
										   $trn_ref,
										   $trn_name,
										   $trn_prd_code,
										   $lastrow,
										   $ischecked
										   )
	{
		$sqlRef = "";
		$orderCare = "";
		$limit = "";
		
		$trans_fixdate_red_min = -9999;
		$trans_fixdate_red_max = 2;

		$trans_fixdate_yellow_min = 2;
		$trans_fixdate_yellow_max = 5;


		//echo '-->$isearch='.$isearch.'@$ischecked='.$ischecked.'@$limit'.$limit.'<!--';
		//isearch = 2: Sự kiện click nút search thì phân trang
		//isearch = 1: Search theo nhân viên thì loc theo nv đó
		
		//ischecked = 1: Sự kiện kiểm tra xem còn trang tiếp theo hay không
		//ischecked = 2: Sự return trang load more trắng khi bắt đầu vào form search
		//ischecked = 3: Sự kiện click vào nút load more trong form search
		//ischecked = 4: Sự kiện kiểm tra xem còn trang tiếp theo hay không trong form search
		
		$limitrow = $_SESSION['steprow'];
		// Load lần đầu và không phải bắt đàu vào form search => Khi vào thì load số lượng row đã nhớ
		if ($lastrow == 0 && $isearch != 2) {
			$limitrow = $_SESSION['limitrow'];
		} else {
			// là sự kiện bấm xem thêm/không phải sự kiện kiểm tra => Nhớ số row đã load
			if ($ischecked ==0 || $ischecked ==3) {
				//$_SESSION['limitrow'] = $_SESSION['limitrow'] + $_SESSION['steprow'];
			}
		}
		
		if ($_SESSION['sort_col_val'] != null && $_SESSION['sort_col_val'] != ' DESC ' &&  $_SESSION['sort_col_val'] != ' ASC ') {
			$sqlOrder = " order by ".$_SESSION['sort_col_val'];
		} else {
			$sqlOrder = " order by tmp.trn_ref desc,tmp.trn_order asc, tmp.prg_step4_dt2 desc";
		}
		$stepsql = " where 1=1 ";
		if ($isearch > 0) {
			//
		} else {
			if ($step == "SALE") {
				$stepsql = $stepsql." and a.prg_status in (11,12,21,22,23,31,32,41,42,51,52) ";
				if ($vw == "all") $stepsql = $stepsql." and a.prg_status not in (42)";
				elseif ($vw == "pending") $stepsql = $stepsql." and a.prg_status < 42";
				elseif ($vw == "wait") $stepsql = $stepsql." and a.prg_status in (12,23)";
				elseif ($vw == "on") $stepsql = $stepsql." and a.prg_status in (21,22,31,41)";
				elseif ($vw == "complete") {
					$stepsql = $stepsql." and a.prg_status in (42,51,52)";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "hot") $stepsql = $stepsql." and a.trn_end_date is not null and 
				TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_end_date),0)) <= ".$trans_fixdate_red_max." and a.prg_status < 42 ";
				elseif ($userrole == '1') $stepsql = $stepsql." and (a.prg_step4_dt2 is null )";
			}
			elseif ($step == "DESIGN") {
				$stepsql = $stepsql."  and  a.prg_status in (12,21,22,23,31,32,41,42,51,52) ";
				if ($vw == "all") $stepsql = $stepsql." and a.prg_status not in (42)";
				elseif ($vw == "wait") $stepsql = $stepsql." and a.prg_status in (12)";
				elseif ($vw == "on") $stepsql = $stepsql." and a.prg_status in (21,22)";
				elseif ($vw == "complete") {
					$stepsql = $stepsql." and a.prg_status in (23,31,32,41,42,51,52)";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "pending") $stepsql = $stepsql." and a.prg_status in (12,21,22)";
				elseif ($vw == "hot") $stepsql = $stepsql." and a.trn_end_date is not null and 
				TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_end_date),0)) <= ".$trans_fixdate_red_max." and a.prg_status < 42 ";
				elseif ($userrole == '1') $stepsql = $stepsql." and (a.prg_step4_dt2 is null )";
			}
			elseif ($step == "BUILD") {
				$stepsql = $stepsql."  and  a.prg_status in (23,31,32,41,42,51,52) ";
				if ($vw == "all") $stepsql = $stepsql." and a.prg_status not in (42)";
				elseif ($vw == "wait") $stepsql = $stepsql." and a.prg_status in (23)";
				elseif ($vw == "on") $stepsql = $stepsql." and a.prg_status in (31)";
				elseif ($vw == "complete") {
					$stepsql = $stepsql." and a.prg_status in (32,41,42,51,52)";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "pending") $stepsql = $stepsql." and a.prg_status in (23,31)";
				elseif ($vw == "hot") $stepsql = $stepsql." and a.trn_end_date is not null and 
				TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_end_date),0)) <= ".$trans_fixdate_red_max." and a.prg_status < 42 ";
				elseif ($userrole == '1') $stepsql = $stepsql." and (a.prg_step4_dt2 is null )";
			}
			elseif ($step == "DELIVER") {
				$stepsql = $stepsql."  and  a.prg_status in (32,41,42,51,52) ";
				if ($vw == "all") $stepsql = $stepsql." and a.prg_status not in (42)";
				elseif ($vw == "wait") $stepsql = $stepsql." and a.prg_status in (32)";
				elseif ($vw == "on") $stepsql = $stepsql." and a.prg_status in (41)";
				elseif ($vw == "complete") {
					$stepsql = $stepsql." and a.prg_status in (42,51,52)";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "pending") $stepsql = $stepsql." and a.prg_status in (32,41)";
				elseif ($vw == "hot") $stepsql = $stepsql." and a.trn_end_date is not null and 
				TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_end_date),0)) <= ".$trans_fixdate_red_max." and a.prg_status < 42 ";
				elseif ($userrole == '1') $stepsql = $stepsql." and (a.prg_step4_dt2 is null )";
			}
			elseif ($step == "CARE") {
				$sqlOrder = " order by prg_step4_dt2_sort desc, trn_ref desc, prg_status desc, trn_payment_remain desc, prg_status , tmp.trn_order asc, tmp.prg_step1_dt1 ";
				$sqlRef = " inner join (select t.trn_ref, t.prg_step4_dt2 as prg_step4_dt2_sort from tbl_trans t where 1=1 ";
				$stepsql = $stepsql."  ";
				//$limit = ' limit '.$lastrow.', 500 ';
				$orderCare = ' prg_step4_dt2_sort, ';
				if ($vw == "all") 
				{
					$sqlWhereCare = "  ";
					$sqlRef = $sqlRef.$sqlWhereCare." group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
				} elseif ($vw == "debit") 
				{
					$sqlWhereCare = " and t.prg_step4_dt2 is not null ";
					$sqlRef = $sqlRef.$sqlWhereCare." and t.trn_payment_remain > 0 group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
				} 
				elseif ($vw == "wait") 
				{
					$sqlWhereCare = " and t.prg_step4_dt2 is not null ";
					$sqlRef = $sqlRef.$sqlWhereCare." and (t.trn_payment_remain = 0 or t.trn_payment_remain is null) and (t.prg_note is null or t.prg_note ='')  group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "pending") 
				{
					$sqlWhereCare = " and t.prg_step4_dt2 is not null ";
					$sqlRef = $sqlRef.$sqlWhereCare." and (t.trn_payment_remain > 0 or t.prg_note is null or t.prg_note ='')  group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "complete") 
				{
					$sqlWhereCare = " and t.prg_step4_dt2 is not null ";
					$sqlRef = $sqlRef.$sqlWhereCare." and t.prg_note is not null and t.prg_note != ''  group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "unauth") 
				{
					$sqlRef = $sqlRef." and t.trn_payment > 0 group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ";
					$sqlSelect = " select * from ( ";
					$sqlWhere = " ) tmpx where tmpx.payment_lastest_0 > tmpx.payment_lastest_1 and tmpx.payment_status <> '1' order by trn_auth_date desc limit 0, 500";
					//$limit = ' limit '.$lastrow.', 500 ';
				}
				elseif ($userrole == '1') 
				{
					$stepsql = $stepsql." and (a.prg_step4_dt2 is null ) ";
				} else {
					$stepsql = $stepsql." and (a.prg_step4_dt2 is null ) ";
				}
				
				
			} else {
				if ($vw == "wait") $stepsql = $stepsql."  and a.prg_status in (12,23,32)";
				elseif ($vw == "on") $stepsql = $stepsql."  and a.prg_status in (21,22,31,41)";
				elseif ($vw == "pending") $stepsql = $stepsql."  and a.prg_status < 42";
				elseif ($vw == "complete") {
					$stepsql = $stepsql."  and a.prg_status in (42,51,52)";
					$limit = ' limit '.$lastrow.', '.$limitrow.' ';
				}
				elseif ($vw == "hot") $stepsql = $stepsql."  and a.trn_end_date is not null and 
				TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_end_date),0)) <= ".$trans_fixdate_red_max." and a.prg_status < 42 ";
				elseif ($userrole == '1') $stepsql = $stepsql." and (a.prg_step4_dt2 is null )";
			}
		}
		if (($userrole == '1' || // là admin
			strpos($_SESSION['MM_group'],'CARE,') !== false || // là care
			$user == '' || // không có user
			//$isearch == '1' || // Là tìm kiếm theo nhân viên
			$isearch == '2' || // Là click nút search
			$ischecked == 3 || // Sự kiện click vào nút load more trong form search
			$ischecked == 4  // Sự kiện kiểm tra xem còn trang tiếp theo hay không trong form search
			) && ($isearch != '1'))
		{
			$subsql = " tbl_trans ";
			/*if ($isearch == 2 || $ischecked == 3 || $ischecked == 4) {
				$stepsql = " where 1=1 ";
			}*/
		} else {
		//if ((($user != "" && $userrole != '1') || ($isearch == '1' && ) && !(strpos($_SESSION['MM_group'],'CARE,') !== false)) {
			$subsql =  " (select * from tbl_trans a1 where (UCASE(a1.trn_created_by) = UCASE('".$user."') or UCASE(a1.prg_step1_by) = UCASE('".$user."') ";
			$subsql = $subsql." or UCASE(a1.prg_step2_by) = UCASE('".$user."') or UCASE(a1.prg_step3_by) = UCASE('".$user."') ";
			$subsql = $subsql." or UCASE(a1.prg_step4_by) = UCASE('".$user."') or UCASE(a1.prg_step5_by) = UCASE('".$user."') ";
			$subsql = $subsql." or UCASE(a1.prg_pending_by) = UCASE('".$user."'))) ";
		}
		
		if ($cust_name != "")  $stepsql = $stepsql." and b.cust_fulltext_search like '%cust_name@%".$cust_name."%@cust_name%' ";
		if ($cust_company != "")  $stepsql = $stepsql." and b.cust_fulltext_search like '%cust_company@%".$cust_company."%@cust_company%' ";
		if ($cust_email != "")  $stepsql = $stepsql." and UCASE(b.cust_email) like UCASE('%".$cust_email."%') ";
		if ($cust_phone != "")  $stepsql = $stepsql." and b.cust_phone like '%".str_replace(' ','',str_replace('.','',$cust_phone))."%' ";
		if ($cust_end_date != 'null' && $cust_end_date != '')  $stepsql = $stepsql." and a.trn_end_date = ".$cust_end_date." ";
		if ($prg_status != "")  {
			if ($prg_status == 52) {
				//$stepsql = $stepsql." ";
				$sqlOrder = " order by prg_step4_dt2_sort desc, trn_ref desc, prg_status desc, trn_payment_remain desc, prg_status , tmp.trn_order asc, tmp.prg_step1_dt1 ";
				$sqlRef = ' inner join (select t.trn_ref, t.prg_step4_dt2 as prg_step4_dt2_sort from tbl_trans t where t.prg_step4_dt2 is not null 
				and t.prg_step4_dt2 is not null and t.prg_note is not null and t.prg_note != ""';
				$sqlRef = $sqlRef.' group by t.trn_ref ) t1 on t1.trn_ref = a.trn_ref ';
				$orderCare = ' prg_step4_dt2_sort, ';
			
			} else {
				$stepsql = $stepsql." and a.prg_status = '".$prg_status."' ";
			}
		}
		if ($trn_ref != "")  $stepsql = $stepsql." and a.trn_ref = '".$trn_ref."' ";
		if ($trn_name != "")  $stepsql = $stepsql." and a.trn_fulltext_search like '%trn_name@%".$trn_name."%@trn_name%' ";
		if ($trn_prd_code != "")  $stepsql = $stepsql." and UCASE(a.trn_prd_code) = UCASE('".$trn_prd_code."') ";
		
		if ($isearch == '1'|| $isearch == '2'  || $ischecked == '1' || $ischecked == '3' || $ischecked == 4) {
			$limit = ' limit '.$lastrow.', '.$limitrow.' ';
		}
		
		
		//echo '-->$isearch='.$isearch.'@$ischecked='.$ischecked.'@$limit'.$limit.'$'.$step.'%'.$vw.'<br>'.$stepsql.'<br>'.$subsql.'<!--';
		//echo '--><br>$userrole='.$userrole.'@$_SESSION[\'MM_group\']='.$_SESSION['MM_group'].'@$isearch='.$isearch.'$ischecked='.$ischecked.'<!--';
		
		/*$sqlccountpage = "select a.trn_id from ".$subsql." a
		inner join tbl_customer b on b.cust_phone = a.trn_cust_phone
		inner join tbl_product c on c.prd_code = a.trn_prd_code
		left join tbl_user e on UCASE(e.user_name) = UCASE(a.prg_pending_by)
		".$sqlRef." 
		".$stepsql;
		
		if ($cust_phone != "")  {
			$limit = ' ';
			$records= 1;
		} else {
			$records=$this->querycount($sqlccountpage);
		}*/
		$records=100;
		
		//echo '-->'.$sqlccountpage.'@@@'.$records.'z'.'<!--';
		$sql=$sqlSelect."select tmp.*,UCASE(tmp.trn_name) trn_name_f, ";
		if ($step == "CARE") {
			$sql=$sql." case when trn_total_amount = trn_total_pay then '1'
						   else '0'
						   end ispaymentok, ";
		}
		$sql=$sql."'".$records."' records ";
		if ($step == "CARE") {
			$sql=$sql.",(SELECT xx.trn_payment FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref order by xx.payment_id desc limit 0,1) trn_payment_auth,
						   (SELECT xx.payment_status FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref order by xx.payment_id desc limit 0,1) payment_status,
						   (SELECT xx.trn_auth_date FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref order by xx.payment_id desc limit 0,1) trn_auth_date,
						   (SELECT xx.trn_payment_sum_tm FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref order by xx.payment_id desc limit 0,1) trn_payment_sum_tm,
						   (SELECT xx.trn_payment_sum_ck FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref order by xx.payment_id desc limit 0,1) trn_payment_sum_ck,
						   IFNULL((SELECT xx.trn_payment FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref and xx.payment_status = '0' order by xx.payment_id desc limit 0,1),0) payment_lastest_0,
						   IFNULL((SELECT xx.trn_payment FROM `tbl_payment_his` xx where xx.trn_ref = tmp.trn_ref and xx.payment_status = '1' order by xx.payment_id desc limit 0,1),0) payment_lastest_1
						   ";
		}
		
		$sql=$sql.',
						   IFNULL((SELECT sum(xx.trn_payment) FROM `tbl_trans` xx where xx.trn_ref = tmp.trn_ref ),0) trn_payment_all';
		
		$sql=$sql." from 
			  (select a.*, ".$orderCare."
					 FORMAT(a.trn_amount, 0) as trn_amount_f,
					 FORMAT(a.trn_quantity, 0) as trn_quantity_f,
					 FORMAT(a.trn_unit_price, 0) as trn_unit_price_f,
					 FORMAT(a.trn_amount_withoutVAT, 0) as trn_amount_withoutVAT_f,
					 FORMAT(a.trn_payment, 0) as trn_payment_f,
					 FORMAT(a.trn_payment_remain, 0) as trn_payment_remain_f,
					 DATE_FORMAT(a.trn_start_date,'%d-%m-%Y') as trn_start_date_f,
					 DATE_FORMAT(a.prg_step2_dt3,'%d-%m-%Y') as prg_step2_dt3_f,
					 DATE_FORMAT(a.prg_step4_dt2,'%d-%m-%Y') as prg_step4_dt2_f,
					 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_step1_by))
					  ) grp_img_step1,
					  (select GROUP_CONCAT(t1.grp_code SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_step1_by))
					  ) grp_code_step1,
					  
					 (select GROUP_CONCAT(t1.grp_img SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_pending_by))
					  ) grp_img_pending,
					  (select GROUP_CONCAT(t1.grp_code SEPARATOR ',') from tbl_group t1
					  where t1.grp_code in (select t2.urg_grp_code 
											from tbl_user_group t2 
											where UCASE(t2.urg_user_name) = UCASE(a.prg_pending_by))
					  ) grp_code_pending,
					  
					 /*case 
						when a.prg_step2_dt3 is not null then TIMESTAMPDIFF(DAY,DATE(a.trn_start_date) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when a.trn_end_date is not null then TIMESTAMPDIFF(DAY,DATE(a.trn_start_date) , DATE(a.trn_end_date))
						else '(?)'
					 end as date_duration,*/
					 case 
						when (a.trn_end_date is not null and a.trn_end_date != '' and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , DATE(a.trn_end_date))
						when (a.trn_end_date is not null and a.trn_end_date != '' and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , DATE(a.trn_end_date))
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is null) then TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is not null) then TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during))
						when (a.trn_has_file = 1) then TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_start_date),a.trn_during))
						else '(?)'
					 end as today_duration,
					 case 
						when (a.prg_status >= 42) then 999
						when (a.trn_end_date != '' and a.trn_end_date is not null and a.prg_step4_dt2 is null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , DATE(a.trn_end_date)),SIGNED INTEGER)
						when (a.trn_end_date != '' and a.trn_end_date is not null and a.prg_step4_dt2 is not null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , DATE(a.trn_end_date)),SIGNED INTEGER)
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during)),SIGNED INTEGER)
						when (a.prg_step2_dt3 is not null and a.prg_step4_dt2 is not null) then CONVERT(TIMESTAMPDIFF(DAY,DATE(a.prg_step4_dt2) , ADDDATE(DATE(a.prg_step2_dt3),a.trn_during)),SIGNED INTEGER)
						when (a.trn_has_file = 1) then CONVERT(TIMESTAMPDIFF(DAY,DATE(NOW()) , ADDDATE(DATE(a.trn_start_date),a.trn_during)),SIGNED INTEGER)
						else 998
					 end as trn_order,
					 case 
						when a.trn_during is not null then CONCAT('+',a.trn_during)
						else DATE_FORMAT(a.trn_end_date,'%d-%m-%Y')
					 end as trn_end_date_f, ";
			if ($step == "CARE") {
				$sql=$sql." (select max(t.trn_payment) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_pay,
				(select max(t.trn_payment_type_tm) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_pay_tm,
				(select max(t.trn_payment_type_ck) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_pay_ck,
					 (select sum(t.trn_amount) from tbl_trans t where t.trn_ref = a.trn_ref) trn_total_amount, ";
			}
			$sql=$sql." case 
						when a.prg_status = '12' then 'Chưa thiết kế'
						when a.prg_status = '21' then 'Đang thiết kế'
						when a.prg_status = '22' then 'Chưa duyệt tkế'
						when a.prg_status = '23' then 'Chưa sản xuất'
						when a.prg_status = '31' then 'Đang sản xuất'
						when a.prg_status = '32' then 'Chưa giao hàng'
						when a.prg_status = '41' then 'Đang giao hàng'
						when a.prg_status = '42' then 'Đã giao hàng'
						else '(?)'
					 end as prg_status_f,
					 case 
						when a.prg_status = '12' then 'Đang thiết kế'
						when a.prg_status = '21' then 'Đã thiết kế'
						when a.prg_status = '22' then 'Đã duyệt tkế'
						when a.prg_status = '23' then 'Đang sản xuất'
						when a.prg_status = '31' then 'Đã sản xuất'
						when a.prg_status = '32' then 'Đã giao hàng'
						when a.prg_status = '41' then 'Đã giao hàng'
						when a.prg_status = '42' and a.trn_payment_remain > 0 then 'Đã thanh toán'
						else 'Đã chăm sóc'
					 end as prg_action_f,
					 case 
						when a.prg_status = '12' then '10'
						when a.prg_status = '21' then '20'
						when a.prg_status = '22' then '40'
						when a.prg_status = '23' then '50'
						when a.prg_status = '31' then '60'
						when a.prg_status = '32' then '80'
						when a.prg_status = '41' then '90'
						when a.prg_status = '42' then '100'
						else '0'
					 end as prg_percent_f,
					 DATE_FORMAT(a.prg_pending_from_dt,'%d-%m-%Y') as prg_pending_from_dt_f,
					 b.*, 
					 c.*, 
					 e.*,
					 (select tmpu.user_img from tbl_user tmpu where UCASE(tmpu.user_name) = UCASE(a.prg_step1_by)) user_img_step1
		from ".$subsql." a
		inner join tbl_customer b on b.cust_phone = a.trn_cust_phone
		inner join tbl_product c on c.prd_code = a.trn_prd_code
		left join tbl_user e on UCASE(e.user_name) = UCASE(a.prg_pending_by)
		".$sqlRef." 
		".$stepsql.") tmp ".$sqlOrder.$limit.$sqlWhere;
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function search_autocomplete(		   $ignoreid,
										   $cust_name,
										   $cust_company,
										   $cust_email,
										   $cust_phone,
										   $trn_ref,
										   $trn_name,
										   $search_code
										   )
	{
		if ($cust_name == '*') $cust_name == '';
		if ($cust_company == '*') $cust_company == '';
		if ($cust_email == '*') $cust_email == '';
		if ($cust_phone == '*') $cust_phone == '';
		if ($trn_ref == '*') $trn_ref == '';
		if ($trn_name == '*') $trn_name == '';
		
		$stepsql = " where 1=1 ";
		if (($cust_name == "") && ($cust_company == "") && ($cust_email == "") && ($cust_phone == "")) {
			if ($search_code == 1) {
				$stepsql = $stepsql." and a.trn_ref = '".$trn_ref."' ";
			} elseif ($search_code == 2) {
				$stepsql = $stepsql." and a.trn_fulltext_search like '%trn_name@%".$trn_name."%@trn_name%' ";
			}
			
		} else {
			if ($cust_name != "")  $stepsql = $stepsql." and b.cust_fulltext_search like '%cust_name@%".$cust_name."%@cust_name%' ";
			if ($cust_company != "")  $stepsql = $stepsql." and b.cust_fulltext_search like '%cust_company@%".$cust_company."%@cust_company%' ";
			if ($cust_email != "")  $stepsql = $stepsql." and UCASE(b.cust_email) like '%".strtoupper($cust_email)."%' ";
			if ($cust_phone != "")  $stepsql = $stepsql." and b.cust_phone like '%".str_replace(' ','',str_replace('.','',$cust_phone))."%' ";
			if ($ignoreid != "")  $stepsql = $stepsql." and a.trn_id != '".$ignoreid."' ";
		} 
		
		
		$stepsql = $stepsql." limit 0,30 ";
		
		$this->getConnection();
		$sql="select a.*, 
					 IFNULL((SELECT sum(xx.trn_payment) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_payment_all,
					 IFNULL((SELECT sum(xx.trn_amount) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_amount_all,
					 DATE_FORMAT(a.trn_start_date,'%d-%m-%Y') as trn_start_date_f,
					 DATE_FORMAT(a.trn_end_date,'%d-%m-%Y') as trn_end_date_f,
					 DATE_FORMAT(a.trn_end_date,'%Y-%m-%d') as trn_end_date_i,
					 DATE_FORMAT(a.prg_step2_dt3,'%d-%m-%Y') as prg_step2_dt3_f,
					 DATE_FORMAT(a.prg_step4_dt2,'%d-%m-%Y') as prg_step4_dt2_f,
					 
					 case 
						when a.prg_status = '12' then 'Chờ thiết kế'
						when a.prg_status = '21' then 'Đang thiết kế'
						when a.prg_status = '22' then 'Chờ duyệt thiết kế'
						when a.prg_status = '23' then 'Chờ sản xuất'
						when a.prg_status = '31' then 'Đang sản xuất'
						when a.prg_status = '32' then 'Chờ giao hàng'
						when a.prg_status = '41' then 'Đang giao hàng'
						when a.prg_status = '42' then 'Đã giao hàng'
						else '(?)'
					 end as prg_status_f,
					 b.*, 
					 c.*, 
					 e.*,
					 (SELECT xx.trn_payment FROM `tbl_payment_his` xx where xx.trn_ref = a.trn_ref and xx.payment_status ='1' order by xx.payment_id desc limit 0,1) trn_payment_auth
		from tbl_trans a
		inner join tbl_customer b on b.cust_phone = a.trn_cust_phone
		inner join tbl_product c on c.prd_code = a.trn_prd_code
		left join tbl_user e on UCASE(e.user_name) = UCASE(a.prg_pending_by)
		".$stepsql;
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_trans_by_id($id)
	{
		$output = null;
		$stepsql = " where a.trn_id='".$id."'";
		
		$this->getConnection();
		$sql="select a.*, 
					 IFNULL((SELECT sum(xx.trn_payment) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_payment_all,
					 IFNULL((SELECT sum(xx.trn_amount) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_amount_all,
					 DATE_FORMAT(a.trn_start_date,'%d-%m-%Y') as trn_start_date_f,
					 DATE_FORMAT(a.trn_end_date,'%d-%m-%Y') as trn_end_date_f,
					 DATE_FORMAT(a.trn_end_date,'%Y-%m-%d') as trn_end_date_i,
					 DATE_FORMAT(a.prg_step2_dt3,'%d-%m-%Y') as prg_step2_dt3_f,
					 DATE_FORMAT(a.prg_step4_dt2,'%d-%m-%Y') as prg_step4_dt2_f,
					 FORMAT(a.trn_quantity,0) as trn_quantity_f,
					 FORMAT(a.trn_unit_price,0) as trn_unit_price_f,
					 FORMAT(a.trn_amount,0) as trn_amount_f,
					 
					 b.*, 
					 c.*,
					 (SELECT xx.trn_payment FROM `tbl_payment_his` xx where xx.trn_ref = a.trn_ref and xx.payment_status ='1' order by xx.payment_id desc limit 0,1) trn_payment_auth,
					 (SELECT xx.payment_status FROM `tbl_payment_his` xx where xx.trn_ref = a.trn_ref order by xx.payment_id desc limit 0,1) payment_status
		from tbl_trans a
		inner join tbl_customer b on b.cust_phone = a.trn_cust_phone
		inner join tbl_product c on c.prd_code = a.trn_prd_code
		".$stepsql;
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_trans_by_ref($ref)
	{
		$output = null;
		$stepsql = " where a.trn_ref='".$ref."'";
		
		$this->getConnection();
		$sql="select a.*, 
					 IFNULL((SELECT sum(xx.trn_payment) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_payment_all,
					 IFNULL((SELECT sum(xx.trn_amount) FROM `tbl_trans` xx where xx.trn_ref = a.trn_ref ),0) trn_amount_all,
					 DATE_FORMAT(a.trn_start_date,'%d-%m-%Y') as trn_start_date_f,
					 DATE_FORMAT(a.trn_end_date,'%d-%m-%Y') as trn_end_date_f,
					 DATE_FORMAT(a.trn_end_date,'%Y-%m-%d') as trn_end_date_i,
					 DATE_FORMAT(a.prg_step2_dt3,'%d-%m-%Y') as prg_step2_dt3_f,
					 DATE_FORMAT(a.prg_step4_dt2,'%d-%m-%Y') as prg_step4_dt2_f,
					 FORMAT(a.trn_quantity,0) as trn_quantity_f,
					 FORMAT(a.trn_unit_price,0) as trn_unit_price_f,
					 FORMAT(a.trn_amount,0) as trn_amount_f,
					 FORMAT(a.trn_amount_withoutVAT,0) as trn_amount_withoutVAT_f,
					 (select max(t1.trn_payment) from tbl_trans t1 where t1.trn_ref = '".$ref."') trn_payment_max,
					 (select max(t1.trn_payment_remain) from tbl_trans t1 where t1.trn_ref = '".$ref."') trn_payment_remain,
					 
					 b.*, 
					 c.*,
					 IFNULL(d.user_phone,'') user_phone
		from tbl_trans a
		inner join tbl_customer b on b.cust_phone = a.trn_cust_phone
		inner join tbl_product c on c.prd_code = a.trn_prd_code
		left join tbl_user d on UCASE(d.user_name) = UCASE(a.prg_step1_by)
		".$stepsql;
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_staff_by_username($username)
	{
		$output = null;
		$stepsql = " where UCASE(a.user_name)=UCASE('".$username."')";
		
		$this->getConnection();
		$sql="select a.*,DATE_FORMAT(a.user_birth,'%d-%m-%Y') as user_birth_f,
				(select FORMAT(sum(c.trn_amount_withoutVAT),0) from tbl_trans c 
				 where UCASE(c.prg_step1_by) = UCASE('".$username."')
				 and   DATE_FORMAT(c.prg_step4_dt2,'%Y%m') = DATE_FORMAT(NOW(),'%Y%m')
				 and c.prg_issue_dt is null
				) current_amount,
				(select FORMAT(sum(c.trn_amount_withoutVAT),0) from tbl_trans c 
				 where UCASE(c.prg_step1_by) = UCASE('".$username."')
				 and c.prg_step4_dt2 is not null
				 and c.prg_issue_dt is null
				 ) total_amount,
				 (select GROUP_CONCAT(t1.grp_code SEPARATOR ',') from tbl_group t1
				  where t1.grp_code in (select t2.urg_grp_code 
										from tbl_user_group t2 
										where UCASE(t2.urg_user_name) = UCASE('".$username."'))
				  ) user_grp_code
		from tbl_user a
		".$stepsql;
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_timeline()
	{
		$output = null;
		$this->getConnection();
		$sql="SELECT * FROM
			 (SELECT DATE_FORMAT(now(), '%Y%m') timeline,
			         DATE_FORMAT(now(), '%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%m/%Y') monthreport  
			  ) a
			  order by timeline asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_timeline_date()
	{
		$output = null;
		$this->getConnection();
		$sql="SELECT * FROM
			 (SELECT DATE_FORMAT(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), '%Y%m%d') timeline,
			         DATE_FORMAT(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), '%d/%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 1 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 1 DAY), '%d/%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 2 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 2 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 3 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 3 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 4 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 4 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 5 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 5 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 6 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 6 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 7 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 7 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 8 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 8 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 9 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 9 DAY), '%d/%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 10 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 10 DAY), '%d/%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 11 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 11 DAY), '%d/%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 12 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 12 DAY), '%d/%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 13 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 13 DAY), '%d/%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 14 DAY), '%Y%m%d') timeline,
			         DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 14 DAY), '%d/%m/%Y') monthreport
			  ) a
			  order by timeline asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_month()
	{
		$this->getConnection();
		$sql="SELECT * FROM
			 (SELECT DATE_FORMAT(now(), '%Y%m') timeline,
			         DATE_FORMAT(now(), '%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%m/%Y') monthreport 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%m/%Y') monthreport  
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%m/%Y') monthreport
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline,
			         DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%m/%Y') monthreport
			  ) a
			  order by timeline desc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_product()
	{
		$this->getConnection();
		$sql="select *
		from tbl_product a
		order by a.prd_order asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_mark_product($grp)
	{
		$sql="select a.*,b.*,IFNULL(b.mrk_point,'&nbsp;&nbsp;&nbsp;') mrk_point_f
		from tbl_product a
		left join tbl_product_mark b on a.prd_code = b.mrk_prd_code
		and b.mrk_grp_code = '".$grp."'
		and (b.mrk_type = '' or b.mrk_type is null)
		and (b.mrk_option = '' or b.mrk_option is null)
		order by a.prd_order asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		return $output;
	}
	
	function view_mark_type($grp,$prd)
	{
		$sql="select a.*,b.*,IFNULL(b.mrk_point,'&nbsp;&nbsp;&nbsp;') mrk_point_f
		from tbl_product_type a
		left join tbl_product_mark b on a.tp_class = b.mrk_prd_code 
		and (b.mrk_option is null or b.mrk_option ='')
		and b.mrk_grp_code = '".$grp."'
		and a.tp_option_code = b.mrk_type
		
		
		where a.tp_class = '".$prd."'
		
		and (a.tp_code is null or a.tp_code = '')
		order by a.tp_option_order asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		return $output;
	}
	
	function view_mark_opt($grp,$prd,$type)
	{
		$sql="select a.*,b.*,IFNULL(b.mrk_point,'&nbsp;&nbsp;&nbsp;') mrk_point_f
		from tbl_product_type a
		left join tbl_product_mark b on a.tp_class = b.mrk_prd_code 
		and b.mrk_grp_code = '".$grp."'
		and a.tp_option_code = b.mrk_type
		and a.tp_code = b.mrk_option
		
		where a.tp_class = '".$prd."'
		and a.tp_option_code  = '".$type."'
				and (a.tp_code is not null or a.tp_code <> '')

		order by a.tp_option_order asc
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		return $output;
	}
	
	function view_report_product_by_timeline($prd_code,$view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		
		$this->getConnection();
		$sql="select tmp.* from (select timeline, IFNULL(sum(b.trn_amount_withoutVAT),0) total_amount
		from (			  
			  SELECT DATE_FORMAT(now(), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline
			  ) a
		left join tbl_trans b on a.timeline = DATE_FORMAT(b.".$dtfilter.",'%Y%m') and b.trn_prd_code = '".$prd_code."' and b.prg_issue_dt is null
		group by a.timeline) tmp
		order by tmp.timeline
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_timeline_staff($grp,$user_name,$view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
			if (strtoupper($grp) == 'DESIGN') {
				$dtfilter = 'prg_step2_dt3';
			}
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		
		if (strtoupper($grp) == 'SALE') {
			$step_by = " and b.prg_step1_by = '".$user_name."' ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
			$joinmark = "  ";
		} else if (strtoupper($grp) == 'DESIGN') {
			$step_by = " and b.prg_step2_by = '".$user_name."' ";
			$mark = "IFNULL(sum(IF(b.prg_step2_dt3 is not null,c.mrk_point,0)),0)";
			$joinmark = " left join tbl_product_mark c on c.mrk_prd_code = b.trn_prd_code and c.mrk_grp_code = '".$grp."'
						  and b.trn_option like concat('%',c.mrk_type,'=',c.mrk_option,'@%') ";
		} else if (strtoupper($grp) == 'BUILD') {
			$step_by = " and b.prg_step3_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
			$joinmark = " left join tbl_product_mark c on c.mrk_prd_code = b.trn_prd_code and c.mrk_grp_code = '".$grp."'
						  and b.trn_option like concat('%',c.mrk_type,'=',c.mrk_option,'@%') ";
		} else if (strtoupper($grp) == 'DELIVER') {
			$step_by = " and b.prg_step4_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
			$joinmark = " left join tbl_product_mark c on c.mrk_prd_code = b.trn_prd_code and c.mrk_grp_code = '".$grp."'
						  and b.trn_option like concat('%',c.mrk_type,'=',c.mrk_option,'@%') ";
		} else if (strtoupper($grp) == 'CARE') {
			$step_by = " and b.prg_step5_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
			$joinmark = " left join tbl_product_mark c on c.mrk_prd_code = b.trn_prd_code and c.mrk_grp_code = '".$grp."'
						  and b.trn_option like concat('%',c.mrk_type,'=',c.mrk_option,'@%') ";
		} else {
			$step_by = " and b.prg_step1_by = '".$user_name."' ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
		}
		
		if ($_SESSION['report_type'] == 'd') {
			$where = " DATE_FORMAT(b.".$dtfilter.",'%d-%m-%Y') = '".$_SESSION['report_date']."'";
		} else {
			$where = " DATE_FORMAT(b.".$dtfilter.",'%Y%m') = '".$_SESSION['report_month']."'";
		}
		
		$this->getConnection();
		$sql="select tmp.* from (select timeline, ".$mark." total_amount
		from (			  
			  SELECT DATE_FORMAT(now(), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline 
			  ) a
		left join tbl_trans b on a.timeline = DATE_FORMAT(b.".$dtfilter.",'%Y%m') ".$step_by." and b.prg_issue_dt is null
		".$joinmark."
		group by a.timeline) tmp
		order by tmp.timeline
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	
	function view_report_timeline_customer()
	{


		$this->getConnection();
		$sql="select tmp.* from (select timeline, IFNULL(sum(b.trn_amount_withoutVAT),0) total_amount
		from (			  
			  SELECT DATE_FORMAT(now(), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline
			  ) a
		left join tbl_trans b on a.timeline = DATE_FORMAT(b.trn_start_date,'%Y%m') and b.prg_issue_dt is null
		group by a.timeline) tmp
		order by tmp.timeline
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_timeline_customer_old()
	{


		$this->getConnection();
		$sql="select timeline, total_amount
		from (			  
			  select DATE_FORMAT(now(), '%Y%m') timeline, IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(now(), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(now(), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') timeline, IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 2 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 3 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 4 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') timeline ,IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 5 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') timeline ,IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 6 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') and
					 
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 7 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') and
					 
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 8 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') and
					 
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 9 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 10 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 11 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 12 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 13 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  UNION
			  select DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') timeline , IFNULL(sum(t1.trn_amount_withoutVAT),0) total_amount 
					 from tbl_trans t1 where DATE_FORMAT(t1.trn_start_date,'%Y%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') and
							t1.prg_issue_dt is null and
							t1.trn_cust_phone in 
							(select distinct t2.trn_cust_phone tbl_trans from tbl_trans t2 
							 where DATE_FORMAT(DATE_SUB(now(), INTERVAL 14 MONTH), '%Y%m') > DATE_FORMAT(t2.trn_start_date,'%Y%m')
							)
			  ) a
		order by a.timeline
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_timeline_staff_date($grp,$user_name,$view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
			if (strtoupper($grp) == 'DESIGN') {
				$dtfilter = 'prg_step2_dt3';
			}
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		
		if (strtoupper($grp) == 'SALE') {
			$step_by = " and b.prg_step1_by = '".$user_name."' ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
		} else if (strtoupper($grp) == 'DESIGN') {
			$step_by = " and b.prg_step2_by = '".$user_name."' ";
			
			$mark = "IFNULL(sum(IF(b.prg_step2_dt3 is not null,c.mrk_point,0)),0)";
		} else if (strtoupper($grp) == 'BUILD') {
			$step_by = " and b.prg_step3_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
		} else if (strtoupper($grp) == 'DELIVER') {
			$step_by = " and b.prg_step4_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
		} else if (strtoupper($grp) == 'CARE') {
			$step_by = " and b.prg_step5_by = '".$user_name."' ";
			$mark = "IFNULL(sum(c.mrk_point),0)";
		} else {
			$step_by = " and b.prg_step1_by = '".$user_name."' ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
		}
		
		$this->getConnection();
		$sql="select tmp.* from (select timeline, timeline1, ".$mark." total_amount
		from (			  
			  SELECT DATE_FORMAT(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 1 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 1 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 2 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 2 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 3 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 3 DAY), '%Y%m%d') timeline1
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 4 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 4 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 5 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 5 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 6 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 6 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 7 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 7 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 8 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 8 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 9 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 9 DAY), '%Y%m%d') timeline1 
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 10 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 10 DAY), '%Y%m%d') timeline1
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 11 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 11 DAY), '%Y%m%d') timeline1
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 12 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 12 DAY), '%Y%m%d') timeline1
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 13 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 13 DAY), '%Y%m%d') timeline1
			  UNION
			  SELECT DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 14 DAY), '%d-%m-%Y') timeline ,
					 DATE_FORMAT(DATE_SUB(STR_TO_DATE('".$_SESSION['report_date']."', '%d-%m-%Y'), INTERVAL 14 DAY), '%Y%m%d') timeline1
			  ) a
		left join tbl_trans b on a.timeline = DATE_FORMAT(b.".$dtfilter.",'%d-%m-%Y') ".$step_by." and b.prg_issue_dt is null
		left join tbl_product_mark c on c.mrk_prd_code = b.trn_prd_code and c.mrk_grp_code = '".$grp."' and b.trn_option like concat('%',c.mrk_type,'=',c.mrk_option,'@%')
		group by a.timeline) tmp
		order by tmp.timeline1
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	
	function view_report_product_by_lastmonth($view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		
		$this->getConnection();
		$sql="	select tmp.*, 
				FORMAT(tmp.total_amount,0) total_amount_f,
				FORMAT(tmp.total_quantity,0) total_quantity_f,
				FORMAT(tmp1.total_issue,0) total_issue_f,
				FORMAT(tmp.speed/total_order_design,0) speed_f
				from(select a.prd_order, a.prd_code, 
							IFNULL(sum(b.trn_amount_withoutVAT),0) total_amount, 
							IFNULL(sum(b.trn_quantity),0) total_quantity,
							IFNULL(sum(IF(b.trn_has_file = 1,0,1)),0) total_order_design,
							sum(IF(b.trn_has_file = 1,0,IFNULL(TIMESTAMPDIFF(SECOND,b.trn_created,prg_step2_dt3),0)))/60/60 speed
				from tbl_product a
				left join tbl_trans b on a.prd_code = b.trn_prd_code and DATE_FORMAT(b.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']." and b.prg_issue_dt is null
				group by a.prd_code ) tmp,
				
				(select a.prd_order, a.prd_code, 
						IFNULL(sum(b.prg_issue_value),0) total_issue
				from tbl_product a
				left join tbl_trans b on a.prd_code = b.trn_prd_code and DATE_FORMAT(b.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']." 
				group by a.prd_code ) tmp1
				WHERE tmp.prd_code = tmp1.prd_code
				order by tmp.prd_order asc
				";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_report_user_lastmonth($grp,$view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
			if (strtoupper($grp) == 'DESIGN') {
				$dtfilter = 'prg_step2_dt3';
			}
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		$tooltip = "";
		$speed = "0";
		$holiday = " and d_day in ('1') ";
		$count_notyet = '\'\'';
		if (strtoupper($grp) == 'SALE') {
			$step_by = " UCASE(prg_step1_by) ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
			$speed = "sum(IF(b.trn_has_file = 1,IFNULL(TIMESTAMPDIFF(SECOND,b.trn_created,prg_step3_dt1) - (select count(*) * 24 * 60 * 60 FROM `tbl_date` WHERE d_today > IFNULL(trn_created,NOW()) and d_today < IFNULL(prg_step3_dt1,NOW())  ".$holiday."),0),IFNULL(TIMESTAMPDIFF(SECOND,b.trn_created,prg_step2_dt1)- (select count(*) * 24 * 60 * 60 FROM `tbl_date` WHERE d_today > IFNULL(trn_created,NOW()) and d_today < IFNULL(prg_step2_dt1,NOW())  ".$holiday."),0)))";
			$count_notyet = "sum(IF(b.prg_step2_dt1 is null,IF(b.trn_has_file = 1,0,1),0))";
			$tooltip="Chưa thiết kế / Tổng đơn hàng";
			
		} else if (strtoupper($grp) == 'DESIGN') {
			$step_by = " UCASE(prg_step2_by) ";
			$mark = "IFNULL(sum(IF(b.prg_step2_dt3 is not null,a.mrk_point,0)),0)";
			$speed = "sum(IF(b.trn_has_file = 1,0,IFNULL(TIMESTAMPDIFF(SECOND,b.trn_created,prg_step2_dt3)- (select count(*) * 24 * 60 * 60 FROM `tbl_date` WHERE d_today > IFNULL(trn_created,NOW()) and d_today < IFNULL(prg_step2_dt3,NOW())  ".$holiday."),0))) ";
			$count_notyet = "sum(IF(b.prg_step2_dt3 is null,IF(b.trn_has_file = 1,0,1),0))";
			$tooltip="Chưa duyệt thiết kế / Tổng đơn hàng";
			$joinmark = " left join tbl_product_mark a on a.mrk_prd_code = b.trn_prd_code and a.mrk_grp_code = '".$grp."' and b.trn_option like concat('%',a.mrk_type,'=',a.mrk_option,'@%')
				";
		} else if (strtoupper($grp) == 'BUILD') {
			$step_by = " UCASE(prg_step3_by) ";
			$mark = "IFNULL(sum(a.mrk_point),0)";
			$speed = "sum(IF(b.trn_has_file = 1,IFNULL(TIMESTAMPDIFF(SECOND,b.trn_created,prg_step3_dt2),0),IFNULL(TIMESTAMPDIFF(SECOND,b.prg_step2_dt3,prg_step3_dt2),0))) ";
			$count_notyet = "sum(IF(b.prg_status > 22 and b.prg_status < 32,1,0))";
			$tooltip="Chưa sản xuất / Tổng đơn hàng";
			$joinmark = " left join tbl_product_mark a on a.mrk_prd_code = b.trn_prd_code and a.mrk_grp_code = '".$grp."' and b.trn_option like concat('%',a.mrk_type,'=',a.mrk_option,'@%')
				";
		} else if (strtoupper($grp) == 'DELIVER') {
			$step_by = " UCASE(prg_step4_by) ";
			$mark = "IFNULL(sum(a.mrk_point),0)";
			$speed = "sum(IFNULL(TIMESTAMPDIFF(SECOND,b.prg_step3_dt2,prg_step4_dt2),0)) ";
			$count_notyet = "sum(IF(b.prg_status > 31 and b.prg_status < 42,1,0))";
			$tooltip="Chưa giao hàng / Tổng đơn hàng";
			$joinmark = " left join tbl_product_mark a on a.mrk_prd_code = b.trn_prd_code and a.mrk_grp_code = '".$grp."' and b.trn_option like concat('%',a.mrk_type,'=',a.mrk_option,'@%')
				";
		} else if (strtoupper($grp) == 'CARE') {
			$step_by = " UCASE(prg_step5_by) ";
			$mark = "IFNULL(sum(a.mrk_point),0)";
		} else {
			$step_by = " UCASE(prg_step1_by) ";
			$mark = "IFNULL(sum(b.trn_amount_withoutVAT),0)";
		}
		
		if ($_SESSION['report_type'] == 'd') {
			$where = " DATE_FORMAT(b.".$dtfilter.",'%d-%m-%Y') = '".$_SESSION['report_date']."' ";
		} else {
			$where = " DATE_FORMAT(b.".$dtfilter.",'%Y%m') = '".$_SESSION['report_month']."' ";
		}
		
		$this->getConnection();
		$sql="select tmp.*, FORMAT(tmp.total_amount- tmp1.total_issue,0) total_amount_f,
							FORMAT(tmp.total_quantity,0) total_quantity_f,
							FORMAT(tmp.total_order,0) total_order_f,
							FORMAT(tmp1.total_issue,0) total_issue_f,
							FORMAT(tmp.speed/total_order_design,0) speed_f,
							FORMAT(tmp.total_order_notyet,0) total_order_notyet_f,
					        '".$tooltip."' tooltip
		from (	select ".$step_by." trn_user, 
					   ".$mark." total_amount, 
					   ".$speed."/60/60 speed, 
					   IFNULL(sum(b.trn_quantity),0) total_quantity,
					   IFNULL(count(b.trn_ref),0) total_order,
					   IFNULL(sum(IF(b.trn_has_file = 1,0,1)),0) total_order_design,
					   ".$count_notyet." total_order_notyet
				from tbl_trans b
				".$joinmark."
				where ".$where." 
				group by ".$step_by." ) tmp,
			 (	select ".$step_by." trn_user, 
					   IFNULL(sum(case when prg_issue_from = ".$step_by." then b.prg_issue_value else 0 end),0) total_issue
				from tbl_trans b
				".$joinmark."
				where ".$where." 
				group by ".$step_by." ) tmp1
		WHERE tmp.trn_user = tmp1.trn_user
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_orderstatus_lastmonth($view)
	{
		if ($view == 0) {
			$dtfilter = 'prg_step4_dt2';
		} elseif ($view == 1) {
			$dtfilter = 'trn_start_date';
		}
		
		$this->getConnection();
		$sql="	select  FORMAT(tmp1.total_amount_complete - tmp4.issue_amount,0) total_amount_complete_f,
						FORMAT(tmp2.payment_remain_amount_all,0) payment_remain_amount_all_f,
						FORMAT(tmp3.payment_remain_amount_notyet,0) payment_remain_amount_notyet_f,
						FORMAT(tmp4.issue_amount,0) issue_amount_f
				from    (
							select round(IFNULL(sum(a.trn_payment),0)/1000) total_amount_complete
							from (select max(t1.trn_payment) trn_payment, t1.trn_ref 
								 from tbl_trans t1 
								 where DATE_FORMAT(t1.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']."
								 group by t1.trn_ref
								 ) a 
						) 	tmp1,
						(
							select round(IFNULL(sum(a.trn_payment_remain),0)/1000) payment_remain_amount_all
							from (select max(t1.trn_payment_remain) trn_payment_remain, t1.trn_ref 
								 from tbl_trans t1 
								 where t1.prg_step4_dt2 is not null
								 and DATE_FORMAT(t1.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']."
								 group by t1.trn_ref
								 ) a  
						) 	tmp2,
						(
							select round(IFNULL(sum(a.trn_payment_remain),0)/1000) payment_remain_amount_notyet
							from (select max(t1.trn_payment_remain) trn_payment_remain, t1.trn_ref 
								 from tbl_trans t1 
								 where t1.prg_step4_dt2 is null
								 and DATE_FORMAT(t1.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']."
								 group by t1.trn_ref
								 ) a  
						) 	tmp3,
						(
							select round(IFNULL(sum(a.prg_issue_value),0)/1000) issue_amount
							from (select sum(t1.prg_issue_value) prg_issue_value 
								 from tbl_trans t1 
								 where DATE_FORMAT(t1.".$dtfilter.",'%Y%m') = ".$_SESSION['report_month']."
								 ) a 
						) 	tmp4
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	
	/*function view_topvalueCust_lastmonth()
	{
		$this->getConnection();
		$sql="	select tmp.* 
				from (	select round(b.total_payment/1000) total_payment,
							   a.trn_cust_phone,
							   (select t2.cust_name from tbl_customer t2 where t2.cust_phone = a.trn_cust_phone limit 0,1) cust_name,
							   round(IFNULL(sum(a.trn_payment),0)/1000) total_amount_complete,
							   round(IFNULL(sum(a.trn_payment),0) * 100/b.total_payment) per_amount_complete
						from tbl_trans a,
							(select sum(t1.trn_payment) total_payment
							 from tbl_trans t1 
							 where DATE_FORMAT(t1.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']."
							 ) b
						where DATE_FORMAT(a.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']."
						group by a.trn_cust_phone
					  ) tmp
				order by tmp.total_amount_complete desc 
					 
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}*/
	
	function view_topvalueCust_lastmonth()
	{
		$this->getConnection();
		$sql="	select tmp.*, c.*
				from (	select round(b.total_amount/1000) total_amount,
							   a.trn_cust_phone,
							   
							   round(IFNULL(sum(a.trn_amount_withoutVAT),0)/1000) total_amount_complete,
							   round(IFNULL(sum(a.trn_amount_withoutVAT),0) * 100/b.total_amount) per_amount_complete
						from tbl_trans a,
							(select sum(t1.trn_amount_withoutVAT) total_amount
							 from tbl_trans t1 
							 where DATE_FORMAT(t1.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']." and t1.prg_issue_dt is null
							 ) b
						where DATE_FORMAT(a.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']." and a.prg_issue_dt is null
						group by a.trn_cust_phone
					  ) tmp
				INNER JOIN tbl_customer c on c.cust_phone = tmp.trn_cust_phone
				order by tmp.total_amount_complete desc 
					 
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_topreturnCust_lastmonth()
	{
		$this->getConnection();
		$sql="	select tmp.* , c.*
				from (	select round(b.total_id) total_id,
							   a.trn_cust_phone,
							   
							   round(IFNULL(count(a.trn_id),0)) total_id_complete,
							   round(IFNULL(count(a.trn_id),0) * 100/b.total_id) per_id_complete
						from tbl_trans a,
							(select count(t1.trn_id) total_id
							 from tbl_trans t1 
							 where DATE_FORMAT(t1.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']."
							 ) b
						where DATE_FORMAT(a.prg_step4_dt2,'%Y%m') = ".$_SESSION['report_month']."
						group by a.trn_cust_phone
					  ) tmp
				INNER JOIN tbl_customer c on c.cust_phone = tmp.trn_cust_phone
				order by tmp.total_id_complete desc 
					 
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function view_listCust_all($from,$to)
	{
		$trn_start_date = "00000000";
		$trn_end_date = "99999999";
		if ($from!="") {
				$trn_start_dateArr = explode("-",$from);

				if (strlen($trn_start_dateArr[2]) == 4) {

					$trn_start_date = $trn_start_dateArr[2].$trn_start_dateArr[1].$trn_start_dateArr[0];

				}
		  }

		  else {

				$trn_start_date = "00000000";
		  }
		  
		  
		  if ($to!="") {
				$trn_end_dateArr = explode("-",$to);

				if (strlen($trn_end_dateArr[2]) == 4) {

					$trn_end_date = $trn_end_dateArr[2].$trn_end_dateArr[1].$trn_end_dateArr[0];

				}
		  }

		  else {

				$trn_end_date = "99999999";
		  }

		if ($from == 'null') {
			$subsql = ' ';
		} else {
			$subsql_a = " where DATE_FORMAT(a.trn_start_date,'%Y%m%d') >= '".$trn_start_date."'
						and DATE_FORMAT(a.trn_start_date,'%Y%m%d') <= '".$trn_end_date."' ";
			$subsql_t1 = " where DATE_FORMAT(t1.trn_start_date,'%Y%m%d') >= '".$trn_start_date."'
						and DATE_FORMAT(t1.trn_start_date,'%Y%m%d') <= '".$trn_end_date."' ";
			$subsql_t2 = " where DATE_FORMAT(t2.trn_start_date,'%Y%m%d') >= '".$trn_start_date."'
						and DATE_FORMAT(t2.trn_start_date,'%Y%m%d') <= '".$trn_end_date."' ";
		}
		
		$this->getConnection();
		$sql="	select GROUP_CONCAT(d.trn_start_date SEPARATOR '<br>') start_date_list,
								  GROUP_CONCAT(d.prg_step4_dt2_max SEPARATOR '<br>') end_date_list
								  , c.cust_name, cust_sex, cust_company,cust_email, cust_phone, cust_address, 
					
					   count(d.prg_step4_dt2_max) order_count,
					   total_amount_complete
				from (	select round(b.total_amount) total_amount,
							   a.trn_cust_phone,
							   
							   round(IFNULL(sum(a.trn_amount_withoutVAT),0)) total_amount_complete,
							   round(IFNULL(sum(a.trn_amount_withoutVAT),0) * 100/b.total_amount) per_amount_complete
						from tbl_trans a,
							(select sum(t1.trn_amount_withoutVAT) total_amount
							 from tbl_trans t1 
							 ".$subsql_t1."
							 ) b
						
						".$subsql_a."
						group by a.trn_cust_phone
						order by a.prg_step4_dt2 desc
					  ) tmp
				INNER JOIN tbl_customer c on c.cust_phone = tmp.trn_cust_phone
				INNER JOIN (select IFNULL(max(t2.prg_step4_dt2),'&nbsp;') prg_step4_dt2_max,
									t2.trn_ref,
									t2.trn_cust_phone,
									t2.trn_start_date
							 FROM tbl_trans t2 
							 
							 ".$subsql_t2."
							 GROUP BY t2.trn_ref,t2.trn_cust_phone
							 order by IFNULL(max(t2.prg_step4_dt2),' ') desc 
							 ) d 
							 ON d.trn_cust_phone = tmp.trn_cust_phone
				GROUP BY tmp.trn_cust_phone
				order by c.cust_name
					 
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		
		//echo 'QQQQ'.$numrow.'ddd';
		$_SESSION['custlist'] = $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	
	function export_excel_trans($phone,$from,$to)
	{
		$trn_start_date = "00000000";
		$trn_end_date = "99999999";
			if ($from!="") {
				$trn_start_dateArr = explode("-",$from);

				if (strlen($trn_start_dateArr[2]) == 4) {

					$trn_start_date = $trn_start_dateArr[2].$trn_start_dateArr[1].$trn_start_dateArr[0];

				}
		  }

		  else {

				$trn_start_date = "00000000";
		  }
		  
		  
		  if ($to!="") {
				$trn_end_dateArr = explode("-",$to);

				if (strlen($trn_end_dateArr[2]) == 4) {

					$trn_end_date = $trn_end_dateArr[2].$trn_end_dateArr[1].$trn_end_dateArr[0];

				}
		  }

		  else {

				$trn_end_date = "99999999";
		  }
		  
		$subsql = " where 1=1 ";
		
		if ($phone != '') {
			$subsql = $subsql." and a.trn_cust_phone = ".$phone." ";
		}
		
		if ($from != 'null') {
			$subsql = $subsql." and DATE_FORMAT(a.trn_start_date,'%Y%m%d') >= ".$trn_start_date." ";
		}
		
		if ($to != 'null') {
			$subsql = $subsql." and DATE_FORMAT(a.trn_start_date,'%Y%m%d') <= ".$trn_end_date." ";
		}
		
		$this->getConnection();
		$sql="	select  IFNULL(a.trn_ref,' '), 
						IFNULL(DATE_FORMAT(a.trn_start_date,'%d/%m/%Y'),' '), 
						' ',
						IFNULL(b.cust_name,' '), 
						IFNULL(b.cust_email,' '), 
						IFNULL(b.cust_phone,' '), 
						IFNULL(b.cust_company,' '), 
						IFNULL(a.trn_name,' '), 
						IFNULL(a.trn_detail,' '),
						'fennex',
						IFNULL(FORMAT(a.trn_quantity,0),' '), 
						IFNULL(REPLACE(REPLACE(a.trn_option,'@','<br>'),'=',':'),' '), 
						IFNULL(FORMAT(a.trn_unit_price,0),' '), 
						' ',
						' ',
						IFNULL(FORMAT(a.trn_amount,0),' '), 
						IFNULL(a.trn_created_by,' '), 
						IFNULL(FORMAT(a.trn_payment,0),' '), 
						(select p.trn_createdby from tbl_payment_hisv2 as p where p.trn_ref = a.trn_ref limit 0,1)
				from tbl_trans a
				INNER JOIN tbl_customer b on b.cust_phone = a.trn_cust_phone
				".$subsql."
				order by trn_start_date desc 
					 
		";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo 'aaaa'.$numrow.'qqqq';
		//echo $sql;
		//$_SESSION['export_excel_trans'] = $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		
		$this->close();
		return $output;
	}
	
	function get_cust_by_phone($phone)
	{
		$stepsql = " where TRIM(a.cust_phone) like '%".$phone."%' ";
		
		$this->getConnection();
		$sql="select a.*,DATE_FORMAT(a.cust_birth,'%d-%m-%Y') as cust_birth_f
		from tbl_customer a
		".$stepsql;
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	
	function get_cust_debit_by_month()
	{
		 if ($_SESSION['report_date_from']!="") {
				$trn_start_dateArr = explode("-",$_SESSION['report_date_from']);

				if (strlen($trn_start_dateArr[2]) == 4) {

					$trn_start_date = $trn_start_dateArr[2].$trn_start_dateArr[1].$trn_start_dateArr[0];

				}
		  }

		  else {

				$trn_start_date = "00000000";
		  }
		  
		  
		  if ($_SESSION['report_date_to']!="") {
				$trn_end_dateArr = explode("-",$_SESSION['report_date_to']);

				if (strlen($trn_end_dateArr[2]) == 4) {

					$trn_end_date = $trn_end_dateArr[2].$trn_end_dateArr[1].$trn_end_dateArr[0];

				}
		  }

		  else {

				$trn_end_date = "99999999";
		  }
			  
		$this->getConnection();
		$sql=" 		SELECT tmp.*,
						   (select  sum(xx1.trn_amount)
						   from (select xx.trn_amount,  
								           case when xx.prg_step4_dt2 is null then 'Ch&#432;a tr&#7843; h&#224;ng'
										    else DATE_FORMAT(xx.trn_start_date,'%Y%m') end as trn_start_date_group
								   from `tbl_trans` xx where 
								   DATE_FORMAT(xx.trn_start_date,'%Y%m%d') >= '".$trn_start_date."'
							   and DATE_FORMAT(xx.trn_start_date,'%Y%m%d') <= '".$trn_end_date."') xx1
							   where xx1.trn_start_date_group = tmp.trn_start_date_order) total_amount
					FROM
					  (select * from (SELECT 
							  DATE_FORMAT(a.prg_step4_dt2,'%Y%m') as prg_step4_dt2_order,
							  case when a.prg_step4_dt2 is null then 'Ch&#432;a tr&#7843; h&#224;ng'
							  else DATE_FORMAT(a.trn_start_date,'%Y%m') end as trn_start_date_order,
							  
							  
							  IFNULL(DATE_FORMAT(a.prg_step4_dt2,'%m-%Y'),'Ch&#432;a tr&#7843; h&#224;ng') AS prg_step4_dt2,
							  sum(ifnull(a.trn_amount,0) - ifnull(a.trn_payment,0)) AS trn_payment_remain
					   FROM `tbl_trans` a
					   
					   WHERE DATE_FORMAT(a.trn_start_date,'%Y%m%d') >= '".$trn_start_date."'
					   and DATE_FORMAT(a.trn_start_date,'%Y%m%d') <= '".$trn_end_date."'
					   GROUP BY DATE_FORMAT(a.prg_step4_dt2,'%m-%Y')) tmp1 
					   ) tmp
					ORDER BY tmp.trn_start_date_order asc, tmp.prg_step4_dt2_order asc ";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_cust_debit($phone)
	{
		$this->getConnection();
		$sql=" 		SELECT /*a.cust_name,*/
						   tmp.*
					FROM
					  (select * from (SELECT a.trn_cust_phone,
							  /*DATE_FORMAT(a.trn_start_date,'%m-%Y') AS trn_start_date,*/
							  DATE_FORMAT(a.prg_step4_dt2,'%Y%m') as prg_step4_dt2_order,
							  DATE_FORMAT(a.trn_start_date,'%Y%m') as trn_start_date_order,
							  IFNULL(DATE_FORMAT(a.prg_step4_dt2,'%m-%Y'),'Ch&#432;a tr&#7843; h&#224;ng') AS prg_step4_dt2,
							  sum(ifnull(a.trn_amount,0) - ifnull(b.trn_payment,0)) AS trn_payment_remain
					   FROM `tbl_trans` a
					   left join (select x.trn_id, 
										 x.trn_ref, 
										 x.trn_start_date,
										 x.prg_step4_dt2,
							MAX(ifnull(x.trn_payment,0)) as trn_payment from tbl_trans x group by x.trn_ref) b on a.trn_id = b.trn_id
					   
					   GROUP BY /*DATE_FORMAT(a.trn_start_date,'%m-%Y'),*/
								DATE_FORMAT(a.prg_step4_dt2,'%m-%Y'),
								a.trn_cust_phone) tmp1 
					   ) tmp
					/*INNER JOIN tbl_customer a ON a.cust_phone = tmp.trn_cust_phone */
					WHERE tmp.trn_cust_phone = '$phone' 
					ORDER BY tmp.trn_start_date_order asc, tmp.prg_step4_dt2_order asc, tmp.prg_step4_dt2 DESC ";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_user_issue_list($trn_id)
	{
		$this->getConnection();
		$sql="select tmp.* from (select prg_step1_by prg_issue_from from tbl_trans where trn_id='".$trn_id."'";
		$sql=$sql."union select prg_step2_by prg_issue_from from tbl_trans where trn_id='".$trn_id."'";
		$sql=$sql."union select prg_step3_by prg_issue_from from tbl_trans where trn_id='".$trn_id."'";
		$sql=$sql."union select prg_step4_by prg_issue_from from tbl_trans where trn_id='".$trn_id."') tmp where tmp.prg_issue_from is not null";
		
		$numrow=$this->querycount($sql);
		$rowID=0;
		echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
	
	function get_last_payment_auth($trn_id)
	{
		$this->getConnection();
		$sql="select a.* from tbl_payment_his a";
		$numrow=$this->querycount($sql);
		$rowID=0;
		//echo $sql;
		if($numrow>0)
		{
			while($row=$this->fetch_array())
			{
					$output[$rowID]=$row;
					$rowID++;
			}
		}
		//echo $sql;
		$this->close();
		return $output;
	}
}
	?>
