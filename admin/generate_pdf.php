<?php
require("../admin/Konfig.php");
require("../lib/fpdf"); // Pastikan FPDF sudah diinstal

$sql = "SELECT p.id, u.nama, u.alamat, u.no_tlp, p.keluhan, p.status 
        FROM pengajuan p 
        JOIN users u ON p.user_id = u.id";
$result = $conn->query($sql);

// Mulai PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Header
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nama', 1);
$pdf->Cell(50, 10, 'Alamat', 1);
$pdf->Cell(40, 10, 'No. Telpon', 1);
$pdf->Cell(60, 10, 'Keluhan', 1);
$pdf->Cell(20, 10, 'Status', 1);
$pdf->Ln();

// Data
while ($row = $result->fetch_assoc()) {
    $statusText = $row['status'] == 1 ? 'Selesai' : 'Belum';
    $pdf->Cell(10, 10, $row['id'], 1);
    $pdf->Cell(40, 10, $row['nama'], 1);
    $pdf->Cell(50, 10, $row['alamat'], 1);
    $pdf->Cell(40, 10, $row['no_tlp'], 1);
    $pdf->Cell(60, 10, $row['keluhan'], 1);
    $pdf->Cell(20, 10, $statusText, 1);
    $pdf->Ln();
}

$pdf->Output('D', 'daftar_pengajuan.pdf');
exit();
?>
