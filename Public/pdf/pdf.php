<?php
header("Content-type: text/html; charset=gb2312");
require('html2fpdf.php');
$pdf=new HTML2FPDF();

$pdf->AddGBFont();
$pdf->AddPage();
$fp = fopen("index.html","r");
$strContent = fread($fp, filesize("index.html"));
fclose($fp);
$pdf->SetFont('GB','',20);
$pdf->WriteHTML($strContent);
$pdf->Output("index2.pdf");
//$aa="http://localhost/pdf/index.pdf";
header("Location: http://localhost/pdf/index.pdf");
?>