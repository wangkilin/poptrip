<?php
require('chinese.php');

$pdf=new PDF_Chinese();
$pdf->AddGBFont();
$pdf->AddPage();
$pdf->SetFont('GB','',20);
$pdf->Write(10,'真是一头猪');
$pdf->Output('ex2.pdf');
?>
