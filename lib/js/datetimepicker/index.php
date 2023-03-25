<!DOCTYPE html>
<html lang="vi">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
</head>
<body>

	<form name="frm" action="">
	Từ ngày <input type="text" name="gettime_start" id="datetimepicker_start_time"/> Đến ngày
		<input type="text" name="gettime_end" id="datetimepicker_end_time"/>
		<input type="submit" name="submit" value="check"/>
	</form>
	<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.datetimepicker.js"></script>
<script type="text/javascript">
$('#datetimepicker').datetimepicker()
	.datetimepicker({value:'2015/04/15 05:03',step:10});



$('#datetimepicker_start_time').datetimepicker({
	startDate:'+1970/05/01'
});


$('#datetimepicker_end_time').datetimepicker({
	startDate:'+2070/05/01'
});
</script>
</body>

</html>


<?php

function FormatDateTime($datetime) {
    return date('Y-m-d H:i:s', strtotime($datetime));
} 
if(isset($_REQUEST["submit"]))
{
	$time_str=$_REQUEST["gettime_start"];
	$time_end=$_REQUEST["gettime_end"];
	echo $time_str." to ".$time_end;
	echo "<br>";
	$convert_str=FormatDateTime($time_str);
	$convert_end=FormatDateTime($time_end);
	echo "Chuỗi để insert vào database: <br>";
	echo "Tìm kiếm Từ ngày : ".$convert_str."đến ngày : ".$convert_end;

	

}
?>