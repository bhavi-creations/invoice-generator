<?php
session_start();
require_once('bhavidb.php'); // Your database connection file

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$invoice_sid = isset($_GET['invoice_sid']) ? intval($_GET['invoice_sid']) : 0;
$invoice_no_display = isset($_GET['invoice_no']) ? htmlspecialchars($_GET['invoice_no']) : 'N/A';

if ($invoice_sid === 0) {
    die("<div class='alert alert-danger'>Invoice ID not provided.</div>");
}

// Fetch invoice details for header display
$stmt_invoice = $conn->prepare("SELECT Invoice_no, Invoice_date, Grandtotal, total_paid, balance_due, Cname, Company_name FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_sid);
$stmt_invoice->execute();
$invoice_details = $stmt_invoice->get_result()->fetch_assoc();
$stmt_invoice->close();

if (!$invoice_details) {
    die("<div class='alert alert-danger'>Invoice not found.</div>");
}

// Fetch all payments for this invoice
$stmt_payments = $conn->prepare("SELECT * FROM payments WHERE invoice_sid = ? ORDER BY payment_date DESC, payment_id DESC");
$stmt_payments->bind_param("i", $invoice_sid);
$stmt_payments->execute();
$payments_result = $stmt_payments->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>

            <section class="col-lg-10">
                <div class="container mt-5">
                    <h4 class="mb-4">Payment History for Invoice:
                        <strong>BHAVI_KKD_2024_<?= htmlspecialchars($invoice_details['Invoice_no']) ?></strong>
                        <br>
                        <small class="text-muted">Customer: <?= htmlspecialchars($invoice_details['Cname']) ?> (<?= htmlspecialchars($invoice_details['Company_name']) ?>)</small>
                    </h4>

                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">Invoice Summary</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Invoice Date:</strong> <?= htmlspecialchars($invoice_details['Invoice_date']) ?><br>
                                    <strong>Grand Total:</strong> ₹<?= number_format($invoice_details['Grandtotal'], 2) ?>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <strong>Total Paid:</strong> ₹<?= number_format($invoice_details['total_paid'], 2) ?><br>
                                    <strong>Balance Due:</strong> ₹<span class="<?= ($invoice_details['balance_due'] <= 0) ? 'text-success' : 'text-danger' ?>"><?= number_format($invoice_details['balance_due'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">All Recorded Payments:</h5>
                    <?php if ($payments_result->num_rows > 0) : ?>
                        <div class="table-responsive mango">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Date</th>
                                        <th>Amount Paid</th>
                                        <th>Method</th>
                                        <th>Reference No.</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($payment = $payments_result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                                            <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                                            <td>₹<?= number_format($payment['amount_paid'], 2) ?></td>
                                            <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                                            <td><?= htmlspecialchars($payment['reference_number'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($payment['notes'] ?? 'N/A') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary view-payment-receipt" data-payment-id="<?= htmlspecialchars($payment['payment_id']) ?>">
                                                    View Receipt
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">No payments recorded for this invoice yet.</div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentReceiptModalLabel">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentReceiptContent">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="printReceiptBtn">Print Receipt (PDF)</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentReceiptElement = null; // To hold the element for printing

            // Function to load and display payment receipt
            function loadPaymentReceipt(paymentId) {
                $.ajax({
                    url: 'generate_payment_receipt.php', // This file will generate the receipt HTML
                    type: 'GET',
                    data: {
                        payment_id: paymentId
                    },
                    success: function(response) {
                        $('#paymentReceiptContent').html(response); // Load receipt HTML into modal body
                        $('#paymentReceiptModal').modal('show'); // Show the receipt modal
                        currentReceiptElement = document.querySelector('#paymentReceiptContent .payment-receipt-card'); // Set the element to print
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading payment receipt:", error);
                        alert("Could not load payment receipt. " + (xhr.responseText || ''));
                    }
                });
            }

            // Event listener for "View Receipt" buttons within the payment history table
            $(document).on('click', '.view-payment-receipt', function() {
                const paymentId = $(this).data('payment-id');
                loadPaymentReceipt(paymentId);
            });

            // Print Receipt Button in the Payment Receipt Modal
            $('#printReceiptBtn').on('click', function() {
                if (!currentReceiptElement) {
                    alert("No receipt to print.");
                    return;
                }

                if (typeof html2canvas === 'undefined' || typeof jspdf === 'undefined') {
                    alert("PDF generation libraries (html2canvas, jspdf) are not loaded. Please check your includes.");
                    console.error("PDF generation libraries not found.");
                    return;
                }

                // Temporarily hide modal footer and header for cleaner print
                const modalDialog = $(currentReceiptElement).closest('.modal-dialog');
                const modalHeader = modalDialog.find('.modal-header');
                const modalFooter = modalDialog.find('.modal-footer');

                modalHeader.hide();
                modalFooter.hide();

                html2canvas(currentReceiptElement, {
                    scale: 2
                }).then(canvas => {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF('p', 'mm', 'a4');
                    const imgData = canvas.toDataURL('image/png');
                    const imgProps = doc.getImageProperties(imgData);
                    const pdfWidth = doc.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    // Generate dynamic file name based on payment ID and invoice no
                    const invoiceNo = "<?= htmlspecialchars($invoice_details['Invoice_no']) ?>";
                    const paymentId = currentReceiptElement.id.replace('receipt-', '');
                    doc.save(`Payment_Receipt_Invoice_${invoiceNo}_P_${paymentId}.pdf`);
                }).catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF receipt. Please try again.");
                }).finally(() => {
                    // Always show them back
                    modalHeader.show();
                    modalFooter.show();
                });
            });
        });
    </script>
</body>

</html>