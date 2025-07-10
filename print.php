<?php
session_start();
require_once('bhavidb.php'); // Your database connection

// This line creates the full, absolute path for your images
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// 1. GET THE INVOICE ID SECURELY
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Invoice ID.");
}
$invoice_id = (int)$_GET['Sid'];

// 2. FETCH THE MAIN INVOICE DATA
$stmt_invoice = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();
if ($result_invoice->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $result_invoice->fetch_assoc();
$stmt_invoice->close();

// 3. FETCH THE SERVICE LINE ITEMS
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
$stmt_services->bind_param("i", $invoice_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// 4. FETCH THE ATTACHED FILES
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
$stmt_files->bind_param("i", $invoice_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo htmlspecialchars($invoice['Invoice_no']); ?></title>
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

                    <div class="invoice-container">

                        <div class="text-center my-4 no-print">
                            <!-- <button id="myPrintButton" class="btn btn-primary">Print Invoice</button> -->
                            <a href="viewinvoices.php" class="btn btn-secondary">Go Back</a>
                        </div>


                        <img src="<?php echo $base_url; ?>/img/Bhavi-Logo-2.png" alt="Bhavi Creations Logo" class="mx-auto d-block img-fluid pt-3" style="max-height: 100px;">

                        <div class="row container pt-5 ps-5 mb-5">
                            <div class="col-6">
                                <h5><strong>Date:</strong> <?php echo date("d-m-Y", strtotime($invoice['Invoice_date'])); ?></h5>
                            </div>
                            <div class="col-6 text-end">
                                <h5><strong>Invoice Number:</strong> BHAVI_KKD_2024_<?php echo htmlspecialchars($invoice['Invoice_no']); ?></h5>
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
                                    <h4 class="pb-2"><strong>To (Bill To):</strong></h4>
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
                                    <tbody>
                                        <?php $counter = 1;
                                        foreach ($services as $service): ?>
                                            <tr>
                                                <td><?php echo sprintf('%02d', $counter++); ?></td>
                                                <td><?php echo htmlspecialchars($service['Sname']); ?></td>
                                                <td><?php echo htmlspecialchars($service['Description']); ?></td>
                                                <td><?php echo htmlspecialchars($service['Qty']); ?></td>
                                                <td><?php echo number_format((float)$service['Price'], 2); ?></td>
                                                <td><?php echo number_format((float)$service['Totalprice'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($service['Discount']); ?></td>
                                                <td><?php echo number_format((float)$service['Finaltotal'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" rowspan="5" style="text-align: left; vertical-align: bottom; border:none;">
                                                <p class="mb-2"><strong>Total in words:</strong><br><?php echo htmlspecialchars($invoice['Totalinwords']); ?></p>
                                                <p><strong>Balance in words:</strong><br><?php echo htmlspecialchars($invoice['balancewords']); ?></p>
                                            </td>
                                            <td style="text-align: right;"><strong>Subtotal</strong></td>
                                            <td><?php echo number_format((float)$invoice['Final'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;"><strong>GST %</strong></td>
                                            <td><?php echo htmlspecialchars($invoice['Gst']); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;"><strong>GST Total</strong></td>
                                            <td><?php echo number_format((float)$invoice['Gst_total'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;" class="bg-light"><strong>Grand Total</strong></td>
                                            <td class="bg-light"><strong><?php echo number_format((float)$invoice['Grandtotal'], 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;"><strong>Advance</strong></td>
                                            <td><?php echo number_format((float)$invoice['advance'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="border:none;"></td>
                                            <td style="text-align: right;" class="bg-light"><strong>Balance</strong></td>
                                            <td class="bg-light"><strong><?php echo number_format((float)$invoice['balance'], 2); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Note:</strong></p>
                                    <p><?php echo nl2br(htmlspecialchars($invoice['Note'])); ?></p>
                                </div>
                                <div class="col-lg-6">
                                    <?php if (!empty($files)): ?>
                                        <p><strong>Attachments:</strong></p>
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
                                        <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                        <img src="<?php echo $base_url; ?>/img/qrcode.jpg" alt="Office QR Code" style="height:120px; width:120px;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Payment details</strong></h5>
                                        <h6><strong>Bank Name:</strong> HDFC Bank, Kakinada</h6>
                                        <h6><strong>Account Name:</strong> Bhavi Creations Private Limited</h6>
                                        <h6><strong>Account No.:</strong> 59213749999999</h6>
                                        <h6><strong>IFSC:</strong> HDFC0000426</h6>
                                    </div>
                                <?php elseif ($invoice['payment_details_type'] == 'personal'): ?>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                        <img src="<?php echo $base_url; ?>/img/personal_qrcode.jpg" alt="Personal QR Code" style="height:120px; width:120px;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3"><strong>Payment details</strong></h5>
                                        <h6><strong>Bank Name:</strong> State Bank Of India</h6>
                                        <h6><strong>Account Name:</strong> Chalikonda Naga Phaneendra Naidu</h6>
                                        <h6><strong>Account No.:</strong> 20256178992</h6>
                                        <h6><strong>IFSC:</strong> SBIN00001917</h6>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <h6 class="border p-2"><strong>Google Pay, Phone Pay, Paytm:</strong> 8686394079</h6>
                                </div>
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