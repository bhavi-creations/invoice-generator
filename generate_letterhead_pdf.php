<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['html_content'])) {
    $htmlContent = $_POST['html_content'];

    // --- FIX 1: Hiding the "Add Feature" button by removing it from the HTML ---
    // This regular expression finds and removes the button HTML.
    $htmlContent = preg_replace('/<button[^>]*class="[^"]*add-feature-btn[^"]*"[^>]*>.*?<\/button>/s', '', $htmlContent);

    // Define the paths to your images, making them relative to the script location
    $letterheadPath = __DIR__ . '/img/letterhead.png';
    $stampPath = __DIR__ . '/img/bhavi stamp.jpg';
    $signaturePath = __DIR__ . '/img/signiture.jpeg';
    
    $css = '
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: \'Inter\', sans-serif;
            -webkit-print-color-adjust: exact;
        }
        .page-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .letter-page {
            width: 7.78in;
            height: 11in;
            margin: 0;
            position: relative;
            box-sizing: border-box;
            padding: 160px 35px 100px 35px;
        }
        .content-area {
            position: relative;
            z-index: 2;
        }
        .header-section {
            margin-bottom: 40px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .header-table td {
            padding: 0;
            vertical-align: top;
        }
        .company-details {
            width: 40%;
            padding-right: 20px;
        }
        .date-container {
            width: 20%;
            text-align: center;
            border: none;
        }
        .client-details {
            width: 40%;
            text-align: right;
        }
        h4, p { margin: 0 0 5px 0; color: #333; }
        .service-card-heading {
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
        }
        .service-card-body table {
            border-collapse: separate; 
            border-spacing: 0;
            width: 100%;
            border: 1px solid #d1d5db;
            table-layout: fixed;
        }
        .service-card-body table th, .service-card-body table td {
            border-bottom: 1px solid #d1d5db; 
            border-right: 1px solid #d1d5db;
            padding: 3px 12px;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
        }
        .service-card-body table th:first-child, .service-card-body table td:first-child { 
            width: 30%; 
        }
        .service-card-body table th:last-child, .service-card-body table td:last-child { 
            width: 70%;
            border-right: none;
        }
        .service-card-body table tbody tr:last-child td, .service-card-body table tbody tr:last-child th { 
            border-bottom: none; 
        }
        [contenteditable] {
            outline: none;
        }
        input[type="date"] {
            border: none;
        }
        textarea {
            width: 100%;
            min-height: 100px;
            padding: 5px;
            border: none;
            resize: none;
            overflow-y: hidden;
            font-family: \'Inter\', sans-serif;
            field-sizing: content;
        }
        
        /* --- FIX 2: Signature position --- */
        .signature-section {
            width: 100%;
            left: 0;
            right: 0;
            padding: 0 35px;
            /* Default position is now relative, so it flows with the document */
            position: relative;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .signature-table td {
            padding: 0;
            vertical-align: bottom;
        }
        .client-signature {
            width: 50%;
            text-align: left;
        }
        .company-signature {
            width: 50%;
            text-align: right;
            position: relative;
        }
        .signature-line {
            width: 80%;
            border-bottom: 1px solid #000;
            margin-top: 40px;
        }
        .company-sign {
            width: 150px;
            height: auto;
            display: block;
            margin-left: auto;
            margin-top: 10px;
            z-index: 2;
        }
        .company-stamp {
            width: 120px;
            height: auto;
            position: absolute;
            bottom: 26px;
            right: 0px;
            opacity: 0.9;
            z-index: 1;
        }
        /* This rule now correctly positions the signature on the last page */
        .letter-page.last-page-with-signature .signature-section {
            position: absolute;
            bottom: 35px;
        }
        .letter-page.last-page-with-signature {
            padding-bottom: 150px;
        }
    </style>';

    try {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'autoLangToFont' => true,
        ]);
        
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->SetWatermarkImage($letterheadPath, 0.9, 'P', 'P');
        $mpdf->showWatermarkImage = true;

        $mpdf->WriteHTML($css);
        $mpdf->WriteHTML($htmlContent);
        
        $mpdf->Output('quotation.pdf', 'I');
    } catch (\Mpdf\MpdfException $e) {
        error_log("mPDF Error: " . $e->getMessage());
        http_response_code(500);
        echo 'An internal server error occurred while generating the PDF.';
    }
} else {
    http_response_code(400);
    echo 'Invalid request.';
}