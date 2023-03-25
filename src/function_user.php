<?php 
class function_user
{
var $mysqlIns;
var $component;
/*var $Popupfile;
var $filenamepopup;
var	$filetypepopup;
var	$filesizepopup;
var	$upfilepopup;
*/



function get_checkbox($alias)
{
$checkarray=array(
			"1"=>"Đến viện lần đầu",
			"2"=>"Đi khám lại",
			"3"=>"Từ tuyến khác chuyển đến",
			"4"=>"Phụ nữ có thai",
			"5"=>"Người cao tuổi",
			"6"=>"Trẻ em nhỏ",
			"7"=>"Người khuyết tật",
			"8"=>"Bệnh truyền nhiễm",
			"9"=>"Thể trạng yếu"
		
		
	);
	for($i=1;$i<=sizeof($checkarray);$i++)
	{
		if($alias==$i) return $checkarray[$i];;

	}
				
}
function FormatDateTime($datetime) {
    return date('d-m-Y H:i', strtotime($datetime));
} 
function view_name_cat($cat_id){
		if($cat_id==1) echo "Cần bán";
		elseif($cat_id==2) echo "Cho thuê";
		elseif($cat_id==3) echo "Cần mua";
		elseif($cat_id==4) echo "Cần thuê";
	
	}
function get_level_member($level)
{

if($level==1) echo "Bình thường";
		elseif($level==2) echo "Thành viên VIP";
		elseif($level==3) echo "Thành viên PRO";

}
function uploadFile($file,$uptodir) 
{
			global $up_path;
			$Popupfile=$file;
			$filenamepopup=$Popupfile['name'];
			$filenamepopup=str_replace(" ","",$filenamepopup);
			$filetypepopup=$Popupfile['type'];
			$filesizepopup=$Popupfile['size'];
			$upfilepopup=$Popupfile['tmp_name'];
			$up_path=$uptodir.$filenamepopup;
			if(($filenamepopup!="") && (!file_exists($up_path)))
			{
			move_uploaded_file($upfilepopup,$up_path);
			return  $filenamepopup;
			}
			elseif(($filenamepopup!="") && (file_exists($up_path)))
			{
			//$upfilepopup=rename($upfilepopup,$upfilepopup.rand(0,20));
			$code=rand(10,500);
			rename($uptodir.$filenamepopup,$uptodir.$code.$filenamepopup);
			//die( $up_path);
			move_uploaded_file($upfilepopup,$uptodir.$code.$filenamepopup);
			return $code.$filenamepopup;
			}
			//else echo 'not exit name file'	;
			
			//echo sizeof($filesizepopup);
	
}

function upload_file($file_name,$filsize,$uptodir)
{
		$_FILES["file"]["name"]=$file_name;
		//global $up_path;
		$up_path=$uptodir.$_FILES["file"]["name"];
		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/bmp")
		|| ($_FILES["file"]["type"] == "application/x-shockwave-flash"))
		&& ($_FILES["file"]["size"] < $filsize))
		  {
		  if ($_FILES["file"]["error"] > 0)
		    {
		    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		    }
		  else
		    {
		  /*  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		    echo "Kiểu file: " . $_FILES["file"]["type"] . "<br />";
		    echo "Kích thước: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			*/
		      if (file_exists($up_path))
		      {
			  
			  $code=rand(1000,50000);
			  rename($uptodir.$_FILES["file"]["name"],$uptodir.$code.$_FILES["file"]["name"]);
			  move_uploaded_file($_FILES["file"]["tmp_name"],$uptodir.$code.$_FILES["file"]["name"]);
		     // echo "<b>Upload thành công</b>";
				return $code.$_FILES["file"]["name"];
		      }
		    else
		      {
			  $code=rand(100000,5000000);
			 // rename($uptodir.$_FILES["file"]["name"],$uptodir.$code.$_FILES["file"]["name"]);
			$uptodir.$code.$_FILES["file"]["name"];
		      move_uploaded_file($_FILES["file"]["tmp_name"],
		      $uptodir.$code.$_FILES["file"]["name"]);
			  return $code.$_FILES["file"]["name"];
		    /*  echo "Thư mục:<img src=".$uptodir.$code.$_FILES["file"]["name"]."> ".$up_path."<br>";
			  echo "<b>Upload thành công</b>";*/
		      }
		    }
		  }
		else
		  {
		  echo "Không tồn tại file hoặc file không đúng định dạng ";
		  }
  }


function report_insert($component,$url)
{
	if($component!="")
	{
		echo '<font size=4 color=red><img src="images/success.jpg" width=200 height=100> <br><b>Insert Successful !</b></font><br><a href="$url">Go Back</font>';
	}
	else
	{
		echo '<font size=4 color=red><img src="images/fail.jpg" width=200 height=100> <br>Insert not Successful !</font><br><a href="$url">Go Back</font>';
	}
	

}


function flashViewer($sourceFile,$height,$width)
{
		
echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=".$width." height=".$height.">
  <param name=\"movie\" value=".$sourceFile." />
  <param name=\"quality\" value=\"high\" />
  <embed src=".$sourceFile." quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=".$width."  height=".$height." ></embed>
</object>";
//return $sourceFile;
}
function flv_viewer($sourceFile,$height,$width)
{

	echo '<embed src="flvplayer.swf" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always" flashvars="&file='.$sourceFile.'&autostart=true" />';

}
function movieplayer($linkmoive)
{
echo '<EMBED
                                pluginspage=http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/
                                src="'.$linkmoive.'"
                                type=application/x-mplayer2 autoreplay="true"
                                loop="false" AUTOSTART="true" ShowStatusBar="1"
                                ShowDisplay="0"  width="398" height="380" align="center" alt="click here to full screen" background-attachment: fixed; background-position: center" >';

}

