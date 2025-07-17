<?php
session_start();
require_once('bhavidb.php'); // Your database connection

// This line creates the full, absolute path for your images
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// 1. GET THE PAYMENT ID SECURELY
if (!isset($_GET['payment_id']) || !is_numeric($_GET['payment_id'])) {
    die("Invalid Payment ID.");
}
$payment_id = (int)$_GET['payment_id'];

// 2. FETCH THE SPECIFIC PAYMENT DATA
$stmt_payment = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
$stmt_payment->bind_param("i", $payment_id);
$stmt_payment->execute();
$result_payment = $stmt_payment->get_result();
if ($result_payment->num_rows === 0) {
    die("Payment not found.");
}
$payment_details = $result_payment->fetch_assoc();
$stmt_payment->close();

$invoice_sid = $payment_details['invoice_sid']; // Get invoice_sid from the payment

// 3. FETCH THE MAIN INVOICE DATA (using invoice_sid from payment)
$stmt_invoice = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_sid);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();
if ($result_invoice->num_rows === 0) {
    die("Associated Invoice not found.");
}
$invoice = $result_invoice->fetch_assoc();
$stmt_invoice->close();

// Re-calculate balances for this specific payment context
$original_grand_total = $invoice['Grandtotal'];
// Get total paid up to this payment (assuming payments are recorded chronologically or total_paid reflects current state)
// For a receipt, we show the state *after* this payment.
$total_paid_after_this_payment = $invoice['total_paid']; // This reflects the total paid including the current payment
$balance_due_after_this_payment = $invoice['balance_due']; // This reflects the balance after all payments including this one


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - <?php echo htmlspecialchars($payment_details['payment_id']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }

        .invoice-container {
            background-color: white;
            border-radius: 50px;
            padding: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        h6 {
            font-weight: normal;
        }

        .table {
            border: 1px solid #dee2e6;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .payment-details h6 {
            margin-bottom: 0.5rem;
        }

        /* Print-specific styles */
        @media print {
            body {
                background-color: white;
            }

            .no-print {
                display: none !important;
            }

            .invoice-container {
                margin: 0;
                border-radius: 0;
                padding: 0;
                border: none;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <section>
                <div class="col-lg-12">
                    <div class="invoice-container payment-receipt-card" id="receipt-<?= htmlspecialchars($payment_details['payment_id']) ?>">

                        <div class="text-center my-4 no-print">
                            <button id="myPrintButton" class="btn btn-primary">Print Receipt</button>
                            </div>

                        <img src="<?php echo $base_url; ?>/img/Bhavi-Logo-2.png" alt="Bhavi Creations Logo" class="mx-auto d-block img-fluid pt-3" style="max-height: 100px;">

                        <div class="row container pt-5 ps-5 mb-5">
                            <div class="col-6">
                                <h5><strong>Receipt Date:</strong> <?php echo date("d-m-Y", strtotime($payment_details['payment_date'])); ?></h5>
                            </div>
                            <div class="col-6 text-end">
                                <h5><strong>Original Invoice Number:</strong> BHAVI_KKD_2024_<?php echo htmlspecialchars($invoice['Invoice_no']); ?></h5>
                                <h5><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_details['payment_id']); ?></h5>
                            </div>
                        </div>

                        <div class="container ps-5 mb-5">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="pb-2"><strong>From:</strong></h4>
                                    <address>
                                        <h5>Bhavi Creations Pvt Ltd</h5>
                                        <h6>Plot no28, H No70, 17-28, RTO Office Rd,</h6>
                                        <h6>RangaRaoNagar, Kakinada, AndhraPradesh 533003.</h6>
                                        <h6><strong>Phone:</strong> 9642343434</h6>
                                        <h6><strong>Email:</strong> admin@bhavicreations.com</h6>
                                        <h6><strong>GSTIN:</strong> 37AAKCB6960H1ZB</h6>
                                    </address>
                                </div>
                                <div class="col-6 text-end">
                                    <h4 class="pb-2"><strong>To (Paid By):</strong></h4>
                                    <address>
                                        <h5><?php echo htmlspecialchars($invoice['Company_name']); ?></h5>
                                        <h6><?php echo htmlspecialchars($invoice['Cname']); ?></h6>
                                        <h6><?php echo htmlspecialchars($invoice['Caddress']); ?></h6>
                                        <h6><strong>Phone:</strong> <?php echo htmlspecialchars($invoice['Cphone']); ?></h6>
                                        <h6><strong>Email:</strong> <?php echo htmlspecialchars($invoice['Cmail']); ?></h6>
                                        <h6><strong>GSTIN:</strong> <?php echo htmlspecialchars($invoice['Cgst']); ?></h6>
                                    </address>
                                </div>
                            </div>
                        </div>

                        <div class="billing px-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="background-color: #e9ecef;">
                                        <tr>
                                            <th colspan="2">Payment Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start" style="width: 50%;"><strong>Amount Paid for this Transaction:</strong></td>
                                            <td class="text-end">₹<?php echo number_format($payment_details['amount_paid'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><strong>Payment Date:</strong></td>
                                            <td class="text-end"><?php echo date("d-m-Y", strtotime($payment_details['payment_date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><strong>Payment Method:</strong></td>
                                            <td class="text-end"><?php echo htmlspecialchars($payment_details['payment_method']); ?></td>
                                        </tr>
                                        <?php if (!empty($payment_details['reference_number'])): ?>
                                            <tr>
                                                <td class="text-start"><strong>Reference Number:</strong></td>
                                                <td class="text-end"><?php echo htmlspecialchars($payment_details['reference_number']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($payment_details['notes'])): ?>
                                            <tr>
                                                <td class="text-start"><strong>Payment Notes:</strong></td>
                                                <td class="text-end"><?php echo nl2br(htmlspecialchars($payment_details['notes'])); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-end"><strong>Original Invoice Grand Total:</strong></td>
                                            <td class="text-end">₹<?php echo number_format($original_grand_total, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end"><strong>Total Amount Paid (Cumulative):</strong></td>
                                            <td class="text-end">₹<?php echo number_format($total_paid_after_this_payment, 2); ?></td>
                                        </tr>
                                        <tr class="bg-light">
                                            <td class="text-end"><strong>Remaining Balance Due:</strong></td>
                                            <td class="text-end"><strong>₹<?php echo number_format($balance_due_after_this_payment, 2); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Original Invoice Note:</strong></p>
                                    <p><?php echo nl2br(htmlspecialchars($invoice['Note'])); ?></p>
                                </div>
                                <div class="col-lg-6">
                                    <?php
                                    // Fetch and display attached files for the original invoice
                                    $files = [];
                                    $stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
                                    $stmt_files->bind_param("i", $invoice_sid);
                                    $stmt_files->execute();
                                    $result_files = $stmt_files->get_result();
                                    while ($row = $result_files->fetch_assoc()) {
                                        $files[] = $row;
                                    }
                                    $stmt_files->close();
                                    ?>
                                    <?php if (!empty($files)): ?>
                                        <p><strong>Original Invoice Attachments:</strong></p>
                                        <ul>
                                            <?php foreach ($files as $file): ?>
                                                <li><a href="uploads/<?php echo htmlspecialchars($file['File_path']); ?>" target="_blank"><?php echo htmlspecialchars(substr($file['File_path'], strpos($file['File_path'], '-', strpos($file['File_path'], '-') + 1) + 1)); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="container pt-3 mb-4 payment-details">
                            <div class="row">
                                <?php if ($invoice['payment_details_type'] == 'office'): ?>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Scan to Pay (Company):</strong></h5>
                                        <img src="<?php echo $base_url; ?>/img/qrcode.jpg" alt="Office QR Code" style="height:120px; width:120px;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Bank Account Details (Company)</strong></h5>
                                        <h6><strong>Bank Name:</strong> HDFC Bank, Kakinada</h6>
                                        <h6><strong>Account Name:</strong> Bhavi Creations Private Limited</h6>
                                        <h6><strong>Account No.:</strong> 59213749999999</h6>
                                        <h6><strong>IFSC:</strong> HDFC0000426</h6>
                                    </div>
                                <?php elseif ($invoice['payment_details_type'] == 'personal'): ?>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Scan to Pay (Personal):</strong></h5>
                                        <img src="<?php echo $base_url; ?>/img/personal_qrcode.jpg" alt="Personal QR Code" style="height:120px; width:120px;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Bank Account Details (Personal)</strong></h5>
                                        <h6><strong>Bank Name:</strong> State Bank Of India</h6>
                                        <h6><strong>Account Name:</strong> Chalikonda Naga Phaneendra Naidu</h6>
                                        <h6><strong>Account No.:</strong> 20256178992</h6>
                                        <h6><strong>IFSC:</strong> SBIN00001917</h6>
                                        <h6><strong>Google Pay, Phone Pay, Paytm:</strong> 8686394079</h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                           
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const printButton = document.getElementById("myPrintButton");
            if (printButton) {
                printButton.addEventListener("click", function() {
                    window.print();
                });
            }
        });
    </script>
</body>

</html>