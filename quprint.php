<?php
require_once('bhavidb.php');

// --- 1. SECURELY GET DATA FROM DATABASE ---
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Quotation ID.");
}
$quote_id = (int)$_GET['Sid'];

// Fetch main quotation details, including the new stamp and signature columns
$stmt_quote = $conn->prepare("SELECT * FROM quotation WHERE Sid = ?");
$stmt_quote->bind_param("i", $quote_id);
$stmt_quote->execute();
$result_quote = $stmt_quote->get_result();
if ($result_quote->num_rows === 0) {
    die("Quotation not found.");
}
$quote = $result_quote->fetch_assoc();
$stmt_quote->close();

// Fetch associated services from `quservice` table
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM quservice WHERE Sid = ?");
$stmt_services->bind_param("i", $quote_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// Fetch associated files
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM quote_files WHERE quote_id = ?");
$stmt_files->bind_param("i", $quote_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();

// --- 2. BUILD THE HTML FOR THE PDF ---

// Set default image paths
$default_logo_path = 'img/Bhavi-Logo-2.png';
$stamp_display_path = ''; // Will be set if a stamp is selected
$signature_display_path = $default_logo_path; // Default signature is the Bhavi logo

// Check if stamp was selected and build its path
if (!empty($quote['selected_stamp_filename'])) {
    $stamp_display_path = 'uploads/' . htmlspecialchars($quote['selected_stamp_filename']);
}

// Check if signature was selected and build its path
if (!empty($quote['selected_signature_filename'])) {
    $signature_display_path = 'uploads/' . htmlspecialchars($quote['selected_signature_filename']);
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
    .details-table td { vertical-align: top; padding: 15px; }
    .services-table { border: 1px solid #333; }
    .services-table th { background-color: #EEEEEE; font-weight: bold; border-bottom: 1px solid #333; text-align: center; }
    .services-table td { border: 1px solid #ddd; text-align: center; }
    .totals-table { margin-top: 20px; }
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
$html .= '<h1 style="text-align:center; margin-top:15px; margin-bottom: 30px;">Quotation</h1>';

// Header Table
$html .= '
<table class="header-table">
    <tr>
        <td style="width:50%;"><strong>Date:</strong> ' . date("d-m-Y", strtotime($quote['quotation_date'])) . '</td>
        <td style="width:50%; text-align:right;"><strong>Quotation #:</strong> BHAVI_QUOTE_' . htmlspecialchars($quote['quotation_no']) . '</td>
    </tr>
</table>
<hr>';

// Company and Customer Details Table
$html .= '
<table class="details-table" style="margin-top: 20px;">
    <tr>
        <td style="width:50%;">
            <h5>From:</h5>
            <strong>Bhavi Creations Pvt Ltd</strong><br>
            Plot no28, H No70, 17-28, RTO Office Rd,<br>
            RangaRaoNagar, Kakinada, AndhraPradesh 533003.<br>
            <strong>Phone:</strong> 9642343434<br>
            <strong>Email:</strong> admin@bhavicreations.com<br>
            <strong>GSTIN:</strong> 37AAKCB6960H1ZB
        </td>
        <td style="width:50%;">
            <h5>To:</h5>
            <strong>' . htmlspecialchars($quote['Company_name']) . '</strong><br>
            ' . htmlspecialchars($quote['Cname']) . '<br>
            ' . nl2br(htmlspecialchars($quote['Caddress'])) . '<br>
            <strong>Phone:</strong> ' . htmlspecialchars($quote['Cphone']) . '<br>
            <strong>Email:</strong> ' . htmlspecialchars($quote['Cmail']) . '<br>
            <strong>GSTIN:</strong> ' . htmlspecialchars($quote['Cgst']) . '
        </td>
    </tr>
</table>';

// Services Table
$html .= '
<h3 style="margin-top:30px;">Services</h3>
<table class="services-table">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Service</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';
$counter = 1;
foreach ($services as $service) {
    $html .= '
        <tr>
            <td>' . $counter++ . '</td>
            <td>' . htmlspecialchars($service['Sname']) . '</td>
            <td style="text-align:left;">' . htmlspecialchars($service['Description']) . '</td>
            <td>' . htmlspecialchars($service['Qty']) . '</td>
            <td>' . number_format((float)$service['Price'], 2) . '</td>
            <td>' . number_format((float)$service['Finaltotal'], 2) . '</td>
        </tr>';
}
$html .= '
    </tbody>
</table>';

// Totals section
$html .= '
<table class="totals-table" align="right" style="width: 40%;">
    <tr><td>Subtotal:</td><td style="text-align:right;">' . number_format((float)$quote['Final'], 2) . '</td></tr>
    <tr><td>GST (' . htmlspecialchars($quote['Gst']) . '%):</td><td style="text-align:right;">' . number_format((float)$quote['Gst_total'], 2) . '</td></tr>
    <tr><td><strong>Grand Total:</strong></td><td style="text-align:right;"><strong>' . number_format((float)$quote['Grandtotal'], 2) . '</strong></td></tr>
</table>
<div style="clear:both;"></div>';

// Notes and Attachments
$html .= '<div class="notes-section">';
if (!empty($quote['Note'])) {
    $html .= '<h5>Note:</h5><p>' . nl2br(htmlspecialchars($quote['Note'])) . '</p>';
}
if (!empty($files)) {
    $html .= '<h5 style="margin-top:15px;">Attachments:</h5>';
    foreach ($files as $file) {
        // This part needs adjustment if file_path still includes the prefix like 'quote_id-uniqid-'
        // Assuming file_name_only from quotationform.php is just the base file name.
        // If file_path in DB is '123-abc-filename.pdf', this will show 'filename.pdf'
        $display_file_name = htmlspecialchars(basename($file['file_path'])); // Use basename to ensure only filename
        // If you specifically want to remove the quote_id-uniqid- prefix:
        // $display_file_name = htmlspecialchars(substr($file['file_path'], strpos($file['file_path'], '-', strpos($file['file_path'], '-') + 1) + 1));
        $html .= '<p>' . $display_file_name . '</p>';
    }
}
$html .= '</div>';


// Conditional Payment Details
$html .= '<div class="payment-section">';
if ($quote['payment_details_type'] == 'office') {
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
} elseif ($quote['payment_details_type'] == 'personal') {
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
    <div style="display: inline-block; text-align: center; margin-right: -300px;">'; // Container to center images and text, floated right

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

$mpdf->SetTitle("Bhavi Creations - Quotation");
$mpdf->SetAuthor("Bhavi Creations");
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('Quotation-' . $quote['quotation_no'] . '.pdf', 'I');

?>