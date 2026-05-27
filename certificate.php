<?php
ob_start();

require(__DIR__ . '/fpdf/fpdf.php');
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    die("Login required");
}

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();

// OPTIONAL COURSE NAME
$course = "Web Development"; // you can make dynamic later

$pdf = new FPDF('L','mm','A4'); // Landscape
$pdf->AddPage();

// 🎨 BORDER
$pdf->SetDrawColor(0, 102, 204);
$pdf->SetLineWidth(3);
$pdf->Rect(10,10,277,190);

// INNER BORDER
$pdf->SetLineWidth(0.5);
$pdf->Rect(15,15,267,180);

// 🎓 TITLE
$pdf->SetFont('Arial','B',30);
$pdf->SetTextColor(0, 51, 102);
$pdf->Cell(0,30,'Certificate of Completion',0,1,'C');

// LINE
$pdf->SetDrawColor(0,0,0);
$pdf->Line(60,45,240,45);

$pdf->Ln(10);

// TEXT
$pdf->SetFont('Arial','',18);
$pdf->Cell(0,10,'This is to certify that',0,1,'C');

$pdf->Ln(5);

// 👤 NAME
$pdf->SetFont('Arial','B',28);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0,15,$u['name'],0,1,'C');

$pdf->Ln(5);

// TEXT
$pdf->SetFont('Arial','',18);
$pdf->Cell(0,10,'has successfully completed the course',0,1,'C');

$pdf->Ln(5);

// 📚 COURSE NAME
$pdf->SetFont('Arial','B',22);
$pdf->Cell(0,10,$course,0,1,'C');

$pdf->Ln(15);

// 📅 DATE
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,10,'Date: '.date("d M Y"),0,1,'C');

// ✍️ SIGNATURE
// ✍️ SIGNATURE (FIXED POSITION)
$pdf->SetFont('Arial','',16);

// Position inside border (X, Y)
$pdf->SetXY(190, 150); // adjust values if needed
$pdf->Cell(80,10,'RAJAN KUMAR',0,1,'C');

$pdf->SetXY(190, 158);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,10,'Authorized Signature',0,1,'C');
// OUTPUT
$pdf->Output('D', 'Premium_Certificate.pdf');

ob_end_flush();
?>