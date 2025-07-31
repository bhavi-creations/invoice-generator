<?php
require_once('bhavidb.php');

// Function to convert numbers to words (Indian Rupees format) - REVISED AND COPIED TO THIS FILE
function numberToWordsINR($num) {
    $num = (float)str_replace(',', '', $num); // Ensure float and remove commas if any

    if ($num == 0) {
        return "Zero Rupees Only.";
    }

    $ones = array(
        0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five",
        6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten",
        11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen",
        15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen",
        19 => "Nineteen"
    );
    $tens = array(
        2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty",
        6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
    );

    $num_arr = explode('.', number_format($num, 2, '.', '')); // Ensure 2 decimal places for accurate paisa
    $integer_part = (int)$num_arr[0];
    $decimal_part = isset($num_arr[1]) ? (int)$num_arr[1] : 0;

    $in_words = "";

    // Process integer part
    if ($integer_part > 0) {
        $crore = floor($integer_part / 10000000);
        $integer_part %= 10000000;
        if ($crore > 0) {
            $in_words .= numberToWordsINRRecursive($crore, $ones, $tens) . " Crore ";
        }

        $lakh = floor($integer_part / 100000);
        $integer_part %= 100000;
        if ($lakh > 0) {
            $in_words .= numberToWordsINRRecursive($lakh, $ones, $tens) . " Lakh ";
        }

        $thousand = floor($integer_part / 1000);
        $integer_part %= 1000;
        if ($thousand > 0) {
            $in_words .= numberToWordsINRRecursive($thousand, $ones, $tens) . " Thousand ";
        }

        $hundred = floor($integer_part / 100);
        $integer_part %= 100;
        if ($hundred > 0) {
            $in_words .= $ones[$hundred] . " Hundred ";
        }

        if ($integer_part > 0) {
            if ($hundred > 0 || $thousand > 0 || $lakh > 0 || $crore > 0) {
                $in_words .= "And "; // As per Indian convention
            }
            $in_words .= numberToWordsINRRecursive($integer_part, $ones, $tens);
        }
    }

    $in_words = trim($in_words);
    $final_string = ($in_words == "" ? "Zero" : $in_words) . " Rupees";

    if ($decimal_part > 0) {
        $final_string .= " And " . numberToWordsINRRecursive($decimal_part, $ones, $tens) . " Paisa";
    }

    $final_string .= " Only.";
    return $final_string;
}

// Helper function for recursive calls (COPIED TO THIS FILE)
function numberToWordsINRRecursive($num, $ones, $tens) {
    if ($num < 20) {
        return $ones[$num];
    } else {
        $word = $tens[floor($num / 10)];
        if (($num % 10) > 0) {
            $word .= " " . $ones[$num % 10];
        }
        return $word;
    }
}


// --- 1. SECURELY GET DATA FROM DATABASE ---
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Invoice ID.");
}
$invoice_id = (int)$_GET['Sid'];

