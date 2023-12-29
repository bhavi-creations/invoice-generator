<?php
require('fpdf/fpdf.php');
require('bhavidb.php');
$Sid = (isset($_GET['Sid']) && $_GET['Sid'] !== '') ? $_GET['Sid'] : 0;
class PDF extends FPDF
{
    private $grandTotal = 0;
    private $gst = 0;
    private $gst_total = 0;
    private $words = 0;
    private $terms = 0;
    private $finalTotal = 0;
    private $totalPages = 0;
    private $note = 0;

    function Header()
    {
        $this->Image("img/logo.png", 82, 6, 42, 23, "png");
        $this->Ln(20);
    }
    function drawTableHeader()
{
    $this->SetFont('Arial', 'B', 8); // Set the font here
    $this->Cell(40, 8, 'SERVICES', 1, 0, 'C');
    $this->Cell(50, 8, 'DESCRIPTION', 1, 0, 'C');
    $this->Cell(10, 8, 'Qty', 1, 0, 'C');
    $this->Cell(20, 8, 'PRICE / UNIT', 1, 0, 'C');
    $this->Cell(20, 8, 'TOTAL', 1, 0, 'C');
    $this->Cell(20, 8, 'DISCOUNT', 1, 0, 'C');
    $this->Cell(20, 8, 'FINAL', 1, 1, 'C');
}

    function body()
    {
        $server = 'localhost';
        $username = 'root';
        $pass = '';
        $database = 'bhavi_invoice_db';

        $conn = mysqli_connect($server, $username, $pass, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $Sid = (isset($_GET['Sid']) && $_GET['Sid'] !== '') ? $_GET['Sid'] : 0;

        $sql = "SELECT * FROM invoice
                JOIN service ON invoice.Sid = service.Sid
                WHERE invoice.Sid = $Sid;";
        $result = mysqli_query($conn, $sql);

        // Check for query execution success
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $firstService = true;

        $this->SetY(40);
        while ($data = mysqli_fetch_assoc($result)) {
            if (!$firstService && $this->GetY() + 40 > $this->GetPageHeight()) {
                $this->AddPage();
                $this->Header();
                $this->drawTableHeader();
                $this->SetY(40);
            }

            $this->SetFont('Arial', 'B', 15);
            $this->Cell(100, 5, 'INVOICE', 0, 0, 'L');
            $this->Cell(0, 5, 'INVOICE   NUMBER', 0, 1, 'L');
            $this->Cell(100, 10, 'Date:  ' . $data['Invoice_date'], 0, 0, 'L');
            $this->Cell(0, 10, 'BHAVI_KKD_2023_' . $data['Invoice_no'], 0, 1, 'L');

            $this->Cell(100, 15, 'Bhavi Creations Pvt. Ltd', 0, 0, 'L');
            $this->Cell(86, 15, $data['Company_name'], 0, 1, 'L');

            $this->SetFont('Arial', '', 10);
            $this->Cell(100, 5, '', 0, 0, 'L');
            $this->Cell(86, 5, $data['Cname'], 0, 1, 'L');
            $text = "Plot no28, H No70, 17-28, RTO Office Rd, opposite to New RTO Office, behind J.N.T.U Engineering College Play Ground, RangaRaoNagar, Kakinada, AndhraPradesh533003";
            $this->SetFont('Arial', '', 10);

            $startX = $this->GetX();
            $startY = $this->GetY();

            $this->MultiCell(80, 5, $text, 0, 'L');

            $this->SetXY($startX + 100, $startY);

            $this->MultiCell(100, 5, $data['Caddress'], 0, 'L');
            $this->SetXY($startX, $startY + 25);
            $this->Cell(100, 5, 'Phone no: 9642343434', 0, 0, 'L');
            $this->Cell(86, 5, $data['Cphone'], 0, 1, 'L');
            $this->Cell(100, 5, 'Email: admin@bhavicreations.com', 0, 0, 'L');
            $this->Cell(86, 5, $data['Cmail'], 0, 1, 'L');
            $this->Cell(100, 5, 'GSTIN 37AAKCB6060HIZB', 0, 0, 'L');
            $this->Cell(86, 5, $data['Cgst'], 0, 1, 'L');

            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 35, 'BILLING', 0, 1, 'C');

            $this->drawTableHeader();
            do {
                $this->Cell(40, 30, $data['Sname'], 'LR', 0, 'C');
                $x = $this->GetX();
                $y = $this->GetY();
                $this->MultiCell(50, 8, $data['Description'], 'LR');
                $this->SetXY($x + 50, $y);
                $this->Cell(10, 30, $data['Qty'], 'LR', 0, 'C');
                $this->Cell(20, 30, $data['Price'], 'LR', 0, 'C');
                $this->Cell(20, 30, $data['Totalprice'], 'LR', 0, 'C');
                $this->Cell(20, 30, $data['Discount'], 'LR', 0, 'C');
                $this->Cell(20, 30, $data['Finaltotal'], 'LR', 1, 'C');

                $this->grandTotal = $data['Final'];
                $this->gst = $data['Gst'];
                $this->gst_total = $data['Gst_total'];
                $this->words = $data['Totalinwords'];
                $this->finalTotal = $data['Grandtotal'];
                $this->terms = $data['Terms'];
                $this->note = $data['Note'];

                $firstService = false;
            } while ($data = mysqli_fetch_assoc($result));
        }
        $this->SetFont('Arial','',10);
        $this->Cell(160, 8, 'Grand Total', 1, 0, 'R');
        $this->Cell(20, 8, $this->grandTotal, 1, 1, 'C');
        $this->Cell(140, 8, 'GST%', 1, 0, 'R');
        $this->Cell(20, 8, $this->gst, 1, 0, 'C');
        $this->Cell(20, 8, $this->gst_total, 1, 1, 'C');
        $this->Cell(140, 8, $this->words, 1, 0, 'L');
        $this->Cell(20, 8, 'Grand Total', 1, 0, 'C');
        $this->Cell(20, 8, $this->finalTotal, 1, 1, 'C');
        $this->Ln(30);

        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 8, 'Terms & Conditons', 0, 1, 'L');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 8, $this->terms, 0, 'L');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 8, 'Note', 0, 1, 'L');
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(0, 8, $this->note, 0, 'L');
        $this->SetY(-55);


        $this->Image("img/Vector.png", 25, 248, 22, 20, "png");
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(11, 5, '');
        $this->Cell(100, 5, 'Scan to pay', 0, 0, 'L');

        // $this->Cell(110);
        $this->Cell(89, 5, 'Payment Details', 0, 1, 'L');

        $this->SetFont('Arial', '', 10);

        $this->Cell(110);
        $this->Cell(89, 5, 'Bank Name : HDFC Bank, Kakinada', 0, 1, 'L');
        $this->Cell(110);
        $this->Cell(89, 5, 'Account Name : Bhavi Creations Private Limited', 0, 1, 'L');
        $this->Cell(110);
        $this->Cell(89, 5, 'Account No. : 59213749999999', 0, 1, 'L  ');
        $this->Cell(110);
        $this->Cell(89, 5, 'IFSC : HDFC000042', 0, 1, 'L');





        $this->SetFont('Arial', 'B', 10);

        $this->Cell(0, 9, 'Google pay, Phone pay, Paytm 9642343434', 0, 0, 'C');
        $this->totalPages = $this->PageNo();
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');

        if ($this->PageNo() == $this->totalPages) {
        }
    }
}

$pdf = new PDF("P", 'mm', 'A4');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();
$pdf->body();
$pdf->Output();