function addSlashed($component)
{
	
	$component=addslashes($component);
	return $component;

}

function strip_slashes($component)
{
	$component=stripslashes($component);
	return $component;
}
function strip_tag($component)
{
	$component=strip_tags($component);
	return $component;
}
function str_replacetitle($component)
{
	
	$component=str_replace("\'","'",$component);
	$component=str_replace('\"','"',$component);
	$component=str_replace('\\','',$component);
	$component=str_replace('<P>',"",$component);
	$component=str_replace('</P>',"",$component);
	$component=trim($component);

	return $component;

}

function str_replace($component)
{
	
	$component=str_replace("\'","'",$component);
	$component=str_replace('\"','"',$component);
	$component=str_replace('\\','\"',$component);
	//$component=strip_tags($component,"\\");
	$component=str_replace('alt=','',$component);
	
	return $component;

}
function showpage($page,$url,$total,$maxpage,$show)    
{  
//global $list_page,$list_page1,$num_page,$list_page2;  
    if ($page>$maxpage) {    
        $num_page=ceil($page/$maxpage);    
        $showpage=($num_page-1)*$maxpage;    
        $end=$showpage+$maxpage;    
        $showpage++;    
    }else    
    {    
        $thispage=1;    
        $showpage=1;    
        $end=$maxpage;    
    }    
    $startpage=$showpage;    
    for ($showpage;$showpage<$end+1;$showpage++)    
    {    
        if ($showpage<=$total) {    
            if ($page==$showpage) {    
                $list_page.="<font color='red'><b>[".$showpage."]</b></font> ";    
            }else {    
                $list_page.="<a href='$url&page=$showpage'>".$showpage."</a> ";    
            }    
        }    
    }    
    if ($num_page>1) {    
        $back=$startpage-1;    
        if ($num_page>2) {    
            $list_page1="<a href='$url&page=1'>First page</a> ";    
        }    
        $list_page1.="<a href='$url&page=$back'>Back</a> ";    
    }    
if ($num_page<ceil($total/$maxpage)&&($total>$maxpage)) {    
        $next=$showpage;    
        $list_page2.=" <a href='$url&page=$next'>Next</a>";    
        $list_page2.=" <a href='$url&page=$total'>Last page</a>";    
    }    
    $list_page=$list_page1.$list_page.$list_page2;    
    switch ($show) {    
        case "str":    
        return $list_page;    
        break;    
        default:    
        echo $list_page;    
        break;    
    }    
}
function showpages($page,$url,$total,$maxpage,$show)    
{    
global $list_page,$list_page1,$num_page,$list_page2;
    if ($page>$maxpage) {    
        $num_page=ceil($page/$maxpage);    
        $showpage=($num_page-1)*$maxpage;    
        $end=$showpage+$maxpage;    
        $showpage++;    
    }else    
    {    
        $thispage=1;    
        $showpage=1;    
        $end=$maxpage;    
    }    
    $startpage=$showpage;    
    for ($showpage;$showpage<$end+1;$showpage++)    
    {    
        if ($showpage<=$total) {    
            if ($page==$showpage) {    
                $list_page.="<font color='red'><b>[".$showpage."]</b></font> ";    
            }else {    
                $list_page.="<a href='$url?page=$showpage'>".$showpage."</a> ";    
            }    
        }    
    }    
    if ($num_page>1) {    
        $back=$startpage-1;    
        if ($num_page>2) {    
            $list_page1="<a href='$url?page=1'>First page</a> ";    
        }    
        $list_page1.="<a href='$url?page=$back'>Back</a> ";    
    }    
if ($num_page<ceil($total/$maxpage)&&($total>$maxpage)) {    
        $next=$showpage;    
        $list_page2.=" <a href='$url?page=$next'>Next</a>";    
        $list_page2.=" <a href='$url?page=$total'>Last page</a>";    
    }    
    $list_page=$list_page1.$list_page.$list_page2;    
    switch ($show) {    
        case "str":    
        return $list_page;    
        break;    
        default:    
        echo $list_page;    
        break;    
    }    
} 
function report($url,$text)
{

echo "<table border=\"0\" width=\"100%\" id=\"table1\" height=\"54\">\n"; 
echo "	<tr>\n"; 
echo "		<td width=\"57\">\n"; 
echo "		<img border=\"0\" src=\"Images/langmanager.png\" width=\"55\" height=\"64\"></td>\n"; 
echo "		<td valign=top><font color=\"#800000\"><b>".$text."</b>"; 
echo "		<a href=index.php?mode=$url>Quay l&#7841;i</a></b></td>\n"; 
echo "	</tr>\n"; 
echo "</table>\n"; 
echo "\n";
}
function get_request($var){
	global $_REQUEST,$ref;
	if(!isset($_REQUEST[$var])) $ref="";
	else $ref=$_REQUEST[$var];
	return trim($ref);	
}
function convert_date_insert($array_date){
	$odate = explode("-",$array_date);
	$ndate = $odate[2]."-".$odate[1]."-".$odate[0];
	return $ndate;
	} 


}

?>