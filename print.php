<?php
require('fpdf/fpdf.php');
require('bhavidb.php');
class PDF extends FPDF
{
    function Header()
    {
        $this->Image("img/logo.png", 82, 6, 42, 23, "png");


        $this->Ln(20);
    }



    function Footer()
    {
        // Position at 1.5 cm from the bottom
        $this->SetY(-15);

        // Select Arial italic 8
        $this->SetFont('Arial', 'B', 10);

        // Page number
        $this->Cell(0, 10, 'Google pay , Phone pay, Paytm 9642343434', 0, 0, 'C');
    }
}

$pdf = new PDF("P", 'mm', 'A4');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();


$server = 'localhost';
$username = 'root';
$pass = '';
$database = 'bhavi_invoice_db';

$conn = mysqli_connect($server, $username, $pass, $database);
if (!$conn) {
    echo "connection failed";
}


$sql = "SELECT *FROM invoice
JOIN service ON invoice.Sid = service.Sid
WHERE invoice.Sid = 1   ;";
$result = mysqli_query($conn, $sql);
while ($data = mysqli_fetch_assoc($result)) {


    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(80, 5, 'INVOICE', 5, 0, 'L');
    $pdf->Cell(95, 5, 'INVOICE NUMBER', 0, 1, 'R');
    $pdf->Cell(0, 10, 'Date:  ' . $data['Invoice_date'], 0, 0, 'L');
    $pdf->Cell(0, 10, 'BHAVI_KKD_2023_' . $data['Invoice_no'], 0, 1, 'R');


    $pdf->Cell(80, 15, 'Bhavi Creations Pvt. Ltd', 0, 0, 'L');
    $pdf->Cell(89, 15, $data['Company_name'], 0, 1, 'R');

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(126, 5, 'Plot no 28, H No 70, 17-28, RTO Office Rd.,', 0, 0, 'L');
    $pdf->Cell(0, 5, $data['Cname'], 0, 1, 'L');
    $pdf->Cell(126, 5, 'Bhavi Creations Pvt. Ltd', 0, 0, 'L');
    $pdf->Cell(0, 5, $data['Caddress'], 0, 1, 'L');
    $pdf->Cell(0, 5, 'opposite to New RTO Office, behind J.N.T.U ', 0, 1, 'L');
    $pdf->Cell(0, 5, 'Engineering College Play Ground,', 0, 1, 'L');
    $pdf->Cell(0, 5, 'RangaRaoNagar, Kakinada, Andhra Pradesh 33003.', 0, 1, 'L');
    $pdf->Cell(126, 5, 'Phone no: 9642343434', 0, 0, 'L');
    $pdf->Cell(0, 5, $data['Cphone'], 0, 1, 'L');
    $pdf->Cell(126, 5, 'Email: admin@bhavicreations.com', 0, 0, 'L');
    $pdf->Cell(0, 5, $data['Cmail'], 0, 1, 'L');
    $pdf->Cell(126, 5, 'GSTIN 37AAKCB6060HIZB', 0, 0, 'L');
    $pdf->Cell(0, 5, $data['Cgst'], 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 15, 'BILLING', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 8, 'SERVICES', 1, 0, 'C');
    $pdf->Cell(50, 8, 'DESCRIPTION', 1, 0, 'C');
    $pdf->Cell(15, 8, 'Qty', 1, 0, 'C');
    $pdf->Cell(25, 8, 'PRICE / UNIT', 1, 0, 'C');
    $pdf->Cell(20, 8, 'TOTAL PRICE', 1, 0, 'C');
    $pdf->Cell(20, 8, 'DISCOUNT', 1, 0, 'C');
    $pdf->Cell(25, 8, 'FINAL TOTAL', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 8);

    $pdf->Cell(30, 8, $data['Sname'], 1, 0, 'C');
    $pdf->Cell(50, 8, $data['Description'], 1, 0, 'C');
    $pdf->Cell(15, 8, $data['Qty'], 1, 0, 'C');
    $pdf->Cell(25, 8, $data['Price'], 1, 0, 'C');
    $pdf->Cell(20, 8, $data['Totalprice'], 1, 0, 'C');
    $pdf->Cell(20, 8, $data['Discount'], 1, 0, 'C');
    $pdf->Cell(25, 8, $data['Finaltotal'], 1, 1, 'C');
}

$pdf->Output();
