<?php  
header("Content-type: text/html; charset=gb2312"); 
require('html2fpdf.php');  
$pdf=new HTML2FPDF();  
$pdf->AddPage();  
$fp = fopen("index.html","r");  
$strContent = fread($fp, filesize("index.html"));  
fclose($fp);  
$pdf->WriteHTML($strContent);  
$pdf->Output("index.pdf");  
//$aa="http://localhost/pdf/index.pdf";
header("Location: http://localhost/pdf/index.pdf");
?> 