<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}
require_once('bhavidb.php'); // Your existing database connection file, provides $conn

// --- Include mPDF Autoloader ---
require_once 'vendor/autoload.php';

// No 'use' statement for Dompdf anymore, we'll use Mpdf directly
// use Dompdf\Dompdf; // REMOVE THIS LINE
// use Dompdf\Options; // REMOVE THIS LINE

if (isset($_POST["save"]) || isset($_POST["update"])) {

    // --- 1. GET CUSTOMER DETAILS ---
    $selectedCompanyId = (int)$_POST['company'];
    $sql_customer = "SELECT * FROM `customer` WHERE `Id` = ?";
    $stmt_customer = $conn->prepare($sql_customer);
    $stmt_customer->bind_param("i", $selectedCompanyId);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();

    if ($row = $result_customer->fetch_assoc()) {
        $company_name = $row['Company_name'];
        $cname = $row['Name'];
        $cphone = $row['Phone'];
        $caddress = $row['Address'];
        $cemail = $row['Email'];
        $cgst = $row['Gst_no'];
    } else {
        die("Error: Company not found. Please select a valid company.");
    }
    $stmt_customer->close();

    // --- 2. CAPTURE & SECURE INVOICE DATA ---
    $invoice_no = htmlspecialchars($_POST["invoice_no"]);
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $status = htmlspecialchars($_POST["status"]);
    $payment_details_type = htmlspecialchars($_POST['payment_details']);

    $grand_total = floatval($_POST["grand_total"]);
    $gst_percentage = floatval($_POST["gst"]);
    $gst_total = floatval($_POST["gst_total"]);
    $final_total = floatval($_POST["Final_total"]);
    $advance = floatval($_POST["advance"]);
    $balance = floatval($_POST["balance"]);

    $totalinwords = htmlspecialchars($_POST["words"]);
    $balancewords = htmlspecialchars($_POST["balancewords"]);
    $note = htmlspecialchars($_POST["note"]);

    $stamp_image_path_for_db = !empty($_POST['stamp_image_path']) ? htmlspecialchars($_POST['stamp_image_path']) : null;
    $signature_image_path_for_db = !empty($_POST['signature_image_path']) ? htmlspecialchars($_POST['signature_image_path']) : null;

    $terms_value = '';

    if (isset($_POST["save"])) {
        // --- INSERT NEW INVOICE ---
        $sql_invoice = "INSERT INTO invoice (Invoice_no, Invoice_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Customer_id, Final, Gst, Gst_total, Grandtotal, total_paid, balance_due, payment_status, Totalinwords, Terms, Note, advance, balance, balancewords, status, payment_details_type, stamp_image, signature_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_invoice = $conn->prepare($sql_invoice);
        $stmt_invoice->bind_param(
            "ssssssssididddddsssddsssss",
            $invoice_no,
            $invoice_date,
            $company_name,
            $cname,
            $cphone,
            $caddress,
            $cemail,
            $cgst,
            $selectedCompanyId,
            $final_total,
            $gst_percentage,
            $gst_total,
            $grand_total,
            $advance,
            $balance,
            $status,
            $totalinwords,
            $terms_value,
            $note,
            $advance,
            $balance,
            $balancewords,
            $status,
            $payment_details_type,
            $stamp_image_path_for_db,
            $signature_image_path_for_db
        );

        if ($stmt_invoice->execute()) {
            $Sid = $conn->insert_id;

            // --- INSERT SERVICES ---
            if (isset($_POST['Sname']) && is_array($_POST['Sname'])) {
                $sql_service = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_service = $conn->prepare($sql_service);

                foreach ($_POST['Sname'] as $key => $s_name) {
                    $description = htmlspecialchars($_POST['Description'][$key]);
                    $qty = floatval($_POST['Qty'][$key]);
                    $price = floatval($_POST['Price'][$key]);
                    $subtotal = floatval($_POST['subtotal'][$key]);
                    $discount = floatval($_POST['discount'][$key]);
                    $finaltotal = floatval($_POST['total'][$key]);

                    $stmt_service->bind_param(
                        "issdidii",
                        $Sid,
                        $s_name,
                        $description,
                        $qty,
                        $price,
                        $subtotal,
                        $discount,
                        $finaltotal
                    );
                    $stmt_service->execute();
                }
                $stmt_service->close();
            }

            // --- HANDLE FILE UPLOADS ---
            $upload_dir = 'uploads/attachments/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (isset($_FILES['attachments'])) {
                $sql_file = "INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)";
                $stmt_file = $conn->prepare($sql_file);
                foreach ($_FILES['attachments']['name'] as $key => $name) {
                    if ($_FILES['attachments']['error'][$key] == 0) {
                        $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                        $file_name = $Sid . '-' . uniqid() . '-' . basename($name);
                        $target_file = $upload_dir . $file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt_file->bind_param("is", $Sid, $file_name);
                            $stmt_file->execute();
                        }
                    }
                }
                $stmt_file->close();
            }

            // --- START PDF GENERATION AND DOWNLOAD (Using mPDF) ---

            $invoice_data_query = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
            $invoice_data_query->bind_param("i", $Sid);
            $invoice_data_query->execute();
            $invoice_details = $invoice_data_query->get_result()->fetch_assoc();
            $invoice_data_query->close();

            $invoice_items_query = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
            $invoice_items_query->bind_param("i", $Sid);
            $invoice_items_query->execute();
            $invoice_items_result = $invoice_items_query->get_result();
            $invoice_items = [];
            while ($row_item = $invoice_items_result->fetch_assoc()) {
                $invoice_items[] = $row_item;
            }
            $invoice_items_query->close();

            // Construct the HTML for the PDF.
            $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Invoice - BHAVIINVOICE</title>
                <style>
                    body { font-family: sans-serif; margin: 20px; font-size: 10pt; }
                    .container { width: 100%; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header img { max-height: 80px; max-width: 80px; }
                    .invoice-details, .company-details { width: 100%; overflow: hidden; margin-bottom: 20px; }
                    .invoice-details div, .company-details div { width: 48%; float: left; }
                    .invoice-details div:last-child, .company-details div:last-child { float: right; text-align: right; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                    th { background-color: #f2f2f2; text-align: center; }
                    .text-right { text-align: right; }
                    .pull-right { float: right; }
                    .signature-section { text-align: right; margin-top: 30px; }
                    .signature-img, .stamp-img { max-height: 80px; max-width: 80px; }
                    .note { margin-top: 15px; }
                    .payment-details { margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; overflow: hidden;}
                    .payment-details div { width: 48%; float: left; }
                    .payment-details div:last-child { float: right; text-align: right; }
                    .payment-details img { max-height: 50px; max-width: 50px; !important }
                    h3, h4, h5, h6, p { margin-bottom: 5px; margin-top: 5px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                       <img src="' . realpath('img/Bhavi-Logo-2.png') . '" alt="Company Logo"  style="max-height: 28%; max-width: 28%;">
                    </div>

                    <div class="invoice-details">
                        <div>
                            <strong>Date:</strong> ' . htmlspecialchars($invoice_details['Invoice_date']) . '<br>
                            <strong>Customer/Company:</strong> ' . htmlspecialchars($invoice_details['Company_name']) . '<br>
                            ' . htmlspecialchars($invoice_details['Cname']) . '<br>
                            ' . htmlspecialchars($invoice_details['Cmail']) . '<br>
                            ' . htmlspecialchars($invoice_details['Cphone']) . '<br>
                            GSTIN: ' . htmlspecialchars($invoice_details['Cgst']) . '
                        </div>
                        <div>
                        </div>

                        <div>
                            <strong>Invoice Number:</strong> BHAVI_KKD_2024_' . htmlspecialchars($invoice_details['Invoice_no']) . '<br>
                            <strong>Status:</strong> ' . htmlspecialchars($invoice_details['status']) . '
                        </div>
                    </div>

                    <div class="company-details">
                        <div>
                            <h4>Bhavi Creations Pvt Ltd</h4>
                            <address>
                                Plot no28, H No70, 17-28, RTO Office Rd,<br>
                                RangaRaoNagar, Kakinada,<br>
                                AndhraPradesh 533003.
                            </address>
                        </div>
                        <div>
                            <h4>Contact</h4>
                            <address>
                                Phone no.: 9642343434<br>
                                Email: admin@bhavicreations.com<br>
                                GSTIN: 37AAKCB6960H1ZB.
                            </address>
                        </div>
                    </div>

                    <h3>BILLING</h3>
                    <table>
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
            $sno = 1;
            foreach ($invoice_items as $item) {
                $html .= '<tr>
                                <td>' . sprintf('%02d', $sno++) . '</td>
                                <td>' . htmlspecialchars($item['Sname']) . '</td>
                                <td>' . htmlspecialchars($item['Description']) . '</td>
                                <td>' . htmlspecialchars(number_format($item['Qty'], 0)) . '</td>
                                <td>' . htmlspecialchars(number_format($item['Price'], 2)) . '</td>
                                <td>' . htmlspecialchars(number_format($item['Totalprice'], 2)) . '</td>
                                <td>' . htmlspecialchars(number_format($item['Discount'], 2)) . '</td>
                                <td>' . htmlspecialchars(number_format($item['Finaltotal'], 2)) . '</td>
                            </tr>';
            }
            $html .= '</tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-right">Total Before Tax</td>
                                <td class="text-right">' . htmlspecialchars(number_format($invoice_details['Grandtotal'], 2)) . '</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right">GST (' . htmlspecialchars(number_format($invoice_details['Gst'], 2)) . '%)</td>
                                <td colspan="2" class="text-right">' . htmlspecialchars(number_format($invoice_details['Gst_total'], 2)) . '</td>
                            </tr>
                            <tr>
                                <td colspan="6">Amount in words: ' . htmlspecialchars($invoice_details['Totalinwords']) . '</td>
                                <td class="text-right">Total</td>
                                <td class="text-right">' . htmlspecialchars(number_format($invoice_details['Final'], 2)) . '</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-right">Advance</td>
                                <td class="text-right">' . htmlspecialchars(number_format($invoice_details['advance'], 2)) . '</td>
                            </tr>
                            <tr>
                                <td colspan="6">Balance in words: ' . htmlspecialchars($invoice_details['balancewords']) . '</td>
                                <td class="text-right">Balance</td>
                                <td class="text-right">' . htmlspecialchars(number_format($invoice_details['balance'], 2)) . '</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="note">
                        <strong>Note:</strong> ' . htmlspecialchars($invoice_details['Note']) . '
                    </div>

                    <div class="payment-details">
                        <h4>Payment Details</h4>';
            if ($invoice_details['payment_details_type'] == 'office') {
                $html .= '
                                <div>
                                    <h5>Scan to Pay:</h5>
                                    <img src="' . realpath('img/qrcode.jpg') . '" alt="Office QR Code" style="max-height: 30%; max-width: 30%;">         
                       </div>
                                <div>
                                    <h5>Bank Name : HDFC Bank, Kakinada</h5>
                                    <h6>Account Name : Bhavi Creations Private Limited</h6>
                                    <h6>Account No. : 59213749999999</h6>
                                    <h6>IFSC : HDFC0000426</h6>
                                </div>';
            } else { // personal
                $html .= '
                                <div>
                                    <h5>Scan to Pay:</h5>
                                    <img src="' . realpath('img/personal_qrcode.jpg') . '" alt="Personal QR Code" style="max-height: 30%; max-width: 30%;">
                                </div>
                                <div>
                                    <h5>Bank Name : State Bank Of India</h5>
                                    <h6>Account Name : Chalikonda Naga Phaneendra Naidu</h6>
                                    <h6>Account No. : 20256178992</h6>
                                    <h6>IFSC : SBIN00001917</h6>
                                    <h6>Google Pay, Phone Pay, Paytm: 8686394079</h6>
                                </div>';
            }
            $html .= '</div>

                    <div class="signature-section" >

                        ';
            $stamp_path_full = !empty($invoice_details['stamp_image']) ? realpath($invoice_details['stamp_image']) : '';
            $signature_path_full = !empty($invoice_details['signature_image']) ? realpath($invoice_details['signature_image']) : '';

            if ($stamp_path_full && file_exists($stamp_path_full)) {
                $html .= '<img src="' . $stamp_path_full . '" alt="Stamp" class="stamp-img"  style="max-height: 20%; max-width: 20%;" ><br>';
            }
            if ($signature_path_full && file_exists($signature_path_full)) {
                $html .= '<img src="' . $signature_path_full . '" alt="Signature" class="signature-img"    ><br>';
            }
            $html .= '    <p  >Signature</p>
                    </div>
                </div>
            </body>
            </html>';


            // Initialize mPDF
            $mpdf = new \Mpdf\Mpdf(); // Correct way to instantiate mPDF

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Define file name for download
            $filename = 'invoice_' . $invoice_details['Invoice_no'] . '.pdf';

            // Output the PDF for download
            $mpdf->Output($filename, 'D'); // 'D' for download

            exit; // Important: Stop execution after sending the file

            // --- END PDF GENERATION AND DOWNLOAD ---

        } else {
            echo "Invoice Save Failed: " . $stmt_invoice->error;
        }
        $stmt_invoice->close();
    } elseif (isset($_POST["update"])) {
        // --- UPDATE EXISTING INVOICE ---
        $Sid = (int)$_POST['Sid'];

        $sql_invoice_update = "UPDATE invoice SET Invoice_no=?, Invoice_date=?, Company_name=?, Cname=?, Cphone=?, Caddress=?, Cmail=?, Cgst=?, Customer_id=?, Final=?, Gst=?, Gst_total=?, Grandtotal=?, total_paid=?, balance_due=?, payment_status=?, Totalinwords=?, Terms=?, Note=?, advance=?, balance=?, balancewords=?, status=?, payment_details_type=?, stamp_image=?, signature_image=? WHERE Sid=?";

        $stmt_invoice_update = $conn->prepare($sql_invoice_update);
        $stmt_invoice_update->bind_param(
            "ssssssssididddddsssddsssssi",
            $invoice_no,
            $invoice_date,
            $company_name,
            $cname,
            $cphone,
            $caddress,
            $cemail,
            $cgst,
            $selectedCompanyId,
            $final_total,
            $gst_percentage,
            $gst_total,
            $grand_total,
            $advance,
            $balance,
            $status,
            $totalinwords,
            $terms_value,
            $note,
            $advance,
            $balance,
            $balancewords,
            $status,
            $payment_details_type,
            $stamp_image_path_for_db,
            $signature_image_path_for_db,
            $Sid
        );

        if ($stmt_invoice_update->execute()) {
            $conn->query("DELETE FROM service WHERE Sid = $Sid");
            if (isset($_POST['Sname']) && is_array($_POST['Sname'])) {
                $sql_service = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_service = $conn->prepare($sql_service);

                foreach ($_POST['Sname'] as $key => $s_name) {
                    $description = htmlspecialchars($_POST['Description'][$key]);
                    $qty = floatval($_POST['Qty'][$key]);
                    $price = floatval($_POST['Price'][$key]);
                    $subtotal = floatval($_POST['subtotal'][$key]);
                    $discount = floatval($_POST['discount'][$key]);
                    $finaltotal = floatval($_POST['total'][$key]);

                    $stmt_service->bind_param(
                        "issdidii",
                        $Sid,
                        $s_name,
                        $description,
                        $qty,
                        $price,
                        $subtotal,
                        $discount,
                        $finaltotal
                    );
                    $stmt_service->execute();
                }
                $stmt_service->close();
            }

            // Handle file deletions
            if (isset($_POST['delete_files']) && is_array($_POST['delete_files'])) {
                $upload_dir_attachments = 'uploads/attachments/';
                foreach ($_POST['delete_files'] as $file_id_to_delete) {
                    $stmt_get_file = $conn->prepare("SELECT File_path FROM invoice_files WHERE id = ? AND Invoice_id = ?");
                    $stmt_get_file->bind_param("ii", $file_id_to_delete, $Sid);
                    $stmt_get_file->execute();
                    $result_get_file = $stmt_get_file->get_result();
                    if ($row_file = $result_get_file->fetch_assoc()) {
                        $file_to_unlink = $upload_dir_attachments . $row_file['File_path'];
                        if (file_exists($file_to_unlink)) {
                            unlink($file_to_unlink);
                        }
                    }
                    $stmt_get_file->close();

                    $stmt_delete_file = $conn->prepare("DELETE FROM invoice_files WHERE id = ? AND Invoice_id = ?");
                    $stmt_delete_file->bind_param("ii", $file_id_to_delete, $Sid);
                    $stmt_delete_file->execute();
                    $stmt_delete_file->close();
                }
            }

            // Handle new file uploads for update
            $upload_dir = 'uploads/attachments/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (isset($_FILES['attachments'])) {
                $sql_file_new = "INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)";
                $stmt_file_new = $conn->prepare($sql_file_new);
                foreach ($_FILES['attachments']['name'] as $key => $name) {
                    if ($_FILES['attachments']['error'][$key] == 0) {
                        $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                        $file_name = $Sid . '-' . uniqid() . '-' . basename($name);
                        $target_file = $upload_dir . $file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt_file_new->bind_param("is", $Sid, $file_name);
                            $stmt_file_new->execute();
                        }
                    }
                }
                $stmt_file_new->close();
            }

            echo "<script>
                    alert('Invoice Updated Successfully!');
                    window.location.href='viewinvoices.php';
                  </script>";
            exit();
        } else {
            echo "Invoice Update Failed: " . $stmt_invoice_update->error;
        }
        $stmt_invoice_update->close();
    }
} else {
    header("Location: createinvoice.php");
    exit();
}
$conn->close();
