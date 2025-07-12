<?php
session_start();
require_once('bhavidb.php'); // Your database connection

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

if (!isset($_GET['name']) || empty($_GET['name'])) {
    echo "Customer name not specified.";
    exit();
}

$customer_name = $_GET['name'];

// Fetch invoices for the customer, including new payment-related columns
$sql = "SELECT i.Sid, i.Invoice_no, i.Cname, i.Invoice_date, i.Grandtotal, i.status, 
               i.total_paid, i.balance_due, i.payment_status,
               c.Company_name, c.Address, c.Phone
        FROM invoice i
        INNER JOIN customer c ON i.Cname = c.Name -- Assuming Cname in invoice links to Name in customer
        WHERE i.Cname = ? 
        ORDER BY i.Invoice_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();

// Fetch customer details for the modal (optional, but good for context)
$customer_details_query = $conn->prepare("SELECT * FROM customer WHERE Name = ? LIMIT 1");
$customer_details_query->bind_param("s", $customer_name);
$customer_details_query->execute();
$customer_details_result = $customer_details_query->get_result();
$customer_data = $customer_details_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoices for <?= htmlspecialchars($customer_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">
    <style>
        .table-responsive.mango {
            max-width: 100%;
            /* Ensure it doesn't overflow on larger screens */
        }

        .status-badge {
            padding: .35em .65em;
            border-radius: .25rem;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
        }

        .bg-paid {
            background-color: #28a745 !important;
            color: white;
        }

        /* Green */
        .bg-partial {
            background-color: #007bff !important;
            color: white;
        }

        /* Blue */
        .bg-unpaid,
        .bg-pending {
            background-color: #dc3545 !important;
            color: white;
        }

        /* Red */

        .modal-body .form-text {
            font-size: 0.85em;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php'); ?>

            <!-- Main Content -->
            <section class="col-lg-10">
                <div class="container mt-5">
                    <h4 class="mb-4">Invoices for: <strong><?= htmlspecialchars($customer_name) ?></strong></h4>

                    <div class="mb-3">
                        <input type="text" id="invoice_filter" class="form-control" placeholder="Search Invoice by Customer Name, Invoice No, Date...">
                    </div>

                    <div class="table-responsive mango" style="max-height: 650px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover viewinvoicetable" id="invoice_table">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th>Issued Date</th>
                                    <th>Invoice Amount</th>
                                    <th>Amount Paid</th>
                                    <th>Balance Due</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <th>Add Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                        // Determine badge class based on payment_status
                                        $status_class = 'bg-unpaid';
                                        if ($row['payment_status'] === 'Paid') {
                                            $status_class = 'bg-paid';
                                        } elseif ($row['payment_status'] === 'Partial') {
                                            $status_class = 'bg-partial';
                                        }
                                ?>
                                        <tr id="invoice-row-<?= htmlspecialchars($row['Sid']) ?>">
                                            <td><?= htmlspecialchars($row['Invoice_no']) ?></td>
                                            <td><?= htmlspecialchars($row['Invoice_date']) ?></td>
                                            <td>₹<?= number_format($row['Grandtotal'], 2) ?></td>
                                            <td class="total-paid-cell">₹<?= number_format($row['total_paid'], 2) ?></td>
                                            <td class="balance-due-cell">₹<?= number_format($row['balance_due'], 2) ?></td>
                                            <td class="payment-status-cell"><span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($row['payment_status']) ?></span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <!-- Edit Button -->
                                                    <a href="edit.php?Sid=<?= $row['Sid'] ?>" title="Edit" class="btn btn-sm btn-primary me-1">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <!-- View Invoice Button (links to print.php) -->
                                                    <a href="print.php?Sid=<?= $row['Sid'] ?>" title="View Invoice" class="btn btn-sm btn-success me-1">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form method="POST" action="delete_invoice.php" onsubmit="return confirm('Are you sure you want to delete this invoice?');" style="display:inline;">
                                                        <input type="hidden" name="sid" value="<?= $row['Sid'] ?>">
                                                        <button type="submit" title="Delete Invoice" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- Add Payment Button -->
                                                <button type="button" class="btn btn-sm btn-info add-payment-btn"
                                                    data-bs-toggle="modal" data-bs-target="#addPaymentModal"
                                                    data-invoice-sid="<?= htmlspecialchars($row['Sid']) ?>"
                                                    data-invoice-no="<?= htmlspecialchars($row['Invoice_no']) ?>"
                                                    data-grand-total="<?= htmlspecialchars($row['Grandtotal']) ?>"
                                                    data-balance-due="<?= htmlspecialchars($row['balance_due']) ?>"
                                                    data-customer-name="<?= htmlspecialchars($row['Cname']) ?>"
                                                    data-customer-company="<?= htmlspecialchars($row['Company_name']) ?>">
                                                    ₹ Add Payment
                                                </button>
                                                <!-- History Button (for Advance History, using original data-id) -->
                                                <a href="payment_history_details.php?invoice_sid=<?= htmlspecialchars($row['Sid']) ?>&invoice_no=<?= htmlspecialchars($row['Invoice_no']) ?>" class="btn btn-sm" style="background-color: #6f42c1; color: white; margin-left: 5px;" title="View Payment History">
                                                    <i class="bi bi-clock-history"></i> History
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No invoices found for this customer.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Advance Modal (Existing) -->
                <div class="modal fade" id="advance_frm" tabindex="-1" aria-labelledby="advanceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="advanceModalLabel">Advance History</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Advance history content will be loaded here dynamically via JS -->
                                <p>Loading advance history...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NEW: Add Payment Modal -->
                <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addPaymentModalLabel">Add Payment for Invoice <span id="modalInvoiceNo"></span></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addPaymentForm">
                                    <input type="hidden" name="invoice_sid" id="addPaymentInvoiceSid">
                                    <p><strong>Customer:</strong> <span id="modalCustomerName"></span> (<span id="modalCustomerCompany"></span>)</p>
                                    <p><strong>Invoice Total:</strong> ₹<span id="modalGrandTotal"></span></p>
                                    <p><strong>Current Balance Due:</strong> ₹<span id="modalBalanceDue" class="text-danger"></span></p>

                                    <div class="mb-3">
                                        <label for="paymentAmount" class="form-label">Amount Paid</label>
                                        <input type="number" step="0.01" class="form-control" id="paymentAmount" name="amount_paid" required min="0.01">
                                        <div class="form-text text-danger" id="paymentAmountWarning"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paymentDate" class="form-label">Payment Date</label>
                                        <input type="date" class="form-control" id="paymentDate" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Online Payment">Online Payment (UPI/Card)</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="referenceNumber" class="form-label">Reference Number (Optional)</label>
                                        <input type="text" class="form-control" id="referenceNumber" name="reference_number">
                                    </div>
                                    <div class="mb-3">
                                        <label for="paymentNotes" class="form-label">Notes (Optional)</label>
                                        <textarea class="form-control" id="paymentNotes" name="notes" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Record Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NEW: Payment Receipt Modal -->
                <div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="paymentReceiptModalLabel">Payment Receipt</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="paymentReceiptContent">
                                <!-- Receipt content will be loaded here dynamically -->
                                <p>Loading receipt...</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="printReceiptBtn">Print Receipt</button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- For PDF generation of receipts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            // Filter customer table (existing functionality)
            $('#invoice_filter').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#invoice_table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // --- Add Payment Modal Logic ---
            let currentInvoiceSid = null;
            let currentInvoiceBalanceDue = 0;
            let currentInvoiceNo = '';

            $('#addPaymentModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // Button that triggered the modal
                currentInvoiceSid = button.data('invoice-sid');
                currentInvoiceNo = button.data('invoice-no');
                currentInvoiceBalanceDue = parseFloat(button.data('balance-due'));
                const customerName = button.data('customer-name');
                const customerCompany = button.data('customer-company');
                const grandTotal = parseFloat(button.data('grand-total'));

                const modal = $(this);
                modal.find('#addPaymentInvoiceSid').val(currentInvoiceSid);
                modal.find('#modalInvoiceNo').text(currentInvoiceNo);
                modal.find('#modalCustomerName').text(customerName);
                modal.find('#modalCustomerCompany').text(customerCompany);
                modal.find('#modalGrandTotal').text(grandTotal.toFixed(2));
                modal.find('#modalBalanceDue').text(currentInvoiceBalanceDue.toFixed(2));
                modal.find('#paymentAmount').val(''); // Clear previous amount
                modal.find('#paymentAmount').attr('max', currentInvoiceBalanceDue); // Set max for validation
                modal.find('#paymentAmountWarning').text(`Max: ₹${currentInvoiceBalanceDue.toFixed(2)}`);
                modal.find('#paymentDate').val('<?= date('Y-m-d') ?>'); // Reset date
                modal.find('#paymentMethod').val('Cash'); // Reset method
                modal.find('#referenceNumber').val(''); // Clear reference
                modal.find('#paymentNotes').val(''); // Clear notes

                // Update balanceDue class based on value
                if (currentInvoiceBalanceDue <= 0) {
                    modal.find('#modalBalanceDue').removeClass('text-danger').addClass('text-success');
                } else {
                    modal.find('#modalBalanceDue').removeClass('text-success').addClass('text-danger');
                }
            });

            // Input validation for payment amount
            $('#paymentAmount').on('input', function() {
                let amount = parseFloat($(this).val());
                if (isNaN(amount) || amount <= 0) {
                    $('#paymentAmountWarning').text('Amount must be greater than 0.');
                    $(this).addClass('is-invalid').removeClass('is-valid');
                } else if (amount > currentInvoiceBalanceDue) {
                    $('#paymentAmountWarning').text(`Amount cannot exceed balance due: ₹${currentInvoiceBalanceDue.toFixed(2)}`);
                    $(this).addClass('is-invalid').removeClass('is-valid');
                } else {
                    $('#paymentAmountWarning').text('');
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // --- Process Add Payment Form Submission ---
            // --- Process Add Payment Form Submission ---
            $('#addPaymentForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const form = $(this);
                // Serialize form data into an array of objects
                const formData = form.serializeArray();
                // Add the 'add_payment' flag to the data array
                formData.push({
                    name: 'add_payment',
                    value: true
                });

                // Basic client-side validation
                let amount = parseFloat($('#paymentAmount').val());
                if (isNaN(amount) || amount <= 0 || amount > currentInvoiceBalanceDue) {
                    alert("Please enter a valid payment amount that does not exceed the balance due.");
                    return;
                }

                $.ajax({
                    url: 'process_installment_payment.php', // New backend file
                    type: 'POST',
                    data: $.param(formData), // Use $.param to correctly convert the array to a URL-encoded string
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#addPaymentModal').modal('hide'); // Close the modal

                            // Update the specific invoice row in the table
                            const $invoiceRow = $(`#invoice-row-${response.invoice_sid}`);
                            $invoiceRow.find('.total-paid-cell').text(`₹${parseFloat(response.new_total_paid).toFixed(2)}`);
                            $invoiceRow.find('.balance-due-cell').text(`₹${parseFloat(response.new_balance_due).toFixed(2)}`);

                            // Update balanceDue class
                            const $balanceDueCell = $invoiceRow.find('.balance-due-cell');
                            if (response.new_balance_due <= 0) {
                                $balanceDueCell.removeClass('text-danger').addClass('text-success');
                            } else {
                                $balanceDueCell.removeClass('text-success').addClass('text-danger');
                            }

                            // Update payment status badge
                            let statusClass = 'bg-unpaid';
                            if (response.new_payment_status === 'Paid') {
                                statusClass = 'bg-paid';
                            } else if (response.new_payment_status === 'Partial') {
                                statusClass = 'bg-partial';
                            }
                            $invoiceRow.find('.payment-status-cell .status-badge').text(response.new_payment_status).removeClass('bg-paid bg-partial bg-unpaid bg-pending').addClass(statusClass);

                            // Optionally, update the data attributes for the "Add Payment" button
                            $invoiceRow.find('.add-payment-btn').data('balance-due', response.new_balance_due);

                            // Load and display the new payment receipt in its own modal
                            loadPaymentReceipt(response.payment.payment_id);

                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred while adding payment. Please try again. " + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            });

            // --- Payment Receipt Modal Logic ---
            let currentReceiptElement = null; // To hold the element for printing

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
                        alert("Could not load payment receipt.");
                    }
                });
            }

            // Event listener for "View Receipt" buttons (from payment history in print.php, if any)
            // This is if you later add a payment history table to customer_invoices.php or want to keep the link from print.php
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
                    doc.save(`Payment_Receipt_Invoice_${currentInvoiceNo}_P_${$(currentReceiptElement).attr('id').replace('receipt-', '')}.pdf`);
                }).catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF receipt. Please try again.");
                }).finally(() => {
                    // Always show them back
                    modalHeader.show();
                    modalFooter.show();
                });
            });

            // --- Status Dropdown Functionality (if you want to keep it) ---
            // This part of the code was in your original customer_invoices.php
            // If you want to remove the status dropdown, you can delete this section.
            $('.status-dropdown').on('change', function() {
                var newStatus = $(this).val();
                var invoiceNo = $(this).data('invoice-no'); // Assuming this is actually Sid, not Invoice_no, check your DB

                if (newStatus && invoiceNo) {
                    $.ajax({
                        url: 'update_invoice_status.php', // You'll need to create this file
                        type: 'POST',
                        data: {
                            invoice_no: invoiceNo, // Use Sid here if that's what data-invoice-no actually holds
                            status: newStatus
                        },
                        success: function(response) {
                            alert('Status updated successfully!');
                            // You might want to update the badge visually here
                            // For example: $(`#invoice-row-${invoiceNo} .status-badge`).text(newStatus).removeClass().addClass('status-badge bg-' + newStatus);
                        },
                        error: function() {
                            alert('Failed to update status.');
                        }
                    });
                }
            });
            // End Status Dropdown Functionality
        });
    </script>

</body>

</html>