<?php

/**
* Simple excel generating from PHP5
*
* @package Utilities
* @license http://www.opensource.org/licenses/mit-license.php
* @author Oliver Schwarz <oliver.schwarz@gmail.com>
* @version 1.0
*/

/**
* Generating excel documents on-the-fly from PHP5
* 
* Uses the excel XML-specification to generate a native
* XML document, readable/processable by excel.
* 
* @package Utilities
* @subpackage Excel
* @author Oliver Schwarz <oliver.schwarz@vaicon.de>
* @version 1.1
* 
* @todo Issue #4: Internet Explorer 7 does not work well with the given header
* @todo Add option to give out first line as header (bold text)
* @todo Add option to give out last line as footer (bold text)
* @todo Add option to write to file
*/
class Excel_XML
{

/**
* Header (of document)
* @var string
*/
private $header = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

/**
* Footer (of document)
* @var string
*/
private $footer = "</Workbook>";

/**
* Lines to output in the excel document
* @var array
*/
private $lines = array();

/**
* Used encoding
* @var string
*/
private $sEncoding;

/**
* Convert variable types
* @var boolean
*/
private $bConvertTypes;

/**
* Worksheet title
* @var string
*/
private $sWorksheetTitle;

/**
* Constructor
* 
* The constructor allows the setting of some additional
* parameters so that the library may be configured to
* one's needs.
* 
* On converting types:
* When set to true, the library tries to identify the type of
* the variable value and set the field specification for Excel
* accordingly. Be careful with article numbers or postcodes
* starting with a '0' (zero)!
* 
* @param string $sEncoding Encoding to be used (defaults to UTF-8)
* @param boolean $bConvertTypes Convert variables to field specification
* @param string $sWorksheetTitle Title for the worksheet
*/
public function __construct($sEncoding = 'UTF-8', $bConvertTypes = false, $sWorksheetTitle = 'Table1')
{
$this->bConvertTypes = $bConvertTypes;
$this->setEncoding($sEncoding);
$this->setWorksheetTitle($sWorksheetTitle);
}

/**
* Set encoding
* @param string Encoding type to set
*/
public function setEncoding($sEncoding)
{
$this->sEncoding = $sEncoding;
}

/**
* Set worksheet title
* 
* Strips out not allowed characters and trims the
* title to a maximum length of 31.
* 
* @param string $title Title for worksheet
*/
public function setWorksheetTitle ($title)
{
$title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
$title = substr ($title, 0, 31);
$this->sWorksheetTitle = $title;
}

/**
* Add row
* 
* Adds a single row to the document. If set to true, self::bConvertTypes
* checks the type of variable and returns the specific field settings
* for the cell.
* 
* @param array $array One-dimensional array with row content
*/
private function addRow ($array,$i)
{
$cells = "<Cell ss:StyleID=\"Table\"><Data ss:Type=\"Number\">" . $i . "</Data></Cell>";
for($i=0;$i<10;$i++)
{
	//$_SESSION['report_values'][$i][0]=$i." ";
	$type = 'String';
	if ($this->bConvertTypes === true && is_numeric($array[$i])):
		$type = 'Number';
	endif;
	
	$v = str_replace('<br>',';',$array[$i]);
	$v = str_replace('&nbsp;','',$v);
	$v = str_replace(';;','',$v);
	$v = str_replace('<','',$v);
	$v = str_replace('>','',$v);
	if ($v[0]==';') {
		$v = htmlentities(substr($v,1), ENT_COMPAT, $this->sEncoding);
	}
		
	
	$cells .= "<Cell ss:StyleID=\"Table\"><Data ss:Type=\"$type\">" . $v . "</Data></Cell>\n"; 
	
}
$this->lines[] = "<Row>\n" . $cells . "</Row>\n";	
/*foreach ($array as $k => $v):
	$type = 'String';
	if ($this->bConvertTypes === true && is_numeric($v)):
		$type = 'Number';
	endif;
	
	$v = htmlentities($v, ENT_COMPAT, $this->sEncoding);
	$cells .= "<Cell><Data ss:Type=\"$type\">" . $v . "</Data></Cell>\n"; 
endforeach;
$this->lines[] = "<Row>\n" . $cells . "</Row>\n";*/
}

/**
* Add an array to the document
* @param array 2-dimensional array
*/
public function addArray ($array)
{
$i = 0;
foreach ($array as $k => $v):
	$i= $i+1;
	$this->addRow ($v,$i);
endforeach;
}


/**
* Generate the excel file
* @param string $filename Name of excel file to generate (...xls)
*/
public function generateXML ($filename = 'excel-export')
{
// correct/validate filename
$filename = preg_replace('/[^aA-zZ0-9\_\-]/', '', $filename);

// deliver header (as recommended in php manual)
header("Content-Type: application/vnd.ms-excel; charset=" . $this->sEncoding);
header("Content-Disposition: inline; filename=\"" . $filename . ".xls\"");

// print out document to the browser
// need to use stripslashes for the **** ">"
echo stripslashes (sprintf($this->header, $this->sEncoding));
echo "<Styles><Style ss:ID=\"s1\">
   <Interior ss:Color=\"#C0C0C0\" ss:Pattern=\"Solid\"/>
    <Borders>
            <Border ss:Position=\"Top\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Bottom\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Left\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Right\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
        </Borders>
        <Font ss:FontName=\"Arial\" ss:Size=\"8\" />
  </Style>
  <Style ss:ID=\"Table\">
        <Borders>
            <Border ss:Position=\"Top\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Bottom\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Left\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
            <Border ss:Position=\"Right\" ss:Color=\"#595959\" ss:Weight=\"1\" ss:LineStyle=\"Continuous\"/>
        </Borders>
        <Font ss:FontName=\"Arial\" ss:Size=\"8\" />
    </Style>
  
  </Styles>";
echo "\n<Worksheet ss:Name=\"" . $this->sWorksheetTitle . "\">\n<Table>\n";
echo "<ss:Column ss:Width=\"20\"/>
		<ss:Column ss:Width=\"100\"/>
            <ss:Column ss:Width=\"100\"/>
            <ss:Column ss:Width=\"100\"/>
			<ss:Column ss:Width=\"40\"/>
			<ss:Column ss:Width=\"100\"/>
			<ss:Column ss:Width=\"120\"/>
			<ss:Column ss:Width=\"70\"/>
			<ss:Column ss:Width=\"150\"/>
			<Row>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>STT</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Ngày đặt hàng", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Ngày Giao hàng", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Tên KH", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Giới tính", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Tên Cty", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Email", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Số ĐT", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Địa chỉ", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Số lần đặt", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
				<Cell ss:StyleID=\"s1\" >
					<Data ss:Type=\"String\"><B>".htmlentities("Doanh số đã đặt(k)", ENT_COMPAT, $this->sEncoding)."</B></Data>
				</Cell>
			</Row>";
foreach ($this->lines as $line)
echo $line;

echo "</Table>\n</Worksheet>\n";
echo $this->footer;
}

}

?>