<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('tcpdf_min/tcpdf.php');

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica','B',20);
$pdf->Cell(0,10,'تجربة TCPDF ناجحة',0,1,'C');
$pdf->Output('test.pdf','I');