// Fetch main invoice details
$stmt_invoice = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();
if ($result_invoice->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $result_invoice->fetch_assoc();
$stmt_invoice->close();

// Fetch associated services from `service` table
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
$stmt_services->bind_param("i", $invoice_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// Fetch associated files from `invoice_files` table
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
$stmt_files->bind_param("i", $invoice_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();

// Calculate Grand Total in words for invoice
$grand_total_in_words_invoice = numberToWordsINR($invoice['Grandtotal']);

// Calculate Balance in words for invoice (NEW LINE)
$balance_in_words_invoice = numberToWordsINR($invoice['balance']);


// --- 2. BUILD THE HTML FOR THE PDF ---

// Set default image paths
$default_logo_path = 'img/Bhavi-Logo-2.png';
$stamp_display_path = ''; // Will be set if a stamp is selected
$signature_display_path = $default_logo_path; // Default signature is the Bhavi logo

// Check if stamp was selected and build its path (using invoice fields now)
if (!empty($invoice['stamp_image'])) {
    $stamp_display_path = 'uploads/' . htmlspecialchars($invoice['stamp_image']);
}

// Check if signature was selected and build its path (using invoice fields now)
if (!empty($invoice['signature_image'])) {
    $signature_display_path = 'uploads/' . htmlspecialchars($invoice['signature_image']);
}


// Start building the HTML string
$html = '
<html>
<head>
<style>
    body { font-family: sans-serif; font-size: 10pt; }
    p { margin: 0pt; }
    h5 { font-size: 12pt; margin: 5pt 0; }
    h6 { font-size: 10pt; font-weight: normal; margin: 2pt 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 5px; text-align: left; }
    .header-table td { font-size: 16pt; font-weight: bold; }
    
    /* Adjusted styles for details table */
    .details-table { 
        width: 100%; 
        margin-top: 20px; 
        border-collapse: collapse; /* Ensure no gaps */
    }
    .details-table td { 
        vertical-align: top; 
        padding: 0px 15px; /* Reduced padding on left/right edges to allow content closer to edge */
        width: 50%; /* Explicitly set width for columns */
    }
    .details-table td.from-column {
        text-align: left; /* Ensure left alignment */
    }
    .details-table td.to-column {
        text-align: right; /* Ensure right alignment */
    }
    .details-table h4 {
        font-size: 14pt; /* Adjusted from 16pt of header table to h4 size */
        margin-bottom: 10px; /* Equivalent to pb-2 */
        font-weight: bold;
    }
    .details-table address {
        font-style: normal; /* Override default address italic */
        margin-top: 5px; /* Small top margin for readability */
    }


    /* Styles for the new services table - adapted from print.css and print_content.php */
    .services-table-new { 
        width: 100%; 
        border-collapse: collapse; 
        border: 1px solid #dee2e6; /* From .table in print.css */
    }
    .services-table-new th,
    .services-table-new td { 
        padding: 8px; /* Standard table padding */
        text-align: center; 
        vertical-align: middle; 
        border: 1px solid #dee2e6; /* From .table in print.css for borders on cells */
    }
    .services-table-new thead th {
        background-color: #e9ecef; /* From thead style in print_content.php */
        font-weight: bold; 
    }
    .services-table-new tfoot td {
        padding: 8px;
        border: 1px solid #dee2e6; /* Ensure footer cells have borders too */
    }
    .services-table-new tfoot td.no-border {
        border: none; /* For the colspan cell containing words */
    }
    .services-table-new .bg-light-custom { /* Custom class for light background rows/cells */
        background-color: #f8f9fa; /* A slightly lighter shade than #e9ecef for subtle distinction */
    }

    .totals-table { margin-top: 20px; } /* Kept for other totals, but service table now contains its own totals */
    .totals-table td { padding: 5px 10px; }
    .notes-section { margin-top: 20px; }
    .payment-section { margin-top: 30px; page-break-inside: avoid; }
    .signature-section { text-align: right; margin-top: 30px; page-break-inside: avoid; }
    .signature-section img { display: block; margin-left: auto; margin-right: 0; } /* Align images to right */
</style>
</head>
<body>';

// Logo
$html .= '<div style="text-align:center;"><img src="' . $default_logo_path . '" width="200px" /></div>';
$html .= '<h1 style="text-align:center; margin-top:15px; margin-bottom: 30px;">Invoice</h1>';

// Header Table
$html .= '
<table class="header-table">
    <tr>
        <td style="width:50%;"><strong>Date:</strong> ' . date("d-m-Y", strtotime($invoice['Invoice_date'])) . '</td>
        <td style="width:50%; text-align:right;"><strong>Invoice #:</strong> BHAVI_INV_' . htmlspecialchars($invoice['Invoice_no']) . '</td>
    </tr>
</table>
<hr>';

// Company and Customer Details Table - Adjusted for alignment
$html .= '
<table class="details-table">
    <tr>
        <td class="from-column">
            <h4><strong>From:</strong></h4>
            <address>
                <h5>Bhavi Creations Pvt Ltd</h5>
                <h6>Plot no28, H No70, 17-28, RTO Office Rd,</h6>
                <h6>RangaRaoNagar, Kakinada, AndhraPradesh 533003.</h6>
                <h6><strong>Phone:</strong> 9642343434</h6>
                <h6><strong>Email:</strong> admin@bhavicreations.com</h6>
                <h6><strong>GSTIN:</strong> 37AAKCB6960H1ZB</h6>
            </address>
        </td>
        <td class="to-column">
            <h4><strong>To (Bill To):</strong></h4>
            <address>
                <h5>' . htmlspecialchars($invoice['Company_name']) . '</h5>
                <h6>' . htmlspecialchars($invoice['Cname']) . '</h6>
                <h6>' . nl2br(htmlspecialchars($invoice['Caddress'])) . '</h6>
                <h6><strong>Phone:</strong> ' . htmlspecialchars($invoice['Cphone']) . '</h6>
                <h6><strong>Email:</strong> ' . htmlspecialchars($invoice['Cmail']) . '</h6>
                <h6><strong>GSTIN:</strong> ' . htmlspecialchars($invoice['Cgst']) . '</h6>
            </address>
        </td>
    </tr>
</table>';

// *** NEW SERVICES TABLE WITH DETAILED FORMATTING ***
$html .= '
<h3 style="margin-top:30px;">Services</h3>
<table class="services-table-new">
    <thead>
        <tr>
            <th>S.no</th>
            <th>Services</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price/Unit</th>
            <th>Sub Total</th>
            <th>Disc %</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';
$counter = 1;
foreach ($services as $service) {
    $html .= '
        <tr>
            <td>' . sprintf('%02d', $counter++) . '</td>
            <td>' . htmlspecialchars($service['Sname']) . '</td>
            <td>' . htmlspecialchars($service['Description']) . '</td>
            <td>' . htmlspecialchars($service['Qty']) . '</td>
            <td>' . number_format((float)$service['Price'], 2) . '</td>
            <td>' . number_format((float)$service['Totalprice'], 2) . '</td>
            <td>' . htmlspecialchars($service['Discount']) . '</td>
            <td>' . number_format((float)$service['Finaltotal'], 2) . '</td>
        </tr>';
}
$html .= '
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" rowspan="5" style="text-align: left; vertical-align: bottom; border:none;" class="no-border">
                <p style="margin-bottom: 5px;"><strong>Total in words:</strong><br>' . htmlspecialchars($grand_total_in_words_invoice) . '</p>
                <p><strong>Balance in words:</strong><br>' . htmlspecialchars($balance_in_words_invoice) . '</p>
            </td>
            <td style="text-align: right;"><strong>Subtotal</strong></td>
            <td>' . number_format((float)$invoice['Final'], 2) . '</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>GST %</strong></td>
            <td>' . htmlspecialchars($invoice['Gst']) . '</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>GST Total</strong></td>
            <td>' . number_format((float)$invoice['Gst_total'], 2) . '</td>
        </tr>
        <tr>
            <td style="text-align: right;" class="bg-light-custom"><strong>Grand Total</strong></td>
            <td class="bg-light-custom"><strong>' . number_format((float)$invoice['Grandtotal'], 2) . '</strong></td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>Advance</strong></td>
            <td>' . number_format((float)$invoice['advance'], 2) . '</td>
        </tr>
        <tr>
            <td colspan="6" style="border:none;"></td>
            <td style="text-align: right;" class="bg-light-custom"><strong>Balance</strong></td>
            <td class="bg-light-custom"><strong>' . number_format((float)$invoice['balance'], 2) . '</strong></td>
        </tr>
    </tfoot>
</table>';
// *** END NEW SERVICES TABLE ***


// Notes and Attachments
$html .= '<div class="notes-section">';
if (!empty($invoice['Note'])) {
    $html .= '<h5>Note:</h5><p>' . nl2br(htmlspecialchars($invoice['Note'])) . '</p>';
}
// if (!empty($files)) {
//     $html .= '<h5 style="margin-top:15px;">Attachments:</h5>';
//     foreach ($files as $file) {
//         $display_file_name = htmlspecialchars(basename($file['file_path']));
//         $html .= '<p>' . $display_file_name . '</p>';
//     }
// }
$html .= '</div>';


// Conditional Payment Details
$html .= '<div class="payment-section">';
if ($invoice['payment_details_type'] == 'office') {
    $html .= '
    <table>
        <tr>
            <td style="width:40%;"><img src="img/qrcode.jpg" width="120px"></td>
            <td style="width:60%;">
                <h5>Payment Details</h5>
                <strong>Bank:</strong> HDFC Bank, Kakinada<br>
                <strong>Acc Name:</strong> Bhavi Creations Private Limited<br>
                <strong>Acc No.:</strong> 59213749999999<br>
                <strong>IFSC:</strong> HDFC0000426
            </td>
        </tr>
    </table>';
} elseif ($invoice['payment_details_type'] == 'personal') {
    $html .= '
    <table>
        <tr>
            <td style="width:40%;"><img src="img/personal_qrcode.jpg" width="120px"></td>
            <td style="width:60%;">
                <h5>Payment Details</h5>
                <strong>Bank:</strong> State Bank Of India<br>
                <strong>Acc Name:</strong> Chalikonda Naga Phaneendra Naidu<br>
                <strong>Acc No.:</strong> 20256178992<br>
                <strong>IFSC:</strong> SBIN00001917<br>
                <strong>Google pay , Phone pay, Paytm :</strong> 8686394079
            </td>
        </tr>
    </table>';
}
$html .= '</div>';

// Stamp and Signature Section
$html .= '
<div class="signature-section ">
    <div style="display: inline-block; text-align: center; margin-right: -300px;">';

if (!empty($stamp_display_path)) {
    $html .= '<img src="' . $stamp_display_path . '" style="max-height:150px; max-width: 150px; margin-bottom: 5px;" />';
}

$html .= '<img src="' . $signature_display_path . '" style="max-height:100px; max-width: 100px;" />';
$html .= '<p style="margin-top:5px; font-weight: bold;">Signature</p>';
$html .= '</div>
</div>';

// End of HTML
$html .= '</body></html>';

// --- 3. RENDER THE PDF using mPDF ---
$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 20,
    'margin_bottom' => 20,
    'margin_header' => 10,
    'margin_footer' => 10
]);

$mpdf->SetTitle("Bhavi Creations - Invoice");
$mpdf->SetAuthor("Bhavi Creations");
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('Invoice-' . $invoice['Invoice_no'] . '.pdf', 'D');

?>